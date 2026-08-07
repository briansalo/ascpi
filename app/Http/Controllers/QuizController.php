<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\QuizResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->hasTakenQuizToday()) {
            return redirect()->route('quiz.review');
        }

        $answeredQuestionIds = $user->quizResults()
            ->whereHas('question', function ($query) use ($user) {
                $query->where('level', $user->level);
            })
            ->pluck('question_id')
            ->toArray();

        $questions = Question::where('level', $user->level)
            ->whereNotIn('id', $answeredQuestionIds)
            ->inRandomOrder()
            ->limit(5)
            ->get();

        // if ($questions->isEmpty()) {
        //     $accuracy = $user->levelAccuracy();
        //     $wrongQuestionIds = $user->levelWrongQuestionIds();

        //     if ($accuracy >= 80) {
        //         $currentLevel = $user->level;
        //         $nextLevel = $user->nextLevel();

        //         if ($nextLevel) {
        //             $user->level = $nextLevel;
        //             $user->save();

        //             return redirect()
        //                 ->route('quiz.index')
        //                 ->with('success', "Congratulations! You scored {$accuracy}% on the {$currentLevel} level and have been promoted to {$nextLevel}. Keep the momentum going — your next challenge is ready, and you’re prepared to level up.")
        //                 ->with('levelUp', $nextLevel)
        //                 ->with('levelUpFrom', $currentLevel);
        //         }

        //         return redirect()
        //             ->route('dashboard')
        //             ->with('success', "Excellent work! You scored {$accuracy}% and have completed the highest level.");
        //     }

        //     if (!empty($wrongQuestionIds)) {
        //         $questions = Question::whereIn('id', $wrongQuestionIds)->get();

        //         return view('quiz', compact('questions'))
        //             ->with('warning', 'Your current level accuracy is below 80%. Review the questions you answered incorrectly before moving on.');
        //     }

        //     return redirect()
        //         ->route('dashboard')
        //         ->with('error', 'No additional review questions were found at this level.');
        // }

        return view('quiz', compact('questions'));
    }

    public function retake()
    {
        $user = Auth::user();
        $lastRetake = $user->quizResults()
            ->where('is_retake', true)
            ->latest('created_at')
            ->first();

        if ($lastRetake && $lastRetake->created_at->gt(now()->subHours(4))) {
            $retryAt = $lastRetake->created_at->addHours(4)->format('g:i a');

            return redirect()
                ->route('dashboard')
                ->with('error', "Retake is only allowed once every 4 hours. You can try again at {$retryAt}.");
        }

        $wrongQuestionIds = $user->levelWrongQuestionIds();

        if (empty($wrongQuestionIds)) {
            return redirect()
                ->route('dashboard')
                ->with('error', 'No retake questions are available right now.');
        }

        $questions = Question::whereIn('id', $wrongQuestionIds)
            ->inRandomOrder()
            ->get();

        $isRetake = true;
        return view('quiz', compact('questions','isRetake'));
    }

    public function review()
    {
        $user = Auth::user();
        $todayResults = QuizResult::with('question')
            ->where('user_id', $user->id)
            ->whereDate('created_at', today())
            ->whereHas('question', function ($query) use ($user) {
                $query->where('level', $user->level);
            })
            ->get();

        if ($todayResults->isEmpty()) {
            return redirect()->route('quiz.index');
        }

        $results = [];
        $score = 0;

        foreach ($todayResults as $result) {
            $isCorrect = $result->is_correct;

            if ($isCorrect) {
                $score++;
            }

            $results[] = [
                'question' => $result->question->question,
                'selected_answer' => $result->selected_answer,
                'correct_answer' => $result->correct_answer,
                'is_correct' => $isCorrect,
                'option_a' => $result->question->option_a,
                'option_b' => $result->question->option_b,
                'option_c' => $result->question->option_c,
                'option_d' => $result->question->option_d,
                'explanation' => $result->question->explanation,
            ];
        }

        return view('quiz-result', [
            'results' => $results,
            'score' => $score,
            'total' => $todayResults->count(),
        ]);
    }

    public function submit(Request $request)
    {
        $user = Auth::user();

        if ($user->hasTakenQuizToday()) {
            return redirect()
                ->route('dashboard')
                ->with('error', 'You already completed today’s quiz. Come back tomorrow for a fresh set of questions.');
        }

        $questionIds = collect($request->input('question_ids', []))
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->unique()
            ->values()
            ->all();

        $answeredQuestionIds = $user->quizResults()->pluck('question_id')->toArray();
        $questions = Question::whereNotIn('id', $answeredQuestionIds)
            ->whereIn('id', $questionIds)
            ->get();

        if ($questions->count() !== count($questionIds) || empty($questionIds)) {
            return redirect()
                ->route('quiz.index')
                ->withErrors(['invalid' => 'Unable to submit the quiz. Please reload the page and try again.']);
        }

        $rules = [];
        foreach ($questions as $question) {
            $rules['question_' . $question->id] = 'required|in:A,B,C,D';
        }

        $validated = $request->validate($rules, [
            'required' => 'Please answer every question before submitting the quiz.',
            'in' => 'Please answer every question before submitting the quiz.',
        ]);

        $results = [];
        $score = 0;

        foreach ($questions as $question) {
            $answerKey = 'question_' . $question->id;
            $selectedAnswer = $validated[$answerKey] ?? null;
            $isCorrect = $selectedAnswer === $question->correct_answer;

            if ($isCorrect) {
                $score++;
            }

            QuizResult::create([
                'user_id' => $user->id,
                'question_id' => $question->id,
                'selected_answer' => $selectedAnswer,
                'correct_answer' => $question->correct_answer,
                'is_correct' => $isCorrect,
                'is_retake' => false,
            ]);

            $results[] = [
                'question' => $question->question,
                'selected_answer' => $selectedAnswer,
                'correct_answer' => $question->correct_answer,
                'is_correct' => $isCorrect,
                'option_a' => $question->option_a,
                'option_b' => $question->option_b,
                'option_c' => $question->option_c,
                'option_d' => $question->option_d,
                'explanation' => $question->explanation,
            ];
        }

        return view('quiz-result', [
            'results' => $results,
            'score' => $score,
            'total' => $questions->count(),
        ]);
    }

    public function retakeSubmit(Request $request)
    {
        $user = Auth::user();
        $lastRetake = $user->quizResults()
            ->where('is_retake', true)
            ->latest('created_at')
            ->first();

        if ($lastRetake && $lastRetake->created_at->gt(now()->subHours(4))) {
            $retryAt = $lastRetake->created_at->addHours(4)->format('g:i a');

            return redirect()
                ->route('dashboard')
                ->with('error', "Retake is only allowed once every 4 hours. You can try again at {$retryAt}.");
        }

        $questionIds = collect($request->input('question_ids', []))
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->unique()
            ->values()
            ->all();

        $questions = Question::whereIn('id', $questionIds)->get();

        if ($questions->count() !== count($questionIds) || empty($questionIds)) {
            return redirect()
                ->route('quiz.retake')
                ->withErrors(['invalid' => 'Unable to submit the retake. Please reload the page and try again.']);
        }

        QuizResult::where('user_id', $user->id)
            ->whereIn('question_id', $questionIds)
            ->delete();
    
        $rules = [];
        foreach ($questions as $question) {
            $rules['question_' . $question->id] = 'required|in:A,B,C,D';
        }

        $validated = $request->validate($rules, [
            'required' => 'Please answer every question before submitting the quiz.',
            'in' => 'Please answer every question before submitting the quiz.',
        ]);

        $results = [];
        $score = 0;

        foreach ($questions as $question) {
            $answerKey = 'question_' . $question->id;
            $selectedAnswer = $validated[$answerKey] ?? null;
            $isCorrect = $selectedAnswer === $question->correct_answer;

            if ($isCorrect) {
                $score++;
            }

            QuizResult::create([
                'user_id' => $user->id,
                'question_id' => $question->id,
                'selected_answer' => $selectedAnswer,
                'correct_answer' => $question->correct_answer,
                'is_correct' => $isCorrect,
                'is_retake' => true,
            ]);

            $results[] = [
                'question' => $question->question,
                'selected_answer' => $selectedAnswer,
                'correct_answer' => $question->correct_answer,
                'is_correct' => $isCorrect,
                'option_a' => $question->option_a,
                'option_b' => $question->option_b,
                'option_c' => $question->option_c,
                'option_d' => $question->option_d,
                'explanation' => $question->explanation,
            ];
        }

        return view('quiz-result', [
            'results' => $results,
            'score' => $score,
            'total' => $questions->count(),
        ]);
    }
}

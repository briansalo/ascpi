<?php

namespace App\Http\Controllers;

use App\Models\QuizResult;
use Illuminate\Support\Facades\Auth;

class QuizReviewController extends Controller
{
    public function index()
    {
        $user = Auth::user();
   
        $results = QuizResult::with('question')
            ->where('user_id', $user->id)

            ->get();
        dd($results);
        if ($results->isEmpty()) {
            return redirect()->route('quiz.index');
        }
    
        $reviewItems = $results->map(function ($result) {
            return [
                'question' => $result->question->question,
                'selected_answer' => $result->selected_answer,
                'correct_answer' => $result->correct_answer,
                'is_correct' => (bool) $result->is_correct,
                'options' => [
                    'A' => $result->question->option_a,
                    'B' => $result->question->option_b,
                    'C' => $result->question->option_c,
                    'D' => $result->question->option_d,
                ],
                'explanation' => $result->question->explanation,
            ];
        });

        return view('quiz-review', [
            'reviewItems' => $reviewItems,
            'score' => $reviewItems->where('is_correct', true)->count(),
            'total' => $reviewItems->count(),
        ]);
    }
}

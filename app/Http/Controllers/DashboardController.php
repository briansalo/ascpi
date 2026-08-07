<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $levelQuestionCount = Question::where('level', $user->level)->count();
        $levelAnsweredCount = $user->latestLevelResults()->count();
        $levelAccuracy = $user->levelAccuracy();
        $isJustLevelUp = false;

        if ($levelAnsweredCount == $levelQuestionCount && $levelAccuracy >= 80) {
            $levelUpFrom = $user->level;
            switch (strtolower($user->level)) {
                case 'easy':
                    $user->update(['level' => 'medium']);
                    break;
                case 'medium':
                    $user->update(['level' => 'hard']);
                    break;
                case 'hard':
                    $user->update(['level' => 'ascpi']);
                    break;
            }

            if ($levelUpFrom !== $user->level) {
                $isJustLevelUp = true;
                $levelUpTo = $user->level;
                session()->flash('justLeveledUp', true);
                session()->flash('levelUpFrom', ucfirst($levelUpFrom));
                session()->flash('levelUpTo', ucfirst($levelUpTo));
            }
        }


        $answeredQuestions = $user->levelResults($user->level)->count();
        $todayResults = $user->todayQuizResults()->whereHas('question', function ($query) use ($user) {
            $query->where('level', $user->level);
        });
        $totalItems = $todayResults->count();            
        $todayCorrect = $todayResults->where('is_correct', true)->count();
       
        $todayCount = $todayResults->count();

        // $scorePercentage = ($todayCorrect / $totalItems) * 100;
        // $motivationalMsg = $this->getMotivationalMsg($scorePercentage);
        // dd($motivationalMsg);
        $remainingQuestions = Question::where('level', $user->level)
            ->whereNotIn('id', $user->levelResults($user->level)->pluck('question_id'))
            ->count();
        $overallAccuracy = $answeredQuestions > 0 ? round(($user->levelResults($user->level)->where('is_correct', true)->count() / $answeredQuestions) * 100, 1) : 0;

        $subjectPerformance = Question::pluck('subject')
            ->unique()
            ->mapWithKeys(function ($subject) use ($user) {
                $results = $user->quizResults()

                    ->whereHas('question', function ($query) use ($subject, $user) {
                        $query->where('subject', $subject);
                    })
                    ->get()
                    ->groupBy('question_id')
                    ->map->last();

                $count = $results->count();
                $correct = $results->where('is_correct', true)->where('is_retake', false)->count();

                return [$subject => $count ? round($correct / $count * 100) : 0];
            });

        $requiresRetake = $levelQuestionCount > 0 && $levelAnsweredCount === $levelQuestionCount && $levelAccuracy < 80;
       
        $levelUpFrom = null;
        $levelUpTo = null;

        return view('dashboard', compact(
            'isJustLevelUp',
            'levelUpFrom',
            'levelUpTo',
            'user',
            'answeredQuestions',
            'todayCorrect',
            'todayCount',
            'remainingQuestions',
            'overallAccuracy',
            'levelAccuracy',
            'requiresRetake',
            'subjectPerformance'
        ));
    }


}

<?php

namespace App\Http\Controllers;
use App\Models\MotivationalMessage;

class InspirationController extends Controller
{
    public function index($score)
    {   
        if($score >= 0 && $score <= 10){
            $scoreLevel = 'low';
        } elseif ($score >= 11 && $score <= 20) {
            $scoreLevel = 'medium';
        } elseif ($score >= 21 && $score <= 30) {
            $scoreLevel = 'high';
        } else {
            $scoreLevel = 'high';
        }
        $inspirational = MotivationalMessage::where('is_displayed', false)
            ->where('score_level', $scoreLevel)
            ->inRandomOrder()->first();
        
        if ($inspirational) {
            $inspirational->update(['is_displayed' => true]);
        } else{
            MotivationalMessage::where('score_level', $scoreLevel)
                ->update(['is_displayed' => false]);
            $inspirational = MotivationalMessage::where('is_displayed', false)
                ->where('score_level', $scoreLevel)
                ->inRandomOrder()->first();
        }

        return view('inspiration', compact('inspirational'));
    }


}

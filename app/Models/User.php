<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\QuizResult;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'level',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function quizResults()
    {
        return $this->hasMany(QuizResult::class);
    }

    public function todayQuizResults()
    {
        return $this->quizResults()->whereDate('created_at', today());
    }

    public function hasTakenQuizToday(): bool
    {
        return $this->quizResults()
            ->whereDate('created_at', today())
            ->whereHas('question', function ($query) {
                $query->where('level', $this->level);
            })
            ->exists();
    }

    public function levelResults(string $level = null)
    {
        $level = $level ?? $this->level;

        return $this->quizResults()->whereHas('question', function ($query) use ($level) {
            $query->where('level', $level);
        });
    }

    public function latestLevelResults(string $level = null)
    {
        $level = $level ?? $this->level;

        return $this->levelResults($level)
            ->get()
            ->sortBy('created_at')
            ->groupBy('question_id')
            ->map(function ($group) {
                return $group->last();
            });
    }

    public function levelAccuracy(string $level = null): float
    {
        $results = $this->latestLevelResults($level);
        $count = $results->count();

        if ($count === 0) {
            return 0;
        }

        return round($results->where('is_correct', true)->count() / $count * 100, 1);
    }

    public function levelWrongQuestionIds(string $level = null): array
    {
        return $this->latestLevelResults($level)
            ->where('is_correct', false)
            ->pluck('question_id')
            ->unique()
            ->values()
            ->all();
    }

    public function nextLevel(): ?string
    {
        $levels = ['easy', 'medium', 'hard'];
        $currentIndex = array_search($this->level, $levels, true);

        if ($currentIndex === false || $currentIndex === count($levels) - 1) {
            return null;
        }

        return $levels[$currentIndex + 1];
    }
}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dashboard</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-800">
    <div class="max-w-7xl mx-auto p-6 lg:p-8">
        <header class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold">Dashboard</h1>
                <p class="text-sm text-slate-600">Welcome back, {{ $user->name }}. Keep learning with your daily quiz.</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="rounded-full bg-slate-100 px-3 py-2 text-sm text-slate-700">Overall accuracy: {{ $overallAccuracy }}%</span>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="rounded-full bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">Log out</button>
                </form>
            </div>
        </header>

        @if(session('error'))
            <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-700">
                {{ session('error') }}
            </div>
        @endif

        @if(session('success'))
            <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        @if(session('justLeveledUp'))
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4" role="dialog" aria-modal="true">
                <div class="w-full max-w-lg rounded-3xl bg-white p-6 shadow-2xl">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-indigo-600">Level Up!</p>
                            <h2 class="mt-4 text-3xl font-semibold text-slate-900">Congratulations!</h2>
                            <p class="mt-3 text-slate-600">You have advanced from {{ session('levelUpFrom') }} to {{ session('levelUpTo') }}. Keep going with your next set of questions now.</p>
                        </div>
                        <button onclick="this.closest('div[role=dialog]').remove()" class="rounded-full bg-slate-100 px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-200">Close</button>
                    </div>
                </div>
            </div>
        @endif

        @if($requiresRetake)
            <div class="mb-6 rounded-3xl border border-amber-200 bg-amber-50 p-6 text-sm text-slate-800 shadow-sm">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="font-semibold text-amber-900">Requirement not met</p>
                        <p class="mt-2 text-slate-700">You have completed all questions for your current level but scored below 80%. Retake the incorrect questions to move up.</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="rounded-full bg-amber-100 px-3 py-2 text-sm font-medium text-amber-900">Level accuracy: {{ $levelAccuracy }}%</span>
                        <a href="{{ route('quiz.retake') }}" class="rounded-full bg-amber-600 px-4 py-2 text-sm font-semibold text-white hover:bg-amber-700">Retake quiz</a>
                    </div>
                </div>
            </div>
        @elseif($user->hasTakenQuizToday())
            <div class="mb-6 rounded-3xl border border-green-200 bg-green-50 p-6 text-sm text-slate-800 shadow-sm">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="font-semibold text-green-900">Quiz completed for today</p>
                        <p class="mt-2 text-slate-700"> You've already completed today's quiz. Come back tomorrow for a new set of questions and continue your learning journey.</p>
                    </div>

                </div>
            </div>    
        @endif
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">

    <!-- Today's Quiz -->
    @if(!$requiresRetake)
        <div class="bg-white rounded-2xl border border-slate-100 shadow-md shadow-slate-200/40 p-6 hover:-translate-y-1 transition">

            <div class="flex justify-between items-start">

                <div>
                    <p class="text-sm font-medium text-blue-600">Today's Quiz</p>
                    <h2 class="mt-2 text-4xl font-bold text-slate-800">{{ $todayCount }}/5</h2>
                    <p class="text-sm text-slate-500 mt-1">{{ $user->hasTakenQuizToday() ? 'Completed' : 'Available' }}</p>
                </div>

                <div class="w-14 h-14 rounded-xl bg-blue-100 flex items-center justify-center">📋</div>

            </div>

            <div class="mt-5 h-2 rounded-full bg-slate-100 overflow-hidden">
                <div class="bg-indigo-500 h-full rounded-full" style="width: {{ $todayCount / 5 * 100 }}%"></div>
            </div>

            <a href="{{ $user->hasTakenQuizToday() ? route('quiz.review') : route('quiz.index') }}" class="mt-5 block text-center rounded-xl bg-gradient-to-r from-indigo-500 to-blue-500 text-white py-3 font-medium hover:opacity-90">
                {{ $user->hasTakenQuizToday() ? 'Review Results' : 'Take Today’s Quiz' }}
            </a>
        </div>
    @endif
    <!-- Accuracy -->

    <div class="bg-white rounded-2xl border border-slate-100 shadow-md shadow-slate-200/40 p-6 hover:-translate-y-1 transition">

        <div class="flex justify-between">

            <div>

                <p class="text-sm text-emerald-600 font-medium">Overall Accuracy</p>

                <h2 class="mt-2 text-4xl font-bold">{{ $overallAccuracy }}%</h2>

                <p class="text-slate-500 mt-1 text-sm">{{ $answeredQuestions }} questions answered</p>

            </div>

            <div class="w-16 h-16 rounded-full border-[8px] border-emerald-500 border-r-emerald-100 border-b-emerald-100"></div>

        </div>

    </div>

    <!-- Questions -->

    <div class="bg-white rounded-2xl border border-slate-100 shadow-md shadow-slate-200/40 p-6 hover:-translate-y-1 transition">

        <div class="flex justify-between">

            <div>

                <p class="text-sm font-medium text-amber-600">Questions Completed</p>

                <h2 class="mt-2 text-4xl font-bold">{{ $answeredQuestions }}</h2>

                <p class="text-slate-500 mt-1">Remaining: {{ $remainingQuestions }}</p>

            </div>

            <div class="w-14 h-14 rounded-xl bg-amber-100 flex items-center justify-center text-2xl">💡</div>

        </div>

    </div>

    <!-- Current Level -->

    <div class="bg-white rounded-2xl border border-slate-100 shadow-md shadow-slate-200/40 p-6 hover:-translate-y-1 transition">

        <div class="flex justify-between">

            <div>

                <p class="text-sm text-purple-600 font-medium">Current Level</p>

                <h2 class="mt-2 text-4xl font-bold">{{ ucfirst($user->level) }}</h2>

                <p class="text-slate-500">Your active question level</p>

            </div>

            <div class="w-14 h-14 rounded-full bg-purple-100 flex items-center justify-center text-3xl">🏅</div>

        </div>

    </div>

</div>
        <div class="mt-5">
            <div class="rounded-xl bg-white p-6 shadow-sm border">
                <h2 class="text-lg font-semibold">Subject Performance</h2>
                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                    @forelse($subjectPerformance as $subject => $percentage)
                        <div class="space-y-3">
                            <div class="flex items-center justify-between text-sm">
                                <div class="text-slate-700">{{ $subject }}</div>
                                <div class="text-slate-500">{{ $percentage }}%</div>
                            </div>
                            <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                <div class="h-2 rounded-full {{ $percentage >= 80 ? 'bg-emerald-400' : ($percentage >= 60 ? 'bg-amber-400' : 'bg-rose-400') }}" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2 rounded-2xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-600">
                            No subject performance data available yet.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</body>
</html>
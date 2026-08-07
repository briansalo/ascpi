<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Results</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-slate-100 text-slate-800">
    <div class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="rounded-3xl border border-slate-200 bg-white/90 p-8 shadow-xl shadow-indigo-100 backdrop-blur sm:p-10">
            <div class="mb-8">
                <span class="inline-flex items-center rounded-full bg-indigo-100 px-3 py-1 text-sm font-semibold text-indigo-700">
                    Quiz Results
                </span>
                <h1 class="mt-4 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">You scored {{ $score }} out of {{ $total }}</h1>
                <p class="mt-3 max-w-2xl text-base leading-7 text-slate-600">
                    Review each answer below to see what was correct, what was missed, and the explanation for each question.
                </p>
            </div>

            <div class="space-y-5">
                @foreach($results as $index => $result)
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                        <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                            <h3 class="text-lg font-semibold text-slate-900">{{ $index + 1 }}. {{ $result['question'] }}</h3>
                            @if($result['is_correct'])
                                <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-sm font-semibold text-emerald-700">Correct</span>
                            @else
                                <span class="inline-flex rounded-full bg-rose-100 px-3 py-1 text-sm font-semibold text-rose-700">Incorrect</span>
                            @endif
                        </div>

                        <div class="mt-4 grid gap-3">
                            @foreach(['A' => $result['option_a'], 'B' => $result['option_b'], 'C' => $result['option_c'], 'D' => $result['option_d']] as $letter => $option)
                                @php
                                    $isSelected = $result['selected_answer'] === $letter;
                                    $isCorrectAnswer = $result['correct_answer'] === $letter;
                                @endphp
                                <div class="rounded-xl border px-4 py-3 text-sm font-medium {{ $isCorrectAnswer ? 'border-emerald-200 bg-emerald-50 text-emerald-700' : ($isSelected ? 'border-rose-200 bg-rose-50 text-rose-700' : 'border-slate-200 bg-white text-slate-700') }}">
                                    <div class="flex items-center justify-between gap-3">
                                        <span>{{ $letter }}. {{ $option }}</span>
                                        @if($isSelected)
                                            <span class="text-xs font-semibold uppercase">Your choice</span>
                                        @elseif($isCorrectAnswer)
                                            <span class="text-xs font-semibold uppercase">Correct answer</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-4 rounded-xl border border-slate-200 bg-white p-4 text-sm text-slate-700">
                            <p class="font-semibold text-slate-900">Explanation</p>
                            <p class="mt-2 leading-6">{{ $result['explanation'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <p>.</p>   
                <a href="{{ url('/inspiration/' . $score) }}" class="inline-flex items-center justify-center rounded-full bg-indigo-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700">
                    Continue
                </a>
            </div>
        </div>
    </div>
</body>
</html>

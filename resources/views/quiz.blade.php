<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sample Quiz</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-slate-100 text-slate-800">
    <div class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="rounded-3xl border border-slate-200 bg-white/90 p-8 shadow-xl shadow-indigo-100 backdrop-blur sm:p-10">
            <div class="mb-8">
                <span class="inline-flex items-center rounded-full bg-indigo-100 px-3 py-1 text-sm font-semibold text-indigo-700">
                    Sample Quiz
                </span>
                <h1 class="mt-4 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">Test your knowledge</h1>
                <p class="mt-3 max-w-2xl text-base leading-7 text-slate-600">
                    Pick the best answer for each question. When you submit the form, you will see your score instantly.
                </p>
            </div>

            <form action="{{ !empty($isRetake) ? route('quiz.retake.submit') : route('quiz.submit') }}" method="POST" class="space-y-5">
                @csrf

                @if ($errors->any())
                    <div class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-700">
                        {{ $errors->first() }}
                    </div>
                @endif

                @foreach($questions as $question)
                    <input type="hidden" name="question_ids[]" value="{{ $question->id }}">
                @endforeach

                @forelse($questions as $index => $question)
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                        <h3 class="mb-4 text-lg font-semibold text-slate-900">{{ $index + 1 }}. {{ $question->question }}</h3>
                        <div class="grid gap-3">
                            <label class="flex items-center gap-3 rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-medium text-slate-700 shadow-sm transition hover:border-indigo-400 hover:bg-indigo-50">
                                <input type="radio" name="question_{{ $question->id }}" value="A" {{ old('question_' . $question->id) === 'A' ? 'checked' : '' }} class="h-4 w-4 border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                {{ $question->option_a }}
                            </label>
                            <label class="flex items-center gap-3 rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-medium text-slate-700 shadow-sm transition hover:border-indigo-400 hover:bg-indigo-50">
                                <input type="radio" name="question_{{ $question->id }}" value="B" {{ old('question_' . $question->id) === 'B' ? 'checked' : '' }} class="h-4 w-4 border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                {{ $question->option_b }}
                            </label>
                            <label class="flex items-center gap-3 rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-medium text-slate-700 shadow-sm transition hover:border-indigo-400 hover:bg-indigo-50">
                                <input type="radio" name="question_{{ $question->id }}" value="C" {{ old('question_' . $question->id) === 'C' ? 'checked' : '' }} class="h-4 w-4 border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                {{ $question->option_c }}
                            </label>
                            <label class="flex items-center gap-3 rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-medium text-slate-700 shadow-sm transition hover:border-indigo-400 hover:bg-indigo-50">
                                <input type="radio" name="question_{{ $question->id }}" value="D" {{ old('question_' . $question->id) === 'D' ? 'checked' : '' }} class="h-4 w-4 border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                {{ $question->option_d }}
                            </label>
                        </div>
                    </div>
                @empty
                    <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-6 text-center text-sm text-slate-500">
                        No questions are available yet.
                    </div>
                @endforelse

                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-sm text-slate-500">Complete all questions to see your score.</p>
                    <button type="submit" class="inline-flex items-center justify-center rounded-full bg-indigo-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Submit Quiz
                    </button>
                </div>

                <div id="result" class="hidden rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700"></div>
            </form>
        </div>
    </div>


</body>
</html>

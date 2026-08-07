<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Inspiration</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
        <style>
            body {
                margin: 0;
                font-family: Figtree, sans-serif;
                background: linear-gradient(135deg, #fef3c7, #f5f3ff);
                color: #111827;
            }
            .container {
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 2rem;
            }
            .card {
                max-width: 720px;
                background: rgba(255,255,255,0.9);
                border-radius: 1.5rem;
                box-shadow: 0 20px 45px rgba(15, 23, 42, 0.12);
                padding: 2.5rem;
                text-align: center;
            }
            h1 {
                font-size: 2rem;
                margin-bottom: 1rem;
            }
            p {
                font-size: 1.125rem;
                line-height: 1.7;
                margin-bottom: 1.5rem;
            }
            a {
                display: inline-block;
                text-decoration: none;
                color: #ffffff;
                background: #7c3aed;
                padding: 0.75rem 1.25rem;
                border-radius: 999px;
                font-weight: 600;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="card">
                <h1>{{ $inspirational['message'] }}</h1>
                <p>
                </p>
                @if($inspirational['image_url'])
                    <img src="{{ asset($inspirational['image_url']) }}" alt="Inspirational Image" style="max-width: 100%; border-radius: 1rem; margin-bottom: 1.5rem;">
                @endif
                @if($inspirational['video_url'])
                    <video width="100%" height="auto" controls>
                        <source src="{{ $inspirational['video_url'] }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                @endif
                <div>
                    <a href="{{ url('/') }}">Back to home</a>
                </div>
            </div>
        </div>
    </body>
</html>

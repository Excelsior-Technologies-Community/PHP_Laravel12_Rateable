<!DOCTYPE html>
<html>

<head>

    <title>Laravel Rateable Demo</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial;
            background: #f4f7fb;
        }

        .header {
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .header h1 {
            font-size: 38px;
        }

        .container {
            width: 90%;
            max-width: 1100px;
            margin: 40px auto;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .create-btn {
            background: #3498db;
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: 0.3s;
        }

        .create-btn:hover {
            background: #217dbb;
        }

        .top-rated-box {
            background: white;
            padding: 25px;
            border-radius: 14px;
            margin-bottom: 40px;
            box-shadow: 0 5px 18px rgba(0,0,0,0.08);
        }

        .top-rated-title {
            font-size: 26px;
            margin-bottom: 20px;
            color: #2c3e50;
        }

        .top-rated-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px,1fr));
            gap: 20px;
        }

        .top-card {
            background: linear-gradient(135deg, #fff8e1, #fff3cd);
            padding: 20px;
            border-radius: 12px;
            transition: 0.3s;
            border: 1px solid #ffe082;
        }

        .top-card:hover {
            transform: translateY(-5px);
        }

        .top-card h3 {
            margin-bottom: 12px;
            color: #2c3e50;
        }

        .badge {
            display: inline-block;
            background: #27ae60;
            color: white;
            padding: 6px 12px;
            border-radius: 30px;
            font-size: 14px;
            margin-bottom: 12px;
        }

        .post-card {
            background: white;
            padding: 25px;
            margin-bottom: 25px;
            border-radius: 14px;
            box-shadow: 0 5px 18px rgba(0,0,0,0.08);
        }

        .post-title {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
        }

        .post-content {
            color: #555;
            margin-top: 10px;
            line-height: 1.7;
        }

        .rating-box {
            margin-top: 25px;
            background: #f8f9fa;
            padding: 20px;
            border-radius: 12px;
        }

        .average {
            color: #e67e22;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .total {
            color: #666;
            margin-bottom: 18px;
        }

        .star-rating {
            direction: rtl;
            display: inline-flex;
        }

        .star-rating input {
            display: none;
        }

        .star-rating label {
            font-size: 32px;
            color: #ccc;
            cursor: pointer;
            transition: 0.2s;
        }

        .star-rating label:hover,
        .star-rating label:hover~label {
            color: #f1c40f;
        }

        .star-rating input:checked~label {
            color: #f1c40f;
        }

        .submit-btn {
            margin-left: 15px;
            padding: 10px 18px;
            border: none;
            background: #27ae60;
            color: white;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            transition: 0.3s;
        }

        .submit-btn:hover {
            background: #1f8b4d;
        }

        .rating-breakdown {
            margin-top: 25px;
        }

        .rating-breakdown h4 {
            margin-bottom: 15px;
            color: #2c3e50;
        }

        .bar-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 12px;
        }

        .bar-label {
            width: 50px;
            font-size: 14px;
        }

        .progress {
            flex: 1;
            height: 12px;
            background: #ddd;
            border-radius: 30px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #f1c40f, #f39c12);
            border-radius: 30px;
        }

        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 14px;
            border-radius: 8px;
            margin-bottom: 25px;
        }

        @media(max-width:768px) {
            .header h1 {
                font-size: 28px;
            }

            .post-title {
                font-size: 20px;
            }

            .star-rating label {
                font-size: 26px;
            }
        }
    </style>

</head>

<body>

    <div class="header">
        <h1>Laravel Rateable ⭐ Premium Dashboard</h1>
    </div>

    <div class="container">

        @if(session('success'))
            <div class="success-message">
                {{ session('success') }}
            </div>
        @endif

        <div class="top-bar">
            <h2>All Posts</h2>

            <a href="/create" class="create-btn">
                + Create New Post
            </a>
        </div>

        <!-- TOP RATED POSTS -->

        <div class="top-rated-box">

            <div class="top-rated-title">
                🏆 Top Rated Posts
            </div>

            <div class="top-rated-grid">

                @foreach($topPosts as $top)

                    <div class="top-card">

                        <h3>{{ $top->title }}</h3>

                        <div class="badge">
                            ⭐ {{ number_format($top->averageRating, 1) }}
                        </div>

                        <p>
                            {{ \Illuminate\Support\Str::limit($top->content, 90) }}
                        </p>

                    </div>

                @endforeach

            </div>

        </div>

        <!-- POSTS -->

        @foreach($posts as $post)

            <div class="post-card">

                <div class="post-title">
                    {{ $post->title }}
                </div>

                <div class="post-content">
                    {{ $post->content }}
                </div>

                <div class="rating-box">

                    <div class="average">
                        ⭐ Average Rating:
                        {{ number_format($post->averageRating, 1) }}/5
                    </div>

                    <div class="total">
                        Total Ratings:
                        {{ $post->ratings->count() }}
                    </div>

                    <form method="POST" action="/rate/{{ $post->id }}">
                        @csrf

                        <div class="star-rating">

                            <input type="radio" name="rating" value="5" id="5-{{$post->id}}">
                            <label for="5-{{$post->id}}">★</label>

                            <input type="radio" name="rating" value="4" id="4-{{$post->id}}">
                            <label for="4-{{$post->id}}">★</label>

                            <input type="radio" name="rating" value="3" id="3-{{$post->id}}">
                            <label for="3-{{$post->id}}">★</label>

                            <input type="radio" name="rating" value="2" id="2-{{$post->id}}">
                            <label for="2-{{$post->id}}">★</label>

                            <input type="radio" name="rating" value="1" id="1-{{$post->id}}">
                            <label for="1-{{$post->id}}">★</label>

                        </div>

                        <button class="submit-btn">
                            Submit Rating
                        </button>

                    </form>

                    <!-- RATING BREAKDOWN -->

                    @php
                        $totalRatings = $post->ratings->count();
                    @endphp

                    <div class="rating-breakdown">

                        <h4>⭐ Rating Analytics</h4>

                        @for($i = 5; $i >= 1; $i--)

                            @php
                                $count = $post->ratings
                                    ->where('rating', $i)
                                    ->count();

                                $percentage = $totalRatings > 0
                                    ? ($count / $totalRatings) * 100
                                    : 0;
                            @endphp

                            <div class="bar-row">

                                <div class="bar-label">
                                    {{ $i }} ⭐
                                </div>

                                <div class="progress">

                                    <div
                                        class="progress-fill"
                                        style="width: {{ $percentage }}%">
                                    </div>

                                </div>

                                <div>
                                    {{ $count }}
                                </div>

                            </div>

                        @endfor

                    </div>

                </div>

            </div>

        @endforeach

    </div>

</body>

</html>
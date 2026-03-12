<!DOCTYPE html>
<html>

<head>

    <title>Laravel Rateable Demo</title>

    <style>
        body {
            font-family: Arial;
            background: #f4f6f9;
            margin: 0;
            padding: 0;
        }

        .header {
            background: #2c3e50;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .container {
            width: 900px;
            margin: 40px auto;
        }

        .create-btn {
            background: #3498db;
            color: white;
            padding: 10px 18px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
        }

        .create-btn:hover {
            background: #2980b9;
        }

        .post-card {
            background: white;
            padding: 25px;
            margin-top: 20px;
            border-radius: 10px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08);
        }

        .post-title {
            font-size: 22px;
            font-weight: bold;
        }

        .post-content {
            color: #555;
            margin-top: 8px;
        }

        .rating-box {
            margin-top: 15px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .average {
            color: #e67e22;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .star-rating {
            direction: rtl;
            display: inline-flex;
        }

        .star-rating input {
            display: none;
        }

        .star-rating label {
            font-size: 28px;
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
            margin-left: 10px;
            padding: 6px 14px;
            border: none;
            background: #27ae60;
            color: white;
            border-radius: 6px;
            cursor: pointer;
        }

        .submit-btn:hover {
            background: #219150;
        }

        .total {
            font-size: 13px;
            color: #888;
            margin-top: 5px;
        }
    </style>

</head>

<body>

    <div class="header">
        <h1>Laravel Rateable ⭐ Demo</h1>
    </div>

    <div class="container">

        <a href="/create" class="create-btn">+ Create New Post</a>

        @foreach($posts as $post)

            <div class="post-card">

                <div class="post-title">{{ $post->title }}</div>

                <div class="post-content">{{ $post->content }}</div>

                <div class="rating-box">

                    <div class="average">
                        Average Rating : {{ number_format($post->averageRating, 1) }}
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

                        <button class="submit-btn">Submit</button>

                    </form>

                    <div class="total">
                        Total Ratings : {{ $post->ratings->count() }}
                    </div>

                </div>

            </div>

        @endforeach

    </div>

</body>

</html>
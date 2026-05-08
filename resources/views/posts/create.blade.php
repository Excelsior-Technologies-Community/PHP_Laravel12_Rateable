<!DOCTYPE html>
<html>

<head>

    <title>Create Post</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background: linear-gradient(135deg, #eef2f7, #d9e4f5);
            margin: 0;
            padding: 0;
        }

        .container {
            width: 90%;
            max-width: 550px;
            margin: 70px auto;
            background: white;
            padding: 35px;
            border-radius: 14px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #2c3e50;
        }

        label {
            font-weight: bold;
            color: #2c3e50;
        }

        input,
        textarea {
            width: 100%;
            padding: 14px;
            margin-top: 10px;
            margin-bottom: 22px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 15px;
        }

        textarea {
            height: 150px;
            resize: none;
        }

        button {
            width: 100%;
            padding: 14px;
            background: #3498db;
            border: none;
            color: white;
            font-size: 17px;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #217dbb;
        }

        .back {
            display: block;
            text-align: center;
            margin-top: 20px;
            text-decoration: none;
            color: #555;
            font-weight: bold;
        }

        .error {
            color: red;
            margin-top: -15px;
            margin-bottom: 15px;
            font-size: 14px;
        }
    </style>

</head>

<body>

    <div class="container">

        <h2>Create New Post</h2>

        <form method="POST" action="/store">

            @csrf

            <label>Post Title</label>

            <input
                type="text"
                name="title"
                placeholder="Enter post title"
                value="{{ old('title') }}">

            @error('title')
                <div class="error">{{ $message }}</div>
            @enderror

            <label>Post Content</label>

            <textarea
                name="content"
                placeholder="Write your content here...">{{ old('content') }}</textarea>

            @error('content')
                <div class="error">{{ $message }}</div>
            @enderror

            <button type="submit">
                Save Post
            </button>

        </form>

        <a href="/" class="back">
            ← Back to Posts
        </a>

    </div>

</body>

</html>
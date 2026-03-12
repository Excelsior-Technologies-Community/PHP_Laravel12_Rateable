# PHP_Laravel12_Rateable


## Project Description

PHP_Laravel12_Rateable is a simple Laravel 12 web application that demonstrates how to implement a post rating system in a web application.

The system allows users to create posts and rate them using a 5-star rating system. Each rating submitted by users is stored in the database and the system automatically calculates the average rating and total number of ratings for each post.

This project helps developers understand how to build a rating feature using Laravel models, migrations, controllers, and Blade views. It also demonstrates how relational data can be used to manage ratings associated with specific posts.

The application uses a clean interface where users can easily create posts, view them, and submit ratings.



## Project Features

- Create new posts with title and content

- Display all created posts on the main page

- Rate posts using a star rating system (1–5 stars)

- Automatically calculate and display average rating

- Display the total number of ratings per post

- Simple and responsive user interface using CSS

- Organized Laravel project structure following MVC pattern



## Technologies Used

- PHP

- Laravel 12 Framework

- MySQL Database

- Blade Template Engine

- HTML & CSS

- Composer Dependency Manager



---



## Installation Steps


---


## STEP 1: Create Laravel 12 Project

### Open terminal / CMD and run:

```
composer create-project laravel/laravel PHP_Laravel12_Rateable "12.*"

```

### Go inside project:

```
cd PHP_Laravel12_Rateable

```

#### Explanation:

This command installs a fresh Laravel 12 application using Composer and creates a new project folder named PHP_Laravel12_Rateable.

The cd command moves into the project directory so you can start working with Laravel commands.




## STEP 2: Database Setup

### Update database details:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel12_Rateable
DB_USERNAME=root
DB_PASSWORD=

```

### Create database in MySQL/phpMyAdmin:

```
Database name: laravel12_Rateable

```

### Then Run:

```
php artisan migrate

```


#### Explanation:

In this step we configure the database connection in the .env file so Laravel can communicate with MySQL.

Running php artisan migrate creates Laravel’s default database tables like users, cache, and jobs.





## STEP 3: Install Rateable Package

### Install package:

```
composer require willvincent/laravel-rateable

```

#### Explanation:

This command installs the laravel-rateable package which allows models to receive user ratings.

The package provides helper methods to calculate average ratings, total ratings, and rating percentages.




## STEP 4: Publish Migration

### Create Ratings Migration

```
php artisan make:migration create_ratings_table

```

### Then update the migration file:

```
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('ratings', function (Blueprint $table) {

            $table->id();

            $table->morphs('rateable');

            $table->integer('rating');

            $table->unsignedBigInteger('user_id')->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }

};

```


### Now run migration:

```
php artisan migrate

```

### Now database table created:

```
ratings

```


#### Explanation:

The php artisan rateable:migration command generates the ratings table migration file required by the Rateable package.

Running php artisan migrate creates the ratings table in the database.






## STEP 5: Create Model and Migration

### Create Post model

```
php artisan make:model Post -m

```

### This creates

```
app/Models/Post.php

database/migrations/create_posts_table.php

```

#### Explanation:

Explanation

This command creates the Post model and a migration file to define the database structure for posts.

The model manages post data while the migration defines the posts table in the database.





## STEP 6: Migration Setup

### Open migration: database/migrations/create_posts_table.php

```

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};

```

### Then Run:

```
php artisan migrate

```

#### Explanation:

In this step we define the structure of the posts table including title and content columns.

Running the migration creates the posts table inside the database.




## STEP 7: Add Rateable Trait

### Open: app/Models/Post.php

```
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use willvincent\Rateable\Rateable;

class Post extends Model
{
    use Rateable;

    protected $fillable = [
        'title',
        'content'
    ];
}


```

#### Explanation:

The Rateable trait is added to the Post model so posts can receive ratings.

This trait automatically provides methods like averageRating and ratings relationship.






## STEP 8: Create Controller

### Run:

```

php artisan make:controller PostController



```

### Open: app/Http/Controllers/PostController.php

```
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use willvincent\Rateable\Rating;

class PostController extends Controller
{

    public function index()
    {
        $posts = Post::all();
        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        Post::create([
            'title' => $request->title,
            'content' => $request->content
        ]);

        return redirect('/');
    }

    public function rate(Request $request, $id)
    {
        $post = Post::find($id);

        $rating = new Rating();
        $rating->rating = $request->rating;
        $rating->user_id = 1;

        $post->ratings()->save($rating);

        return back();
    }

}


```

#### Explanation:

The PostController handles the application logic such as displaying posts, creating posts, and saving ratings.

Controllers help keep the application organized by separating logic from views.





## STEP 9: Routes

### Open: routes/web.php

```
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

Route::get('/', [PostController::class,'index']);

Route::get('/create',[PostController::class,'create']);

Route::post('/store',[PostController::class,'store']);

Route::post('/rate/{id}',[PostController::class,'rate']);

```

#### Explanation:

Routes define the URLs of the application and connect them to controller methods.

Each route handles actions like showing posts, creating posts, storing posts, and submitting ratings.





## STEP 10: Create Views

### Create folder

```

resources/views/posts



```

### resources/views/posts/index.blade.php

```
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

```


### resources/views/posts/create.blade.php

```

<!DOCTYPE html>
<html>

<head>
    <title>Create Post</title>

    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 500px;
            margin: 80px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
        }

        label {
            font-weight: bold;
        }

        input,
        textarea {
            width: 100%;
            padding: 10px;
            margin-top: 8px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
        }

        textarea {
            height: 120px;
            resize: none;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #4CAF50;
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
        }

        button:hover {
            background: #43a047;
        }

        .back {
            display: block;
            text-align: center;
            margin-top: 15px;
            text-decoration: none;
            color: #555;
        }
    </style>
</head>

<body>

    <div class="container">

        <h2>Create Post</h2>

        <form method="POST" action="/store">
            @csrf

            <label>Title</label>
            <input type="text" name="title" required>

            <label>Content</label>
            <textarea name="content" required></textarea>

            <button type="submit">Save Post</button>

        </form>

        <a href="/" class="back">← Back to Posts</a>

    </div>

</body>

</html>

```

#### Explanation:

Views create the user interface of the application using Laravel Blade templates.

The index page displays posts and ratings, while the create page allows users to add new posts.





## STEP 11: Test It

### Start Laravel dev server:

```
php artisan serve

```

### Open in browser:

```
http://127.0.0.1:8000

```

#### Explanation:

The php artisan serve command starts Laravel’s local development server.

Opening the provided URL in the browser allows you to test creating posts and submitting ratings.




## Expected Output:

### Home Page:


<img width="1919" height="764" alt="Screenshot 2026-03-12 155922" src="https://github.com/user-attachments/assets/8f1c9fcb-c202-441e-a05f-9c0442e23bb4" />


### Create Post Page:


<img width="1918" height="909" alt="Screenshot 2026-03-12 155944" src="https://github.com/user-attachments/assets/04e93944-58b4-49cf-8fc7-8f580324717b" />


### Display Posts and Ratings:


<img width="1919" height="859" alt="Screenshot 2026-03-12 155953" src="https://github.com/user-attachments/assets/b29cd9e7-f998-483f-a02d-fa337b2ee7c3" />


### Select Star Rating:


<img width="1919" height="954" alt="Screenshot 2026-03-12 160002" src="https://github.com/user-attachments/assets/75671037-edcc-452d-8024-a45ec96bc78b" />


### Submit Rating for Post:


<img width="1917" height="865" alt="Screenshot 2026-03-12 160010" src="https://github.com/user-attachments/assets/b897576e-a277-4ca9-900e-fca09d702988" />




---


# Project Folder Structure:

```
PHP_Laravel12_Rateable
│
├── app
│   ├── Http
│   │   └── Controllers
│   │        PostController.php
│   │
│   ├── Models
│   │      Post.php
│   │
│   └── Providers
│
├── bootstrap
│   └── app.php
│
├── config
│   ├── app.php
│   ├── database.php
│   └── services.php
│
├── database
│   ├── factories
│   ├── migrations
│   │     create_posts_table.php
│   │     create_ratings_table.php
│   │
│   └── seeders
│
├── public
│   └── index.php
│
├── resources
│   ├── css
│   ├── js
│   │
│   └── views
│        └── posts
│             create.blade.php
│             index.blade.php
│
├── routes
│   ├── web.php
│   └── console.php
│
├── storage
│
├── tests
│
├── vendor
│
├── .env
├── .env.example
├── .gitignore
├── artisan
├── composer.json
├── composer.lock
├── package.json
├── phpunit.xml
└── README.md

```


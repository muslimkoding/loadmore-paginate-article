<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Buat Loadmore pada halaman artikel
Untuk membuat fitur "load more" artikel di Laravel dengan Bootstrap 5, Anda bisa mengikuti langkah-langkah berikut. Ini melibatkan backend di Laravel untuk menangani permintaan AJAX dan frontend di Bootstrap 5 untuk menampilkan artikel dan tombol "Load More".

# Langkah 1: Buat Model dan Migration
Pertama, pastikan Anda memiliki model dan tabel untuk artikel.
```
php artisan make:model Article -m
```
Edit migration file di `database/migrations/xxxx_xx_xx_create_articles_table.php`:

```
public function up()
{
    Schema::create('articles', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->text('body');
        $table->timestamps();
    });
}
```
Jalankan migrasi untuk membuat tabel:
```
php artisan migrate
```

# Langkah 2: Seeder untuk Data Dummy
Buat seeder untuk mengisi beberapa data artikel:
```
php artisan make:seeder ArticlesTableSeeder
```

Edit seeder di `database/seeders/ArticlesTableSeeder.php`:
```
use App\Models\Article;
use Illuminate\Database\Seeder;

class ArticlesTableSeeder extends Seeder
{
    public function run()
    {
        Article::factory()->count(50)->create();
    }
}
```

Setelah Data Seeder sudah siap, Berikut adalah langkah-langkah untuk membuat factory dan mengisinya dengan data:
```
php artisan make:factory ArticleFactory --model=Article
```

# Langkah 3: Mengisi Factory dengan Data
Edit file `database/factories/ArticleFactory.php` yang baru saja dibuat:
```
<?php

namespace Database\Factories;

use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArticleFactory extends Factory
{
    protected $model = Article::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'body' => $this->faker->paragraph,
        ];
    }
}
```
Setelah `ArticelTableSeeder` dan `ArticleFactory` sudah siap
Jalankan seeder:
```
php artisan db:seed --class=ArticlesTableSeeder
```

# Langkah 4: Route dan Controller
Buat controller untuk menangani permintaan artikel:
```
php artisan make:controller ArticleController
```

Edit controller di `app/Http/Controllers/ArticleController.php`:
```
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;

class ArticleController extends Controller
{
    public function index()
    {
        return view('articles.index');
    }

    public function loadMore(Request $request)
    {
        if ($request->ajax()) {
            $articles = Article::latest()->skip($request->skip)->take(5)->get();
            return response()->json($articles);
        }
    }
}
```

Tambahkan route di `routes/web.php`:
```
use App\Http\Controllers\ArticleController;

Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
Route::post('/articles/load-more', [ArticleController::class, 'loadMore'])->name('articles.loadMore');
```

# Langkah 5: View dan JavaScript
Buat view untuk menampilkan artikel dan tombol "Load More":

Edit file `resources/views/articles/index.blade.php`:
```
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Articles</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="container mt-5">
    <h1>Articles</h1>
    <div id="article-container">
        <!-- Articles will be loaded here -->
    </div>
    <button id="load-more" class="btn btn-primary mt-4">Load More</button>
</div>

<script>
$(document).ready(function() {
    var skip = 0;
    loadArticles(skip);

    $('#load-more').click(function() {
        skip += 5;
        loadArticles(skip);
    });

    function loadArticles(skip) {
        $.ajax({
            url: '{{ route("articles.loadMore") }}',
            type: 'POST',
            data: {
                skip: skip,
                _token: '{{ csrf_token() }}'
            },
            success: function(data) {
                if (data.length == 0) {
                    $('#load-more').hide();
                }
                $.each(data, function(index, article) {
                    $('#article-container').append('<div class="card mt-3"><div class="card-body"><h5 class="card-title">' + article.title + '</h5><p class="card-text">' + article.body + '</p></div></div>');
                });
            }
        });
    }
});
</script>
</body>
</html>
```

# Langkah 5: Test
Jalankan server Laravel:
```
php artisan serve
```

Buka browser dan akses `http://127.0.0.1:8000/articles`. Anda akan melihat artikel dimuat dan tombol "Load More" di bawahnya untuk memuat lebih banyak artikel.

Dengan mengikuti langkah-langkah di atas, Anda akan memiliki fitur "load more" artikel yang bekerja dengan Laravel di backend dan Bootstrap 5 di frontend.

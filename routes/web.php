<?php

use App\Http\Controllers\PostDashboardController;
use App\Models\Post;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;


Route::get('/', function () {
    return view('home', ['title' => 'Home Page']);
});

Route::get('/posts', function () {
  $filters = request(['search', 'category', 'author']);

  // --- DEBUG POINT 1: Periksa input yang diterima dari URL ---

  $posts = App\Models\Post::filter($filters)->latest()->paginate(5)->withQueryString();

  // --- DEBUG POINT 3: Periksa data hasil akhir setelah semua filter ---

  return view('posts', ['title' => 'Blog', 'posts' => $posts]);
});

Route::get('/posts/{post:slug}', function (Post $post) {
  return view('post', ['title' => 'Single Post', 'post' => $post]);
});

Route::get('/about', function () {
    return view('about', ['title' => 'About']);
});

Route::get('/contact', function () {
    return view('contact', ['title' => 'Contact Us']);
});
// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::get('/dashboard', [PostDashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
// Route::post('/dashboard', [PostDashboardController::class, 'store'])->middleware(['auth', 'verified']);
// Route::get('/dashboard/create', [PostDashboardController::class, 'create'])->middleware(['auth', 'verified']);
// Route::get('/dashboard/{post:slug}', [PostDashboardController::class, 'show'])->middleware(['auth', 'verified']);

Route::middleware(['auth', 'verified'])->group(function () {
		Route::get('/dashboard', [PostDashboardController::class, 'index'])->name('dashboard');
		Route::post('/dashboard', [PostDashboardController::class, 'store']);
		Route::get('/dashboard/create', [PostDashboardController::class, 'create']);
		Route::delete('/dashboard/{post:slug}', [PostDashboardController::class, 'destroy']);
		Route::get('/dashboard/{post:slug}/edit', [PostDashboardController::class, 'edit']);
		Route::patch('/dashboard/{post:slug}', [PostDashboardController::class, 'update']);
		Route::get('/dashboard/{post:slug}', [PostDashboardController::class, 'show']);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
		Route::post('/upload', [ProfileController::class, 'upload']);
});

require __DIR__.'/auth.php';

<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\HomepageController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


// Route::get('dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::get('homepage', [HomepageController::class, 'index'])->name('homepage')->middleware('auth');

route::resource('post', PostController::class)->middleware('auth');

Route::get('/comments/create/{post}', [CommentController::class, 'create'])->name('comment.create');
Route::post('/comments/create/', [CommentController::class, 'store'])->name('comment.store');
Route::get('/comments/edit/{comment}', [CommentController::class, 'edit'])->name('comment.edit');
Route::patch('/comments/{comment}', [CommentController::class, 'update'])->name('comment.update');
Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comment.destroy');


Route::post('post/like_dislike', [PostController::class, 'ajaxLike'])->name('likedislike')->middleware('auth');

Broadcast::routes();


require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';

<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;

Route::get('/', function () {
    return view('welcome');
});

// Forum display routes
Route::get('/forum', [ForumController::class, 'index'])->name('forum.index');
Route::get('/forum/following', [ForumController::class, 'following'])->name('forum.following');
Route::get('/forum/mine', [ForumController::class, 'mine'])->name('forum.mine');

Route::post('/forum/store', [ForumController::class, 'store'])->name('forum.store');
Route::post('/forum/report', [ForumController::class, 'report'])->name('forum.report.submit');

Route::get('/forum/{id}', [ForumController::class, 'show'])->name('forum.show');
Route::get('/forum/tags/{tag_name}', [ForumController::class, 'postsByTag'])->name('forum.tag');
Route::get('/forum/{id}/edit', [ForumController::class, 'edit'])->name('forum.edit');
Route::put('/forum/{id}/update', [ForumController::class, 'update'])->name('forum.update');


Route::post('/forum/{id}/store', [CommentController::class, 'store'])->name('comments.store');
Route::post('/forum/{id}/edit', [ForumController::class, 'edit'])->name('forum.edit');
Route::post('/forum/{id}/like', [LikeController::class, 'likePost'])->name('like.post');
Route::post('/comments/{id}/like', [CommentController::class, 'like'])->name('comment.like');
Route::delete('/forum/{id}/delete', [ForumController::class, 'destroy'])->name('forum.delete');

Route::post('/follow/{user}', [UserController::class, 'follow'])->name('user.follow');

use App\Http\Controllers\ReportController;

Route::get('/top-posts', [ForumController::class, 'generateTopPostsXML']);
Route::get('/most-used-tags', [ForumController::class, 'generateMostUsedTagsXML']);
Route::get('/reports', [ForumController::class, 'reportPage']);

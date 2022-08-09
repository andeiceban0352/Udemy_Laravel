<?php

use Illuminate\Support\Facades\Route;
use App\Models\Post;
use App\Models\User;
use App\Http\Controllers\PostsController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/contact/{id}', function($id) {
//     return "The id is ".$id;
// });

// Route::get('admin/posts/example', array('as' => 'admin.home', function(){
//     $url = route('admin.home');

//     return  "this is  the url".$url;
// }));

// Route::get('/post', [PostsController::class, 'index']);

// Route::resource('posts', PostsController::class);

// Route::get('/contact', [PostsController::class, 'contact']);

// Route::get('post/{id}',[PostsController::class, 'show_post']);


// DATABASE Raw SQL Queries

Route::get('/insert',function() {
    DB::insert('insert into posts(title, content) values(?, ?)', ['PHP with Laravel', 'The second parameter']);
});

Route::get('/read',function() {
    $results = DB::select('select * from posts where id = 2', [1]);

    return var_dump($results);
});

Route::get('/update', function(){
    $updated = DB::update('update posts set title="Update title" where id=2', [1]);
    return $updated;
});

Route::get('/delete', function(){
    $deleted = DB::delete('delete from posts where  id=2', [1]);
    return $deleted;
});

// ELOQUENT 

Route::get('/all',function() {

    $posts = Post::all();

    foreach($posts as $post){
        return $post->title;
    }

});


Route::get('/find',function() {

    $post = Post::find(4);

    return $post->title;
});

Route::get('/findwhere',function() {

    $posts = Post::where('id',3)->orderBy('id', 'desc')->take(1)->get();

    return $posts;
});

Route::get('/findmore',function() {

    $posts = Post::findOrFail(2);

    return $posts;
});


// Eloquent data inserts
////////////////////////

Route::get('/basicinsert', function() {
    // $post = Post::find(4);
    $post = new Post;

    $post->title = 'ererererer';

    $post->content = 'cocococoocococ';
    $post->save();
});

Route::get('/create', function() {
    Post::create(['title'=>'the create', 'content'=>'wwwww']);
});

Route::get('/update_db',function() {
    Post::where('id',3)->update(['title'=>'New Title', 'content'=>'The new content']);
});

Route::get('/delete_db',function() {
    $post = Post::find(5);
    $post->delete();
    // or
    // Post::destroy(3);
    // or
    // Post::destroy([4,5]);
});


Route::get('/softdelete', function() {
    Post::find(4)->delete();
});

Route::get('/softdeletes', function() {
    $post = Post::onlyTrashed()->where('is_admin',0)->get();
    return $post;
});

// restore the deleted data
Route::get('/restore', function() {
    Post::withTrashed()->where('is_admin', 0)->restore();
});

//delete permanently
Route::get('/force_delete', function() {
    Post::onlyTrashed()->where('is_admin', 0)->forceDelete();
});

// Eloquent Relationships

Route::get('/user/{id}/post', function($id) {
    return User::find($id)->post->title;
});
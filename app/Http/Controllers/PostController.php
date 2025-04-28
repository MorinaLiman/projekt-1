<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use Illuminate\Support\Facades\DB;
use Exception;

class PostController extends Controller
{
    public function deletePost(Post $post)
    {
        if (auth()->user()->id === $post->user_id) {
            $post->delete();
        }
        return redirect('/');
    }

    public function actuallyUpdatePost(Post $post, UpdatePostRequest $request)
    {
        if (auth()->user()->id !== $post->user_id) {
            return redirect('/');
        }

        try {
            $post->update([
                'title' => $request->title,
                'body'  => $request->body,
            ]);
            return redirect('/');
        } catch (Exception $e) {
            return redirect('/')->with('error', 'Failed to update post.');
        }
    }

    public function showEditScreen(Post $post)
    {
        if (auth()->user()->id !== $post->user_id) {
            return redirect('/');
        }

        return view('edit-post', ['post' => $post]);
    }

    public function createPost(StorePostRequest $request)
    {
        try {
            Post::create([
                'title' => $request->title,
                'body'  => $request->body,
                'user_id' => auth()->id(),
            ]);
            return redirect('/');
        } catch (Exception $e) {
            return redirect('/')->with('error', 'Failed to create post.');
        }
    }
}

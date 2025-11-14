<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Product $product)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập để bình luận'
            ], 401);
        }

        $request->validate([
            'content' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:comments,id'
        ]);

        $comment = Comment::create([
            'product_id' => $product->id,
            'user_id' => Auth::id(),
            'parent_id' => $request->parent_id,
            'content' => $request->content,
        ]);

        $comment->load('user', 'replies.user');

        return response()->json([
            'success' => true,
            'message' => 'Bình luận đã được đăng thành công',
            'comment' => $comment
        ]);
    }

    public function index(Product $product)
    {
        $comments = $product->comments()->with(['user', 'replies.user'])->get();

        return response()->json([
            'success' => true,
            'comments' => $comments
        ]);
    }
}


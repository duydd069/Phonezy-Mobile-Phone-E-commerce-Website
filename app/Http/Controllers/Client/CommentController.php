<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Product;
use App\Models\BadWord;
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

        // Check if trying to reply to a reply (enforce 1-level nesting only)
        if ($request->parent_id) {
            $parentComment = Comment::find($request->parent_id);
            
            if ($parentComment && $parentComment->parent_id !== null) {
                return response()->json([
                    'success' => false,
                    'message' => 'Chỉ được phép trả lời bình luận gốc. Không thể trả lời một câu trả lời.'
                ], 400);
            }
        }

        // Check content with bad_words table (cached for 1 hour)
        $badWords = cache()->remember('bad_words_list', 3600, function () {
            return BadWord::pluck('word')->map(function($word) {
                return mb_strtolower($word, 'UTF-8');
            })->toArray();
        });
        
        $contentLower = mb_strtolower($request->content, 'UTF-8');
        
        foreach ($badWords as $badWordLower) {
            if (mb_strpos($contentLower, $badWordLower) !== false) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bình luận chứa từ ngữ không phù hợp. Vui lòng sửa lại.'
                ], 400);
            }
        }


        $comment = Comment::create([
            'product_id' => $product->id,
            'user_id' => Auth::id(),
            'parent_id' => $request->parent_id,
            'content' => $request->content,
        ]);

        // Load relationships including parent user for display
        $comment->load('user', 'parent.user', 'replies.user');

        return response()->json([
            'success' => true,
            'message' => 'Bình luận đã được đăng thành công',
            'comment' => $comment
        ]);
    }

    public function index(Product $product)
    {
        // Only get root comments (parent_id = null) with their replies
        $comments = $product->comments()
            ->whereNull('parent_id')
            ->with(['user', 'replies.user', 'replies.parent.user'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'comments' => $comments
        ]);
    }
}


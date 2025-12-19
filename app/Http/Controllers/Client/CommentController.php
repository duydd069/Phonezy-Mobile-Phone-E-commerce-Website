<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\BadWord;
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
            'parent_id' => 'nullable|exists:comments,id',
            'replied_to_user_id' => 'nullable|exists:users,id'
        ]);

        // Check for bad words
        if (BadWord::containsBadWord($request->content)) {
            $badWord = BadWord::findBadWord($request->content);
            return response()->json([
                'success' => false,
                'message' => 'Bình luận chứa từ ngữ không phù hợp' . ($badWord ? ": \"$badWord\"" : '')
            ], 422);
        }

        $parentId = $request->parent_id;
        $repliedToUserId = $request->replied_to_user_id;

        // Enforce 2-level comment structure
        if ($parentId) {
            $parentComment = Comment::find($parentId);
            
            if ($parentComment && $parentComment->parent_id) {
                // Parent is already a child comment (level 1)
                // Use the grandparent as parent to keep at level 1
                $parentId = $parentComment->parent_id;
                
                // If no explicit replied_to_user_id, use parent comment's user
                if (!$repliedToUserId) {
                    $repliedToUserId = $parentComment->user_id;
                }
            }
        }

        $comment = Comment::create([
            'product_id' => $product->id,
            'user_id' => Auth::id(),
            'parent_id' => $parentId,
            'replied_to_user_id' => $repliedToUserId,
            'content' => $request->content,
        ]);

        $comment->load('user', 'repliedToUser', 'replies.user');

        return response()->json([
            'success' => true,
            'message' => 'Bình luận đã được đăng thành công',
            'comment' => $comment
        ]);
    }

    public function index(Product $product)
    {
        $comments = $product->comments()->with(['user', 'repliedToUser', 'replies.user', 'replies.repliedToUser'])->get();

        return response()->json([
            'success' => true,
            'comments' => $comments
        ]);
    }
}


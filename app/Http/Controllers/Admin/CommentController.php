<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Product;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display products with comments, ordered by newest comment
     */
    public function index()
    {
        $products = Product::has('comments')
            ->withCount('comments')
            ->with(['comments' => function($query) {
                $query->latest()->limit(1)->with('user');
            }])
            ->get()
            ->sortByDesc(function($product) {
                return $product->comments->first()?->created_at;
            })
            ->values();

        return view('admin.comments.index', compact('products'));
    }

    /**
     * Show comments for a specific product
     */
    public function show($productId)
    {
        $product = Product::with(['comments' => function($query) {
            $query->whereNull('parent_id')
                  ->with(['user', 'repliedToUser', 'replies.user', 'replies.repliedToUser'])
                  ->latest();
        }])->findOrFail($productId);

        return view('admin.comments.show', compact('product'));
    }

    /**
     * Delete a comment and its replies
     */
    public function destroy($id)
    {
        try {
            $comment = Comment::findOrFail($id);
            
            // Delete child replies first
            $comment->replies()->delete();
            
            // Delete parent comment
            $comment->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Xóa bình luận thành công'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xóa bình luận'
            ], 500);
        }
    }

    /**
     * Admin replies to a comment
     */
    public function reply(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'parent_id' => 'nullable|exists:comments,id',
            'replied_to_user_id' => 'nullable|exists:users,id',
            'content' => 'required|string|max:1000'
        ]);

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
            'product_id' => $request->product_id,
            'user_id' => auth()->id(),
            'parent_id' => $parentId,
            'replied_to_user_id' => $repliedToUserId,
            'content' => $request->content,
        ]);

        return redirect()
            ->route('admin.comments.show', $request->product_id)
            ->with('success', 'Trả lời bình luận thành công');
    }
}

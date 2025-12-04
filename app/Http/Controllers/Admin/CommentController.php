<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Show products that have comments
     */
    public function index()
    {
        // Get products with comment count, sorted by latest comment
        $products = Product::has('comments')
            ->withCount('comments')
            ->with(['comments' => function($query) {
                $query->latest()->limit(1);
            }])
            ->get()
            ->sortByDesc(function($product) {
                return $product->comments->first()->created_at ?? null;
            })
            ->take(100);

        // Paginate manually
        $currentPage = request()->get('page', 1);
        $perPage = 20;
        $products = new \Illuminate\Pagination\LengthAwarePaginator(
            $products->forPage($currentPage, $perPage),
            $products->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('admin.comments.index', compact('products'));
    }

    /**
     * Show comments for a specific product
     */
    public function show(Product $product)
    {
        $comments = $product->comments()
            ->with(['user', 'parent.user', 'replies.user'])
            ->whereNull('parent_id') // Only root comments
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.comments.show', compact('product', 'comments'));
    }

    /**
     * Admin reply to a comment
     */
    public function reply(Request $request, Comment $comment)
    {
        $request->validate([
            'content' => 'required|string|max:1000'
        ]);

        Comment::create([
            'product_id' => $comment->product_id,
            'user_id' => Auth::id(),
            'parent_id' => $comment->id,
            'content' => $request->content,
        ]);

        return redirect()->back()->with('success', 'Đã trả lời bình luận thành công');
    }

    /**
     * Delete a comment
     */
    public function destroy(Comment $comment)
    {
        $product = $comment->product;
        
        // Delete replies first
        $comment->replies()->delete();
        
        // Delete the comment
        $comment->delete();

        return redirect()->route('admin.comments.show', $product)
            ->with('success', 'Đã xóa bình luận thành công');
    }
}


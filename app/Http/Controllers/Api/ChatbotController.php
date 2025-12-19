<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ChatbotService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    public function __construct(private ChatbotService $chatbot)
    {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'min:3'],
        ]);

        // Lấy userId nếu user đã đăng nhập
        $userId = auth()->id();

        $result = $this->chatbot->reply($validated['message'], $userId);

        return response()->json([
            'success' => true,
            'data' => $result,
        ]);
    }
}


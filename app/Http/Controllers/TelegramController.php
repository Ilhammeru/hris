<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TelegramController extends Controller
{
    public function webhook(Request $request): JsonResponse
    {
        $content = $request->getContent();
        Log::debug($content);
        return response()->json($content);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TelegramController extends Controller
{
    public function webhook(Request $request): JsonResponse
    {
        $content = $request->getContent();
        return response()->json($content);
    }
}

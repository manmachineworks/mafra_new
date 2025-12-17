<?php

namespace App\Http\Controllers;

use App\Services\ShiprocketService;

class ShiprocketTokenController extends Controller
{
    public function refresh(ShiprocketService $shiprocket)
    {
        $result = $shiprocket->refreshToken();

        $status = $result['ok'] ? 200 : 422;

        return response()->json([
            'success' => $result['ok'],
            'message' => $result['message'] ?? '',
        ], $status);
    }
}

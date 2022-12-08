<?php

namespace App\Http\Controllers\v1\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    public function sendResponse($data, $message)
    {
        $respons = [
            'success' => true,
            'data' => $data,
            'message' => $message,
        ];
        return response()->json($respons, 200);
    }

    public function sendError( $errorMessages = '', $code = 404)
    {
    	$response = [
            'success' => false,
            'message' => $errorMessages
        ];


        return response()->json($response, $code);
    }
}

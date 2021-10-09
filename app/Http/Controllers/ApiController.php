<?php

namespace App\Http\Controllers;


class ApiController extends Controller
{

    // Check the server
    public function ping()
    {
        return 'OK';
    }

    // About Developer info
    public function me()
    {
        return response()->json([
            'Project' => 'SeedUps by Increments Inc',
            'Developer' => 'Masud Rana && Salman Rahman'
        ], 200);
    }


    /**
     *  Response Json Success
     *
     */
    protected function respondWithSuccess($message = '', $data = [], $code = 200){
        return response()->json([
            'status' => $code,
            'message' => $message,
            'data' =>$data
        ], $code);
    }


    /**
     *  Response Json Success
     *
     */
    protected function respondWithError($message = '', $data = [], $code = 400){
        return response()->json([
            'status' => $code,
            'message' => $message,
            'error' =>$data
        ], $code);
    }

    /**
     * Fallback route response
     */
    public function fallback()
    {
        return response()->json(['message' => 'Route does not exist'], 404);
    }

}

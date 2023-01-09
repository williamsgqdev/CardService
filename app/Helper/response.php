<?php

use Illuminate\Http\Response;

function error($message, $data = null, $code = null)
{
    $d = [
        'code' => $code ?? Response::HTTP_BAD_REQUEST,
        'status' => 'error',
        'message' => $message,
        'data' => $data
    ];



    return response()->json($d, 400);
}


function handle_response($message,  $code, $status, $data = null)
{

    $d = [
        'code' => $code,
        'status' =>  $status,
        'message' => $message,
        'data' => $data
    ];

    return response()->json($d, $code);
}

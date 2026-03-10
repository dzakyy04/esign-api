<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class UserProxyController extends Controller
{
    /**
     * Proxy GET request ke endpoint eksternal untuk mendapatkan status user berdasarkan NIK.
     *
     * @param  string  $nik
     * @return \Illuminate\Http\Response
     */
    public function getUserStatus($nik)
    {
        $baseUrl  = env('PROXY_BASE_URL');
        $username = env('PROXY_USERNAME');
        $password = env('PROXY_PASSWORD');

        $response = Http::withBasicAuth($username, $password)
            ->get("{$baseUrl}/api/user/status/{$nik}");

        return response($response->body(), $response->status())
            ->withHeaders([
                'Content-Type' => $response->header('Content-Type') ?? 'application/json',
            ]);
    }

    /**
     * Proxy POST request untuk check status user berdasarkan NIK di body JSON.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function checkStatusByNik(Request $request)
    {
        $baseUrl  = env('PROXY_BASE_URL');
        $username = env('PROXY_USERNAME');
        $password = env('PROXY_PASSWORD');

        $response = Http::withBasicAuth($username, $password)
            ->post("{$baseUrl}/api/v2/user/check/status", [
                'nik' => $request->input('nik'),
            ]);

        return response($response->body(), $response->status())
            ->withHeaders([
                'Content-Type' => $response->header('Content-Type') ?? 'application/json',
            ]);
    }
}

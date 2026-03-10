<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class UserProxyController extends Controller
{
    public function checkStatusUser($nik)
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

    public function getProfile($nik)
    {
        $baseUrl  = env('PROXY_BASE_URL');
        $username = env('PROXY_USERNAME');
        $password = env('PROXY_PASSWORD');
        
        $response = Http::withBasicAuth($username, $password)
            ->get("{$baseUrl}/api/user/profile/{$nik}");

        return response($response->body(), $response->status())
            ->withHeaders([
                'Content-Type' => $response->header('Content-Type') ?? 'application/json',
            ]);
    }

    public function getExpiredCertificate() {
        $baseUrl  = env('PROXY_BASE_URL');
        $username = env('PROXY_USERNAME');
        $password = env('PROXY_PASSWORD');

        $response = Http::withBasicAuth($username, $password)
            ->get("{$baseUrl}/api/entity/cert/expired");

        return response($response->body(), $response->status())
            ->withHeaders([
                'Content-Type' => $response->header('Content-Type') ?? 'application/json',
            ]);
    }

    public function getCertificateChain($id) {
        $baseUrl  = env('PROXY_BASE_URL');
        $username = env('PROXY_USERNAME');
        $password = env('PROXY_PASSWORD');

        $response = Http::withBasicAuth($username, $password)
            ->get("{$baseUrl}/api/user/certificate/chain/{$id}");

        return response($response->body(), $response->status())
            ->withHeaders([
                'Content-Type' => $response->header('Content-Type') ?? 'application/json',
            ]);
    }
}

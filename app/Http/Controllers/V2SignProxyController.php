<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class V2SignProxyController extends Controller
{
    public function signPdf(Request $request)
    {
        $baseUrl  = env('PROXY_BASE_URL');
        $username = env('PROXY_USERNAME');
        $password = env('PROXY_PASSWORD');

        if ($request->filled('nik')) {
            $payload = [
                'nik'                 => $request->input('nik'),
                'passphrase'          => $request->input('passphrase'),
                'signatureProperties' => $request->input('signatureProperties'),
                'file'                => $request->input('file'),
            ];
        } else {
            $payload = [
                'email'               => $request->input('email'),
                'passphrase'          => $request->input('passphrase'),
                'signatureProperties' => $request->input('signatureProperties'),
                'file'                => $request->input('file'),
            ];
        }

        $response = Http::withBasicAuth($username, $password)
            ->post("{$baseUrl}/api/v2/sign/pdf", $payload);

        return response($response->body(), $response->status())
            ->withHeaders([
                'Content-Type' => $response->header('Content-Type') ?? 'application/json',
            ]);
    }
}

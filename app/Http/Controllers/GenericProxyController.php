<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GenericProxyController extends Controller
{
    public function proxy(Request $request, $path)
    {
        $baseUrl  = env('PROXY_BASE_URL');

        // URL tujuan (endpoint yang sama)
        $url = "{$baseUrl}/api/{$path}";

        // Ambil Method dari original request (GET, POST, dll)
        $method = rtrim(strtoupper($request->method()));

        // Inisiasi HTTP Client dengan header otorisasi yang dilempar dari request
        $client = Http::withHeaders([
            'Authorization' => $request->header('Authorization')
        ]);

        // Ambil query param dari request asalnya (contoh ?nik=123)
        $query = $request->query();

        // Variabel untuk menangani data payload
        $data = $request->except(array_keys($request->allFiles()));

        if (count($request->allFiles()) > 0) {
            // Jika ada file upload (Multipart/form-data)
            foreach ($request->allFiles() as $key => $file) {
                $client->attach($key, file_get_contents($file->path()), $file->getClientOriginalName());
            }

            // Guzzle (via Laravel HTTP client) memerlukan parameter berbeda untuk file + data
            if ($method === 'POST') {
                $response = $client->post($url . '?' . http_build_query($query), $data);
            } elseif ($method === 'PUT') {
                $response = $client->put($url . '?' . http_build_query($query), $data);
            } else {
                $response = $client->send($method, $url, [
                    'query' => $query,
                    'multipart' => $this->buildMultipart($data)
                ]);
            }
        } else {
            // Request biasa
            if ($method === 'GET' || $method === 'HEAD' || $method === 'DELETE') {
                // Biasanya method ini tidak memiliki body
                $response = $client->send($method, $url, [
                    'query' => $query
                ]);
            } else {
                // Method dengan body (POST, PUT, PATCH...)
                if ($request->isJson() || $request->wantsJson()) {
                    $response = $client->send($method, $url, [
                        'query' => $query,
                        'json'  => $data
                    ]);
                } else {
                    $response = $client->send($method, $url, [
                        'query' => $query,
                        'form_params' => $data
                    ]);
                }
            }
        }

        // Return Data Response dan Header Content-Type yang sama dari server
        return response($response->body(), $response->status())
            ->withHeaders([
                'Content-Type' => $response->header('Content-Type') ?? 'application/json',
            ]);
    }

    private function buildMultipart(array $parameters)
    {
        $multipart = [];
        foreach ($parameters as $name => $contents) {
            $multipart[] = ['name' => $name, 'contents' => $contents];
        }
        return $multipart;
    }
}

<?php

namespace App\Repositories;

use App\Contracts\GoogleDriveRepositoryInterface;
use Illuminate\Support\Facades\Http;
use Google\Client as GoogleClient;
use Google\Service\Drive;

class GoogleDriveRepository implements GoogleDriveRepositoryInterface
{
    private array $config;

    public function __construct()
    {
        $this->config = config('services.google');
    }

    private function http(array $extraOptions = []): \Illuminate\Http\Client\PendingRequest
    {
        $defaults = [
            'timeout' => 60,
            'connect_timeout' => 20,
            'curl' => [
                CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
            ],
        ];
        $options = array_replace_recursive($defaults, $extraOptions);
        return Http::withOptions($options);
    }

    public function getAccessToken(): string
    {
        $sessionToken = session('google_token');
        if ($sessionToken && isset($sessionToken['access_token'])) {
            if (!isset($sessionToken['expires_at']) || time() < $sessionToken['expires_at']) {
                return $sessionToken['access_token'];
            }
        }

        return $this->refreshAccessToken();
    }

    private function refreshAccessToken(): string
    {
        if (!$this->config['refresh_token']) {
            throw new \Exception('No se encontró el refresh token en la configuración.');
        }

        try {
            $response = $this->http()
                ->asForm()
                ->retry(3, 1000)
                ->post('https://oauth2.googleapis.com/token', [
                'client_id' => $this->config['client_id'],
                'client_secret' => $this->config['client_secret'],
                'refresh_token' => $this->config['refresh_token'],
                'grant_type' => 'refresh_token',
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $accessToken = $data['access_token'] ?? null;
                if (!$accessToken) {
                    throw new \Exception('Respuesta inválida al obtener token: ' . $response->body());
                }
                $expiresIn = (int)($data['expires_in'] ?? 3600);
                session([
                    'google_token' => [
                        'access_token' => $accessToken,
                        'expires_at' => time() + max(0, $expiresIn - 60),
                    ],
                ]);
                return $accessToken;
            }

            throw new \Exception('No se pudo obtener el token: ' . $response->body());
        } catch (\Exception $e) {
            throw new \Exception('Error obteniendo token Google Drive: ' . $e->getMessage());
        }
    }

    public function uploadFile(string $filePath, string $fileName): string
    {
        $accessToken = $this->getAccessToken();

        $metadata = [
            'name' => $fileName,
        ];

        if (!empty($this->config['folder_id'])) {
            $metadata['parents'] = [ $this->config['folder_id'] ];
        }

        $response = $this->http()
            ->withToken($accessToken)
            ->retry(3, 1000)
            ->attach('metadata', json_encode($metadata), 'metadata.json', ['Content-Type' => 'application/json; charset=UTF-8'])
            ->attach('file', file_get_contents($filePath), $fileName)
            ->post('https://www.googleapis.com/upload/drive/v3/files?uploadType=multipart');

        if (!$response->successful()) {
            throw new \Exception('Failed to upload file to Google Drive: ' . $response->body());
        }

        return $response->json('id');
    }

}
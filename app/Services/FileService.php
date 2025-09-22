<?php

namespace App\Services;

use App\Contracts\FileInterface;
use App\Contracts\GoogleDriveServiceInterface;
use App\Repositories\FileRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;

class FileService implements FileInterface
{
    public function __construct(
        private readonly GoogleDriveServiceInterface $driveService,
        private readonly FileRepository $files
    ) {}

    public function create(UploadedFile $file, string $folder): string
    {
        $base = trim($folder) !== '' ? $folder : pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        $result = $this->driveService->uploadFile($file, $base);
        if (!($result['success'] ?? false)) {
            throw new \RuntimeException($result['error'] ?? 'Error desconocido al subir a Google Drive');
        }

        $created = $this->files->create([
            'file_name'     => $result['upload_name'] ?? ($base . '.' . $file->getClientOriginalExtension()),
            'file_type'     => $result['mime_type'] ?? $file->getClientMimeType(),
            'file_size'     => $result['size'] ?? $file->getSize(),
            'file_user_id'  => Auth::id(),
            'file_google_id'=> $result['file_id'] ?? null,
        ]);

        return $result['message'] ?? ('Archivo registrado: #' . $created->getKey());
    }

    public function latest(int $limit = 10): \Illuminate\Support\Collection
    {
        return $this->files->latest($limit);
    }
}

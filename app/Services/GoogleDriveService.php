<?php

namespace App\Services;

use App\Contracts\GoogleDriveServiceInterface;
use App\Contracts\GoogleDriveRepositoryInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class GoogleDriveService implements GoogleDriveServiceInterface
{
    private GoogleDriveRepositoryInterface $repository;

    public function __construct(GoogleDriveRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function uploadFile(UploadedFile $file, string $fileName): array
    {
        try {
            $originalName = $file->getClientOriginalName();
            $filePath = $file->getRealPath();
            $mimeType = $file->getClientMimeType();
            $fileSize = $file->getSize();
            $extension = $file->getClientOriginalExtension();

            $baseFromOriginal = pathinfo($originalName, PATHINFO_FILENAME);
            $base = $fileName;

            if (empty($base) || str_ireplace(' ', '', $base) === '' || stripos($base, 'untitled') !== false) {
                $base = !empty($baseFromOriginal) ? $baseFromOriginal : 'archivo';
                $base .= '-' . date('Y-m-d_H-i-s');
            }

            $uploadName = $base;
            if (!empty($extension)) {
                if (pathinfo($uploadName, PATHINFO_EXTENSION) === '') {
                    $uploadName .= '.' . $extension;
                }
            }

            $googleFileId = $this->repository->uploadFile($filePath, $uploadName);

            return [
                'success' => true,
                'file_id' => $googleFileId,
                'file_name' => $fileName,
                'original_name' => $originalName,
                'upload_name' => $uploadName,
                'mime_type' => $mimeType,
                'size' => $fileSize,
                'message' => "Archivo subido exitosamente como: {$uploadName}"
            ];
        } catch (\Exception $e) {
            Log::error('Error en uploadFile: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}

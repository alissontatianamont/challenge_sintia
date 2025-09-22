<?php

namespace App\Contracts;

use Illuminate\Http\UploadedFile;

interface GoogleDriveServiceInterface
{
    public function uploadFile(UploadedFile $file, string $fileName): array;
}
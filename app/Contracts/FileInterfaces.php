<?php

namespace App\Contracts;

use Illuminate\Http\UploadedFile;

interface FileInterface
{
    public function create(UploadedFile $file, string $folder): string;
}

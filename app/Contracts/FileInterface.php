<?php

namespace App\Contracts;

use Illuminate\Http\UploadedFile;

interface FileInterface
{
    public function create(UploadedFile $file, string $folder): string;

    public function latest(int $limit = 10): \Illuminate\Support\Collection;
}

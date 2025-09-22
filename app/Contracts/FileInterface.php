<?php

namespace App\Contracts;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface FileInterface
{
    public function create(UploadedFile $file, string $folder): string;

    public function latest(int $limit = 10): Collection;

    /**
     * Return paginated list of files ordered by newest first.
     */
    public function latestPaginated(int $perPage = 10): LengthAwarePaginator;
}

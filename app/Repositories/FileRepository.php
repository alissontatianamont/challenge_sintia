<?php

namespace App\Repositories;
use App\Models\FileRegister;
use Illuminate\Support\Collection;

class FileRepository
{
    /**
     * Create a new class instance.
     */
    public function __construct(protected FileRegister $fileRegister)
    {}

    public function create(array $data): FileRegister
    {
        return $this->fileRegister->create($data);
    }

    public function latest(int $limit = 10): Collection
    {
        return $this->fileRegister
            ->with('user')
            ->orderByDesc('file_created')
            ->limit($limit)
            ->get();
    }
}

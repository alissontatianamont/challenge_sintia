<?php

namespace App\Contracts;

interface GoogleDriveRepositoryInterface
{
    public function getAccessToken(): string;

    public function uploadFile(string $filePath, string $fileName): string;
}
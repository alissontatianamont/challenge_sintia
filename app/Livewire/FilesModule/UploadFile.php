<?php

namespace App\Livewire\FilesModule;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Contracts\FileInterface;
use App\Http\Requests\UploadFileRequest;
use Illuminate\Support\Facades\Validator;

class UploadFile extends Component
{
    use WithFileUploads;

    public $file_cp;
    public $fileBaseName;

    private FileInterface $fileService;

    public function boot(FileInterface $fileService)
    {
        $this->fileService = $fileService;
    }

    // Naming handled by domain services

    public function render()
    {
        $recent = $this->fileService->latest(10);
        return view('livewire.files-module.upload-file', [
            'recentFiles' => $recent,
        ]);
    }

    public function save()
    {
        $validator = Validator::make([
            'file_cp' => $this->file_cp,
        ], (new UploadFileRequest())->rules(), (new UploadFileRequest())->messages());

        if ($validator->fails()) {
            $this->setErrorBag($validator->errors());
            return;
        }

        try {
            $base = trim((string)($this->fileBaseName ?? ''));
            $resultMessage = $this->fileService->create($this->file_cp, $base);

            if ($resultMessage) {
                session()->flash('success', $resultMessage);
                $this->reset(['file_cp', 'fileBaseName']);
            } else {
                session()->flash('error', 'No se pudo registrar el archivo.');
            }

        } catch (\Exception $e) {
            session()->flash('error', "Error al subir archivo: " . $e->getMessage());
        }
    }
}

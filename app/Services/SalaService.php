<?php

namespace App\Services;

use App\Models\Sala;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class SalaService
{
    public function getSalas()
    {
        return Sala::orderBy('nome')->get();
    }

    public function createSala(array $data)
    {
        if (isset($data['imagem']) && $data['imagem'] instanceof UploadedFile) {
            $data['imagem'] = $this->uploadImage($data['imagem']);
        }

        return Sala::create($data);
    }

    public function updateSala(Sala $sala, array $data)
    {
        if (isset($data['imagem']) && $data['imagem'] instanceof UploadedFile) {
            if ($sala->imagem) {
                Storage::disk('public')->delete($sala->imagem);
            }

            $data['imagem'] = $this->uploadImage($data['imagem']);
        }

        $sala->update($data);

        return $sala;
    }

    private function uploadImage(UploadedFile $file)
    { 
        $originalName = $file->getClientOriginalName();
        $fileName = time().'_'.$originalName;
        return $file->storeAs('salas', $fileName, 'public');
    }

    public function deleteSala(Sala $sala)
    {
        if ($sala->imagem) {
            Storage::disk('public')->delete($sala->imagem);
        }
        return $sala->deleteOrFail();
    }
}

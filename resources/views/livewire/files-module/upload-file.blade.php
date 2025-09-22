<div>
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-lg font-medium mb-4">Subir archivo a Google Drive</h2>

        @if (session()->has('success'))
            <div class="p-2 bg-green-500 text-white mb-4 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="p-2 bg-red-500 text-white mb-4 rounded">
                {{ session('error') }}
            </div>
        @endif

    <form wire:submit.prevent="save" enctype="multipart/form-data">

            <div class="mb-4">
                <label for="file_cp" class="block text-sm font-medium text-gray-700">Seleccione archivo *</label>
          <input type="file" id="file_cp" wire:model="file_cp" 
              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                <small class="text-gray-600">Tamaño máximo 5MB. Formatos permitidos: .pdf y .csv</small>
                
                @error('file_cp') 
                    <div class="mt-2 text-red-600 text-sm">
                        <ul>
                            @foreach($errors->get('file_cp') as $error)
                                <li>• {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="fileBaseName" class="block text-sm font-medium text-gray-700">Nombre base (opcional)</label>
                <input type="text" id="fileBaseName" wire:model.defer="fileBaseName"
                       placeholder="Ej: Reporte-Ventas"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                <small class="text-gray-600">Si lo ingresas, será el prefijo del nombre final. Se concatenará la fecha y hora automáticamente.</small>
            </div>

            @if($file_cp)
                <div class="mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded-md">
                    <p class="text-sm text-yellow-800">
                        <strong>Archivo seleccionado:</strong> Se generará un nombre automático
                    </p>
                    <p class="text-xs text-yellow-600">
                        formato: archivo / nombre_personalizado
                    </p>
                </div>
            @endif

            <div class="flex justify-end">
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed"
                        wire:loading.attr="disabled"
                        wire:target="save">
                    <span wire:loading.remove wire:target="save">Subir archivo</span>
                    <span wire:loading wire:target="save">Subiendo...</span>
                </button>
            </div>
        </form>
    </div>

    @isset($recentFiles)
    <div class="bg-white shadow rounded-lg p-6 mt-6">
        <h3 class="text-md font-medium mb-4">Archivos recientes</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuario</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha de subida</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($recentFiles as $f)
                        <tr>
                            <td class="px-4 py-2 text-sm text-gray-900">{{ $f->file_name }}</td>
                            <td class="px-4 py-2 text-sm text-gray-900">{{ optional($f->user)->name ?? '—' }}</td>
                            <td class="px-4 py-2 text-sm text-gray-900">{{ optional($f->file_created)->format('Y-m-d H:i') ?? (is_string($f->file_created) ? $f->file_created : '') }}</td>
                            <td class="px-4 py-2 text-sm">
                                @if(!empty($f->file_google_id))
                                    <a href="https://drive.google.com/file/d/{{ $f->file_google_id }}/view" target="_blank" rel="noopener noreferrer"
                                       class="inline-flex items-center px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 mr-1"><path d="M12 5c-3.86 0-7 3.14-7 7 0 3.53 2.61 6.43 6 6.92V17h2v2.92c3.39-.49 6-3.39 6-6.92 0-3.86-3.14-7-7-7zm0-3c5.52 0 10 4.48 10 10s-4.48 10-10 10S2 17.52 2 12 6.48 2 12 2zm-1 5h2v6h-2zm0 8h2v2h-2z"/></svg>
                                        Ver
                                    </a>
                                @else
                                    <span class="text-xs text-gray-500">Sin ID de Drive</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-4 text-sm text-gray-500">No hay archivos recientes.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $recentFiles->links() }}
        </div>
    </div>
    @endisset
</div>

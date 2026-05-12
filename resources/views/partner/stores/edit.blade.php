@extends('layouts.app')

@section('title', 'Edit Store Settings')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('partner.store.show') }}" class="btn btn-light rounded-pill me-3">
                    &larr; Back
                </a>
                <h1 class="h3 fw-bold mb-0 text-primary">Edit Store Settings</h1>
            </div>

            <div class="card shadow-sm border-0 p-4">
                <div class="card-body">
                    <form action="{{ route('partner.store.update') }}" method="POST">
                        @csrf
                        @method('PATCH')
                        
                        <div class="mb-4">
                            <label for="name" class="form-label">Store Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $store->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description', $store->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Store Logo</label>
                            
                            <div class="d-flex align-items-center mb-3">
                                <div id="previewImageContainer" class="bg-light rounded d-flex align-items-center justify-content-center me-3" style="width: 100px; height: 100px; border: 1px solid #ddd;">
                                    @if($store->logo)
                                        <img src="{{ Storage::url($store->logo) }}" style="width: 100%; height: 100%; object-fit: cover;" class="rounded">
                                    @else
                                        <i class="bi bi-image text-muted fs-3"></i>
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    <div class="border rounded-3 p-3 text-center bg-light" id="dropZone" style="border-style: dashed !important; border-width: 2px !important; cursor: pointer;" onclick="document.getElementById('fileInput').click()">
                                        <input type="file" id="fileInput" class="d-none" accept="image/*">
                                        <i class="bi bi-cloud-upload text-primary mb-2" style="font-size: 1.5rem;"></i>
                                        <p class="mb-0 small">Click or drag & drop to upload logo</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div id="uploadProgress" class="d-none">
                                <div class="progress rounded-pill" style="height: 10px;">
                                    <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%"></div>
                                </div>
                                <p id="progressText" class="small text-center mt-1 mb-0 text-muted">Uploading...</p>
                            </div>

                            <input type="hidden" name="logo" id="logoInput" value="{{ old('logo', $store->logo) }}">
                            @error('logo')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid mt-5">
                            <button type="submit" class="btn btn-primary rounded-pill py-2 fw-bold" id="submitBtn">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const CHUNK_SIZE = 512 * 1024; // 512KB chunks to prevent 413 Payload Too Large
    const fileInput = document.getElementById('fileInput');
    const dropZone = document.getElementById('dropZone');
    const progressBar = document.getElementById('progressBar');
    const progressText = document.getElementById('progressText');
    const uploadProgress = document.getElementById('uploadProgress');
    const submitBtn = document.getElementById('submitBtn');
    const logoInput = document.getElementById('logoInput');
    const previewImageContainer = document.getElementById('previewImageContainer');

    let isUploading = false;

    fileInput.addEventListener('change', (e) => {
        if (e.target.files.length > 0) handleFile(e.target.files[0]);
    });

    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('bg-white');
    });

    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('bg-white');
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('bg-white');
        if (e.dataTransfer.files.length > 0) handleFile(e.dataTransfer.files[0]);
    });

    async function handleFile(file) {
        if (isUploading) return;
        isUploading = true;
        submitBtn.disabled = true;
        uploadProgress.classList.remove('d-none');

        const totalChunks = Math.ceil(file.size / CHUNK_SIZE);
        const uuid = self.crypto.randomUUID();
        const fileName = file.name;

        for (let chunkIndex = 0; chunkIndex < totalChunks; chunkIndex++) {
            const start = chunkIndex * CHUNK_SIZE;
            const end = Math.min(start + CHUNK_SIZE, file.size);
            const chunk = file.slice(start, end);

            const formData = new FormData();
            formData.append('file', chunk);
            formData.append('fileName', fileName);
            formData.append('chunkIndex', chunkIndex);
            formData.append('totalChunks', totalChunks);
            formData.append('uuid', uuid);
            formData.append('_token', '{{ csrf_token() }}');

            try {
                const response = await fetch('{{ route("partner.store.upload_chunk") }}', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();
                
                const percent = Math.round(((chunkIndex + 1) / totalChunks) * 100);
                progressBar.style.width = percent + '%';
                progressText.innerText = `Uploading: ${percent}%`;

                if (result.completed && result.path) {
                    logoInput.value = result.path;
                    previewImageContainer.innerHTML = `<img src="/storage/${result.path}" style="width: 100%; height: 100%; object-fit: cover;" class="rounded">`;
                }
            } catch (error) {
                console.error('Upload failed', error);
                alert('Upload failed for logo.');
                break;
            }
        }

        isUploading = false;
        submitBtn.disabled = false;
        setTimeout(() => uploadProgress.classList.add('d-none'), 1000);
        fileInput.value = '';
    }
</script>
@endsection

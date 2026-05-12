@extends('layouts.app')

@section('title', 'Add New Product')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('partner.products.index') }}" class="btn btn-light rounded-pill me-3">
                    &larr; Back
                </a>
                <h1 class="h3 fw-bold mb-0 text-primary">Add New Product</h1>
            </div>

            <div class="card shadow-sm border-0 p-4">
                <div class="card-body">
                    <form id="productForm" action="{{ route('partner.products.store') }}" method="POST">
                        @csrf
                        
                        <div class="row g-4">
                            <div class="col-md-7">
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Store</label>
                                    <input type="text" class="form-control bg-light" value="{{ $store->name }}" readonly disabled>
                                    <div class="form-text">Products are automatically assigned to your store.</div>
                                </div>

                                <div class="mb-4">
                                    <label for="name" class="form-label fw-bold">Product Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="e.g. Classic Vanilla Cake" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label for="price" class="form-label fw-bold">Price (₱)</label>
                                        <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price') }}" required>
                                        @error('price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="category_id" class="form-label fw-bold">Category</label>
                                        <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                            <option value="">-- Choose Category --</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-12">
                                        <label for="status" class="form-label fw-bold">Status</label>
                                        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                            <option value="Active" {{ old('status') == 'Active' ? 'selected' : '' }}>Active</option>
                                            <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="description" class="form-label fw-bold">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5" placeholder="Describe your product...">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-5">
                                <div class="mb-4">
                                    <h5 class="fw-bold mb-3">Live Preview</h5>
                                    <div class="card h-100 shadow-sm border-0 product-card mx-auto" style="max-width: 300px; border-radius: 15px !important;">
                                        <div id="previewImageContainer" class="bg-light d-flex align-items-center justify-content-center" style="height: 180px; border-top-left-radius: 15px; border-top-right-radius: 15px;">
                                            <i class="bi bi-image text-muted display-4"></i>
                                        </div>
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <span id="previewCategory" class="badge bg-light text-dark rounded-pill">Category</span>
                                                <p class="fw-bold text-primary mb-0">₱<span id="previewPrice">0.00</span></p>
                                            </div>
                                            <h5 id="previewName" class="card-title fw-bold mb-1">Product Name</h5>
                                            <p class="text-muted small mb-4 product-brand">By <span class="fw-bold">{{ $store->name }}</span></p>
                                        </div>
                                        <div class="card-footer bg-white border-0 pb-4 px-3 text-center">
                                            <div class="mb-1 text-warning">
                                                <i class="bi bi-star-fill"></i>
                                                <i class="bi bi-star-fill"></i>
                                                <i class="bi bi-star-fill"></i>
                                                <i class="bi bi-star-fill"></i>
                                                <i class="bi bi-star-fill"></i>
                                            </div>
                                            <div class="small text-muted">(0 reviews)</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold">Product Images</label>
                                    <div class="border rounded-4 p-4 text-center bg-light" id="dropZone" style="border-style: dashed !important; border-width: 2px !important;">
                                        <input type="file" id="fileInput" class="d-none" multiple accept="image/*">
                                        <div class="py-3">
                                            <i class="bi bi-cloud-upload display-4 text-primary"></i>
                                            <p class="mt-3 mb-0">Drag & drop images here or <button type="button" class="btn btn-link p-0 text-decoration-none fw-bold" onclick="document.getElementById('fileInput').click()">browse</button></p>
                                            <p class="small text-muted mt-2">Chunked upload enabled for reliability.</p>
                                        </div>
                                    </div>
                                    
                                    <div id="uploadProgress" class="mt-3 d-none">
                                        <div class="progress rounded-pill" style="height: 10px;">
                                            <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%"></div>
                                        </div>
                                        <p id="progressText" class="small text-center mt-2 text-muted">Uploading...</p>
                                    </div>

                                    <div id="imageList" class="row g-2 mt-3">
                                        <!-- Uploaded images will appear here -->
                                    </div>
                                    
                                    <!-- Hidden inputs for final form submission -->
                                    <div id="hiddenInputs"></div>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid mt-5">
                            <button type="submit" class="btn btn-primary rounded-pill py-3 fw-bold shadow-sm" id="submitBtn">Create Product</button>
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
    const imageList = document.getElementById('imageList');
    const hiddenInputs = document.getElementById('hiddenInputs');
    const progressBar = document.getElementById('progressBar');
    const progressText = document.getElementById('progressText');
    const uploadProgress = document.getElementById('uploadProgress');
    const submitBtn = document.getElementById('submitBtn');

    // Preview Elements
    const nameInput = document.getElementById('name');
    const priceInput = document.getElementById('price');
    const categorySelect = document.getElementById('category_id');
    const previewName = document.getElementById('previewName');
    const previewPrice = document.getElementById('previewPrice');
    const previewCategory = document.getElementById('previewCategory');
    const previewImageContainer = document.getElementById('previewImageContainer');

    nameInput.addEventListener('input', () => {
        previewName.innerText = nameInput.value || 'Product Name';
    });

    priceInput.addEventListener('input', () => {
        const val = parseFloat(priceInput.value);
        previewPrice.innerText = isNaN(val) ? '0.00' : val.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
    });

    categorySelect.addEventListener('change', () => {
        const selectedText = categorySelect.options[categorySelect.selectedIndex].text;
        previewCategory.innerText = categorySelect.value ? selectedText : 'Category';
    });

    // Initialize Preview on Load
    if (nameInput.value) previewName.innerText = nameInput.value;
    if (priceInput.value) {
        const val = parseFloat(priceInput.value);
        previewPrice.innerText = isNaN(val) ? '0.00' : val.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
    }
    if (categorySelect.value) {
        previewCategory.innerText = categorySelect.options[categorySelect.selectedIndex].text;
    }

    let isUploading = false;

    fileInput.addEventListener('change', (e) => handleFiles(e.target.files));

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
        handleFiles(e.dataTransfer.files);
    });

    async function handleFiles(files) {
        if (isUploading) return;
        isUploading = true;
        submitBtn.disabled = true;
        uploadProgress.classList.remove('d-none');

        for (let i = 0; i < files.length; i++) {
            await uploadFileInChunks(files[i]);
        }

        isUploading = false;
        submitBtn.disabled = false;
        uploadProgress.classList.add('d-none');
        fileInput.value = '';
    }

    async function uploadFileInChunks(file) {
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
                const response = await fetch('{{ route("partner.products.upload_chunk") }}', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();
                
                // Update progress
                const percent = Math.round(((chunkIndex + 1) / totalChunks) * 100);
                progressBar.style.width = percent + '%';
                progressText.innerText = `Uploading ${fileName}: ${percent}%`;

                if (result.completed) {
                    addThumb(result.path, result.name);
                }
            } catch (error) {
                console.error('Upload failed', error);
                alert('Upload failed for ' + fileName);
                break;
            }
        }
    }

    function addThumb(path, name) {
        const col = document.createElement('div');
        col.className = 'col-4';
        col.innerHTML = `
            <div class="position-relative">
                <img src="/storage/${path}" class="img-fluid rounded border shadow-sm" style="height: 80px; width: 100%; object-fit: cover;">
                <button type="button" class="btn btn-danger btn-sm rounded-circle position-absolute top-0 end-0 m-1" onclick="this.parentElement.parentElement.remove(); removeInput('${name}')" style="padding: 0 6px;">&times;</button>
            </div>
        `;
        imageList.appendChild(col);

        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'images[]';
        input.value = path;
        input.id = `input-${name.replace(/\./g, '_')}`;
        hiddenInputs.appendChild(input);

        // Update main preview image if it's the first one
        if (hiddenInputs.querySelectorAll('input[name="images[]"]').length === 1) {
            previewImageContainer.innerHTML = `<img src="/storage/${path}" class="card-img-top" style="height: 180px; object-fit: cover; border-top-left-radius: 15px; border-top-right-radius: 15px;">`;
        }
    }

    function removeInput(name) {
        const input = document.getElementById(`input-${name.replace(/\./g, '_')}`);
        if (input) input.remove();

        // Update preview image to next available or default
        const nextInput = hiddenInputs.querySelector('input[name="images[]"]');
        if (nextInput) {
            previewImageContainer.innerHTML = `<img src="/storage/${nextInput.value}" class="card-img-top" style="height: 180px; object-fit: cover; border-top-left-radius: 15px; border-top-right-radius: 15px;">`;
        } else {
            previewImageContainer.innerHTML = `<i class="bi bi-image text-muted display-4"></i>`;
        }
    }
</script>
@endsection

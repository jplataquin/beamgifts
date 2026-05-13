@extends('layouts.app')

@section('title', 'My Digital Gifts - Beam Gifts')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 h-100">
                <div class="card-body p-0">
                    <h5 class="fw-bold text-primary mb-4">Account Menu</h5>
                    <div class="list-group list-group-flush">
                        <a href="{{ route('profile') }}" class="list-group-item list-group-item-action border-0 rounded-pill mb-1">My Profile</a>
                        <a href="{{ route('my-gifts') }}" class="list-group-item list-group-item-action active rounded-pill mb-1 border-0">My Gifts</a>
                        <a href="{{ route('reviews.index') }}" class="list-group-item list-group-item-action rounded-pill mb-1 border-0">Reviews Awaiting</a>
                        <a href="{{ route('my-orders') }}" class="list-group-item list-group-item-action rounded-pill mb-1 border-0">Order History</a>
                        <form action="{{ route('logout') }}" method="POST" class="mt-3">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm w-100 rounded-pill">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <h1 class="h3 fw-bold mb-4 text-primary">My Purchased Gifts</h1>
            <p class="text-muted mb-5">Personalize your gifts with a note and a photo before sending the link.</p>
            
            @if(session('success'))
                <div class="alert alert-success rounded-pill px-4 mb-4">{{ session('success') }}</div>
            @endif

            <div class="row g-4">
                @forelse($vouchers as $voucher)
                    <div class="col-md-6">
                        <div class="card shadow-sm border-0 h-100 hover-card overflow-hidden">
                            <div class="row g-0 h-100">
                                <div class="col-4">
                                    @php 
                                        $displayPhoto = $voucher->custom_photo ?: (!empty($voucher->product->images) ? $voucher->product->images[0] : null);
                                    @endphp
                                    @if($displayPhoto)
                                        <img src="{{ Storage::url($displayPhoto) }}" class="img-fluid h-100 w-100" style="object-fit: cover;">
                                    @else
                                        <div class="bg-light h-100 w-100 d-flex align-items-center justify-content-center">
                                            <i class="bi bi-image text-muted fs-2"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-8">
                                    <div class="card-body p-4 d-flex flex-column h-100">
                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between mb-3">
                                                <span class="badge {{ $voucher->status === 'active' ? 'bg-success' : 'bg-secondary' }} rounded-pill px-3">
                                                    {{ strtoupper($voucher->status) }}
                                                </span>
                                                <small class="text-muted">{{ $voucher->created_at->format('M d, Y') }}</small>
                                            </div>
                                            
                                            <h5 class="fw-bold mb-1">{{ $voucher->product->name }}</h5>
                                            <p class="text-muted small mb-3">Store: {{ $voucher->product->store->name }}</p>
                                            
                                            @if($voucher->personal_message)
                                                <div class="bg-light p-2 rounded-3 mb-0 small italic" style="border-left: 3px solid var(--bs-primary);">
                                                    <i class="bi bi-quote text-primary"></i> 
                                                    {{ Str::limit($voucher->personal_message, 45) }}
                                                    <i class="bi bi-quote text-primary bi-quote-reverse"></i>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="d-grid gap-2 mt-auto">
                                            <button class="btn btn-outline-primary rounded-pill btn-sm" data-bs-toggle="modal" data-bs-target="#messageModal-{{ $voucher->id }}">
                                                <i class="bi bi-stars me-1"></i> Personalize
                                            </button>
                                            
                                            <a href="{{ route('voucher.show', $voucher->unique_token) }}" class="btn btn-primary rounded-pill btn-sm py-2">View & Send</a>
                                            
                                            <div class="input-group input-group-sm">
                                                <input type="text" class="form-control bg-light border-0" value="{{ route('voucher.show', $voucher->unique_token) }}" id="link-{{ $voucher->id }}" readonly>
                                                <button class="btn btn-outline-primary" type="button" onclick="copyLink('{{ $voucher->id }}')">Copy</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Personalization Modal -->
                        <div class="modal fade" id="messageModal-{{ $voucher->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 rounded-4 shadow">
                                    <form action="{{ route('vouchers.update_message', $voucher) }}" method="POST" class="personalization-form">
                                        @csrf
                                        <div class="modal-header border-0 pb-0">
                                            <h5 class="modal-title fw-bold">Personalize Your Gift</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body py-4">
                                            <div class="mb-4">
                                                <label class="form-label small fw-bold text-muted text-uppercase">Gift Message</label>
                                                <textarea name="personal_message" class="form-control border-light bg-light rounded-3" rows="3" placeholder="Happy Birthday!..." maxlength="1000">{{ old('personal_message', $voucher->personal_message) }}</textarea>
                                            </div>
                                            
                                            <div class="mb-0">
                                                <label class="form-label small fw-bold text-muted text-uppercase">Custom Photo</label>
                                                <div class="photo-upload-zone border rounded-3 p-3 text-center bg-light" style="border-style: dashed !important;">
                                                    <input type="file" class="photo-input d-none" accept="image/*" data-voucher-id="{{ $voucher->id }}">
                                                    <div class="photo-preview-container mb-2 {{ $voucher->custom_photo ? '' : 'd-none' }}">
                                                        <img src="{{ $voucher->custom_photo ? Storage::url($voucher->custom_photo) : '' }}" class="img-thumbnail rounded-3" style="max-height: 150px;">
                                                    </div>
                                                    <div class="upload-ui">
                                                        <i class="bi bi-camera text-primary fs-3"></i>
                                                        <p class="mb-0 small text-muted">Upload a photo to appear when the gift is unwrapped.</p>
                                                        <button type="button" class="btn btn-link btn-sm text-decoration-none fw-bold p-0" onclick="this.parentElement.previousElementSibling.previousElementSibling.click()">Select Photo</button>
                                                    </div>
                                                    <div class="upload-progress d-none mt-2">
                                                        <div class="progress rounded-pill" style="height: 6px;">
                                                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="custom_photo" class="final-photo-path" value="{{ $voucher->custom_photo }}">
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0 pt-0">
                                            <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary rounded-pill px-4 submit-save">Save Changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <div class="mb-4">
                            <i class="bi bi-gift display-1 text-light"></i>
                        </div>
                        <h2 class="h5 fw-bold">No gifts found</h2>
                        <p class="text-muted mb-4">You haven't purchased any digital vouchers yet.</p>
                        <a href="{{ url('/') }}" class="btn btn-primary rounded-pill px-5 py-2">Explore Gifts</a>
                    </div>
                @endforelse
            </div>
            
            <div class="mt-5">
                {{ $vouchers->links() }}
            </div>
        </div>
    </div>
</div>

<script>
    const CHUNK_SIZE = 512 * 1024; // 512KB chunks to prevent 413 Payload Too Large

    document.querySelectorAll('.photo-input').forEach(input => {
        input.addEventListener('change', async function(e) {
            const file = e.target.files[0];
            if (!file) return;

            // 7MB limit (7 * 1024 * 1024)
            if (file.size > 7340032) {
                alert('The photo size must be 7MB or less.');
                this.value = ''; // Reset input
                return;
            }

            const modal = this.closest('.modal');
            const previewContainer = modal.querySelector('.photo-preview-container');
            const previewImg = previewContainer.querySelector('img');
            const uploadUi = modal.querySelector('.upload-ui');
            const progressContainer = modal.querySelector('.upload-progress');
            const progressBar = progressContainer.querySelector('.progress-bar');
            const hiddenPathInput = modal.querySelector('.final-photo-path');
            const saveBtn = modal.querySelector('.submit-save');

            saveBtn.disabled = true;
            uploadUi.classList.add('d-none');
            progressContainer.classList.remove('d-none');

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
                    const response = await fetch('{{ route("vouchers.upload_chunk") }}', {
                        method: 'POST',
                        body: formData
                    });

                    const result = await response.json();
                    
                    const percent = Math.round(((chunkIndex + 1) / totalChunks) * 100);
                    progressBar.style.width = percent + '%';

                    if (result.completed) {
                        previewImg.src = '/storage/' + result.path;
                        previewContainer.classList.remove('d-none');
                        hiddenPathInput.value = result.path;
                        progressContainer.classList.add('d-none');
                        uploadUi.classList.remove('d-none');
                        saveBtn.disabled = false;
                    }
                } catch (error) {
                    console.error('Upload failed', error);
                    alert('Photo upload failed. Please try again.');
                    saveBtn.disabled = false;
                    break;
                }
            }
        });
    });

    function copyLink(id) {
        var copyText = document.getElementById("link-" + id);
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(copyText.value);
        
        const btn = event.target;
        const originalText = btn.innerText;
        btn.innerText = "Copied!";
        btn.classList.replace('btn-outline-primary', 'btn-success');
        setTimeout(() => {
            btn.innerText = originalText;
            btn.classList.replace('btn-success', 'btn-outline-primary');
        }, 2000);
    }
</script>
@endsection

@extends('layouts.app')

@section('title', 'Manage Gift - Beam Gifts')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-3">
            @include('partials.account-menu')
        </div>
        <div class="col-md-9">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('my-gifts') }}" class="btn btn-light rounded-circle me-3">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h1 class="h3 fw-bold mb-0 text-primary">Manage Your Gift</h1>
            </div>

            @if(session('success'))
                <div class="alert alert-success rounded-pill px-4 mb-4">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger rounded-pill px-4 mb-4">{{ session('error') }}</div>
            @endif

            <div class="row g-4">
                <!-- Left Column: Details & Personalization -->
                <div class="col-lg-7">
                    <!-- Personalization Card -->
                    <div class="card shadow-sm border-0 rounded-4 mb-4">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-4">Personalize Your Gift</h5>
                            
                            @if($voucher->status !== 'active')
                                <div class="alert alert-warning rounded-3 mb-4">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                    This gift has been <strong>{{ $voucher->status }}</strong>. Personalization can no longer be edited.
                                </div>
                            @endif

                            <form action="{{ route('vouchers.update_message', $voucher) }}" method="POST" id="personalization-form">
                                @csrf
                                <div class="mb-4">
                                    <label class="form-label small fw-bold text-muted text-uppercase">Gift Message</label>
                                    <textarea name="personal_message" class="form-control border-light bg-light rounded-3" rows="4" placeholder="Happy Birthday!..." maxlength="1000" {{ $voucher->status !== 'active' ? 'disabled' : '' }}>{{ old('personal_message', $voucher->personal_message) }}</textarea>
                                    <div class="form-text small">This message will appear when the recipient unwraps the gift.</div>
                                </div>
                                
                                <div class="mb-4">
                                    <label class="form-label small fw-bold text-muted text-uppercase">Custom Photo</label>
                                    <div class="photo-upload-zone border rounded-4 p-4 text-center bg-light" style="border-style: dashed !important;">
                                        <input type="file" class="photo-input d-none" id="photo-input" accept="image/*" {{ $voucher->status !== 'active' ? 'disabled' : '' }}>
                                        
                                        <div class="photo-preview-container mb-3 {{ $voucher->custom_photo ? '' : 'd-none' }}">
                                            <img src="{{ $voucher->custom_photo ? Storage::url($voucher->custom_photo) : '' }}" class="img-thumbnail rounded-4 shadow-sm" style="max-height: 250px;">
                                        </div>

                                        @if($voucher->status === 'active')
                                        <div class="upload-ui">
                                            <i class="bi bi-camera text-primary fs-2 mb-2 d-block"></i>
                                            <p class="mb-3 small text-muted">Upload a photo to appear during the unwrap reveal.</p>
                                            <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-4" onclick="document.getElementById('photo-input').click()">
                                                {{ $voucher->custom_photo ? 'Change Photo' : 'Select Photo' }}
                                            </button>
                                        </div>

                                        <div class="upload-progress d-none mt-3">
                                            <div class="progress rounded-pill" style="height: 10px;">
                                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%"></div>
                                            </div>
                                            <p class="small text-muted mt-2 mb-0">Uploading... <span class="percent">0</span>%</p>
                                        </div>
                                        @endif
                                    </div>
                                    <input type="hidden" name="custom_photo" id="final-photo-path" value="{{ $voucher->custom_photo }}">
                                </div>

                                @if($voucher->status === 'active')
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary rounded-pill py-3 fw-bold" id="submit-save">
                                        Save Personalization
                                    </button>
                                </div>
                                @endif
                            </form>
                        </div>
                    </div>

                    @if($voucher->claimed_at)
                    <!-- Review Card -->
                    <div class="card shadow-sm border-0 rounded-4 mb-4">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-4">Review Your Gift</h5>
                            
                            @if($voucher->review)
                                <div class="d-flex align-items-center mb-3">
                                    <div class="text-warning me-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="bi bi-star-fill {{ $i <= $voucher->review->rating ? '' : 'text-light' }}"></i>
                                        @endfor
                                    </div>
                                    <span class="fw-bold">{{ $voucher->review->rating }}/5</span>
                                </div>
                                @if($voucher->review->comment)
                                    <p class="text-muted mb-0 italic">"{{ $voucher->review->comment }}"</p>
                                @endif
                            @else
                                <form action="{{ route('reviews.store', $voucher) }}" method="POST">
                                    @csrf
                                    <div class="mb-4">
                                        <label class="form-label small fw-bold text-muted text-uppercase mb-3">How would you rate this product?</label>
                                        <div class="rating-input d-flex flex-row-reverse justify-content-end gap-2">
                                            @for($i = 5; $i >= 1; $i--)
                                                <input type="radio" name="rating" value="{{ $i }}" id="star-{{ $i }}" class="btn-check" required>
                                                <label for="star-{{ $i }}" class="btn btn-outline-light rounded-circle d-flex align-items-center justify-content-center star-label" style="width: 45px; height: 45px;">
                                                    <i class="bi bi-star-fill text-muted"></i>
                                                </label>
                                            @endfor
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label small fw-bold text-muted text-uppercase">Your Feedback (Optional)</label>
                                        <textarea name="comment" class="form-control border-light bg-light rounded-3" rows="4" placeholder="Tell us what you liked or how we can improve..." maxlength="1000"></textarea>
                                    </div>

                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary rounded-pill py-3 fw-bold">
                                            Submit Review
                                        </button>
                                    </div>
                                </form>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Redemption Info -->
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-3">Redemption Instructions</h5>
                            <p class="text-muted small mb-4">The recipient can redeem this gift at any of the following branches by presenting the QR code.</p>
                            
                            @php
                                $groupedBranches = $voucher->product->store->branches->groupBy(function($branch) {
                                    return $branch->city->name ?? 'Other';
                                });
                            @endphp

                            @foreach($groupedBranches as $cityName => $branches)
                                <div class="mb-3">
                                    <h6 class="fw-bold text-uppercase small text-muted border-bottom pb-1 mb-3">{{ $cityName }}</h6>
                                    <ul class="list-unstyled mb-0">
                                        @foreach($branches as $branch)
                                            <li class="mb-3 d-flex justify-content-between align-items-start">
                                                <div>
                                                    <div class="fw-bold small">{{ $branch->name }}</div>
                                                    <div class="text-muted" style="font-size: 0.75rem;">{{ $branch->address }}</div>
                                                </div>
                                                @if($branch->map_url)
                                                    <a href="{{ $branch->map_url }}" target="_blank" class="btn btn-sm btn-light rounded-circle ms-2">
                                                        <i class="bi bi-geo-alt text-primary"></i>
                                                    </a>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Right Column: Preview & Sharing -->
                <div class="col-lg-5">
                    <!-- Share Card -->
                    <div class="card shadow-sm border-0 rounded-4 mb-4 bg-primary text-white">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-3">Share this Gift</h5>
                            <p class="small opacity-75 mb-4">Copy the link below and send it to the recipient via Messenger, WhatsApp, or SMS.</p>
                            
                            <div class="input-group mb-3">
                                <input type="text" class="form-control border-0 bg-white bg-opacity-25 text-white" value="{{ route('voucher.show', $voucher->unique_token) }}" id="share-link" readonly>
                                <button class="btn btn-white" type="button" onclick="copyShareLink()" id="copy-btn">Copy</button>
                            </div>

                            <div class="d-grid">
                                <a href="{{ route('voucher.show', $voucher->unique_token) }}" target="_blank" class="btn btn-light rounded-pill fw-bold">
                                    <i class="bi bi-eye me-2"></i> Preview as Recipient
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Voucher Summary -->
                    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                        <div class="bg-light p-3 border-bottom d-flex justify-content-between align-items-center">
                            <span class="small fw-bold text-muted text-uppercase">Voucher Details</span>
                            <span class="badge {{ $voucher->status === 'active' ? 'bg-success' : 'bg-secondary' }} rounded-pill">
                                {{ strtoupper($voucher->status) }}
                            </span>
                        </div>
                        <div class="card-body p-4">
                            <div class="d-flex mb-4">
                                @if(!empty($voucher->product->images))
                                    <img src="{{ Storage::url($voucher->product->images[0]) }}" class="rounded-3 me-3" style="width: 80px; height: 80px; object-fit: cover;">
                                @endif
                                <div>
                                    <h6 class="fw-bold mb-1">{{ $voucher->product->name }}</h6>
                                    <p class="text-muted small mb-0">{{ $voucher->product->store->name }}</p>
                                    <div class="fw-bold text-primary">₱{{ number_format($voucher->price ?? $voucher->product->price, 2) }}</div>
                                </div>
                            </div>

                            <hr class="my-4 opacity-10">

                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="small text-muted mb-1">Purchased On</div>
                                    <div class="fw-bold small">{{ $voucher->created_at->format('M d, Y') }}</div>
                                </div>
                                <div class="col-6">
                                    <div class="small text-muted mb-1">Valid Until</div>
                                    <div class="fw-bold small">{{ $voucher->expires_at ? $voucher->expires_at->format('M d, Y') : 'N/A' }}</div>
                                </div>
                                <div class="col-12">
                                    <div class="small text-muted mb-1">Voucher ID</div>
                                    <code class="small">{{ $voucher->unique_token }}</code>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .rating-input .btn-check:checked ~ .star-label,
    .rating-input .btn-check:checked + .star-label,
    .rating-input .star-label:hover,
    .rating-input .star-label:hover ~ .star-label {
        background-color: var(--bs-warning) !important;
        border-color: var(--bs-warning) !important;
    }
    .rating-input .btn-check:checked ~ .star-label i,
    .rating-input .btn-check:checked + .star-label i,
    .rating-input .star-label:hover i,
    .rating-input .star-label:hover ~ .star-label i {
        color: white !important;
    }
</style>

<script>
    const CHUNK_SIZE = 512 * 1024; // 512KB

    document.getElementById('photo-input').addEventListener('change', async function(e) {
        const file = e.target.files[0];
        if (!file) return;

        // 7MB limit
        if (file.size > 7 * 1024 * 1024) {
            alert('The photo size must be 7MB or less.');
            this.value = '';
            return;
        }

        const previewContainer = document.querySelector('.photo-preview-container');
        const previewImg = previewContainer.querySelector('img');
        const uploadUi = document.querySelector('.upload-ui');
        const progressContainer = document.querySelector('.upload-progress');
        const progressBar = progressContainer.querySelector('.progress-bar');
        const percentText = progressContainer.querySelector('.percent');
        const hiddenPathInput = document.getElementById('final-photo-path');
        const saveBtn = document.getElementById('submit-save');

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
                percentText.innerText = percent;

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

    function copyShareLink() {
        var copyText = document.getElementById("share-link");
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(copyText.value);
        
        const btn = document.getElementById('copy-btn');
        const originalText = btn.innerText;
        btn.innerText = "Copied!";
        btn.classList.replace('btn-white', 'btn-success');
        setTimeout(() => {
            btn.innerText = originalText;
            btn.classList.replace('btn-success', 'btn-white');
        }, 2000);
    }
</script>
@endsection

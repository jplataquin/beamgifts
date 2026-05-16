@extends('layouts.app')

@section('title', 'Scan Voucher - Manager')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center text-center">
        <div class="col-md-6">
            <h1 class="h3 fw-bold mb-2 text-primary">Redeem Voucher</h1>
            <p class="text-muted mb-5">Branch: <strong>{{ Auth::guard('partner')->user()->branch->name }}</strong></p>
            
            <div class="card shadow-sm border-0 rounded-4 overflow-hidden mb-5">
                <div id="reader" style="width: 100%; min-height: 400px; background: #000;"></div>
            </div>

            <style>
                #reader {
                    border-radius: 1rem;
                    overflow: hidden;
                }
                #reader video {
                    object-fit: cover !important;
                }
                #reader__dashboard {
                    display: none !important;
                }
            </style>

            <div class="alert alert-info rounded-pill px-4 small">
                <i class="bi bi-info-circle me-2"></i> Position the voucher QR code within the frame to scan.
            </div>

            <a href="{{ route('manager.vouchers.transactions') }}" class="btn btn-light rounded-pill px-4 mt-4">View Transactions</a>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const onScanSuccess = (decodedText, decodedResult) => {
            if (window.html5QrCode) {
                window.html5QrCode.stop().then(() => {
                    let token = decodedText;
                    if (decodedText.includes('/v/')) {
                        const parts = decodedText.split('/v/');
                        token = parts[parts.length - 1];
                    }
                    
                    if (token) {
                        window.location.href = "{{ url('manager/scan') }}/" + token;
                    }
                }).catch(err => {
                    console.error("Failed to stop scanner", err);
                });
            }
        };

        const config = { fps: 10, qrbox: { width: 280, height: 280 } };
        window.html5QrCode = new Html5Qrcode("reader");
        
        window.html5QrCode.start({ facingMode: "environment" }, config, onScanSuccess)
            .catch(err => {
                console.error("Error starting scanner:", err);
                window.html5QrCode.start({ facingMode: "user" }, config, onScanSuccess);
            });
    });
</script>
@endpush
@endsection
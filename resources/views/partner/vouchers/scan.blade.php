@extends('layouts.app')

@section('title', 'Scan Voucher')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center text-center">
        <div class="col-md-6">
            <h1 class="h3 fw-bold mb-4 text-primary">Redeem Voucher</h1>
            <p class="text-muted mb-5">Position the voucher QR code within the frame to scan.</p>
            
            <div class="card shadow-sm border-0 rounded-4 overflow-hidden mb-5">
                <div id="reader" style="width: 100%; min-height: 400px; background: #000;"></div>
            </div>

            <div class="alert alert-info rounded-pill px-4 small">
                <i class="bi bi-info-circle me-2"></i> Ensure you have camera permissions enabled for this site.
            </div>

            <a href="{{ route('partner.vouchers.index') }}" class="btn btn-light rounded-pill px-4 mt-4">Back to List</a>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const onScanSuccess = (decodedText, decodedResult) => {
            // Stop scanning to prevent multiple triggers
            if (window.html5QrcodeScanner) {
                window.html5QrcodeScanner.clear();
            }
            
            // Extract TOKEN from URL (e.g. https://domain.com/v/TOKEN)
            try {
                let token = '';
                if (decodedText.includes('/v/')) {
                    const parts = decodedText.split('/v/');
                    token = parts[parts.length - 1];
                } else {
                    // Fallback if it's just the token
                    token = decodedText;
                }
                
                if (token) {
                    window.location.href = "{{ url('partner/vouchers/scan') }}/" + token;
                } else {
                    alert("Invalid QR code format.");
                    location.reload(); // Restart
                }
            } catch (e) {
                alert("Could not process QR code.");
                location.reload();
            }
        };

        window.html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", { fps: 10, qrbox: {width: 250, height: 250} }, false);
        window.html5QrcodeScanner.render(onScanSuccess);
    });
</script>
@endpush
@endsection

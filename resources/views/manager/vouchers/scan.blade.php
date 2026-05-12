@extends('layouts.app')

@section('title', 'Scan Voucher - Manager')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center text-center">
        <div class="col-md-6">
            <h1 class="h3 fw-bold mb-2 text-primary">Redeem Voucher</h1>
            <p class="text-muted mb-5">Branch: <strong>{{ Auth::guard('manager')->user()->branch->name }}</strong></p>
            
            <div class="card shadow-sm border-0 rounded-4 overflow-hidden mb-5">
                <div id="reader" style="width: 100%; min-height: 400px; background: #000;"></div>
            </div>

            <div class="alert alert-info rounded-pill px-4 small">
                <i class="bi bi-info-circle me-2"></i> Position the voucher QR code within the frame to scan.
            </div>

            <a href="{{ route('vouchers.transactions') }}" class="btn btn-light rounded-pill px-4 mt-4">View Transactions</a>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const onScanSuccess = (decodedText, decodedResult) => {
            if (window.html5QrcodeScanner) {
                window.html5QrcodeScanner.clear();
            }
            
            try {
                let token = '';
                if (decodedText.includes('/v/')) {
                    const parts = decodedText.split('/v/');
                    token = parts[parts.length - 1];
                } else {
                    token = decodedText;
                }
                
                if (token) {
                    window.location.href = "{{ url('manager/scan') }}/" + token;
                } else {
                    alert("Invalid QR code.");
                    location.reload();
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
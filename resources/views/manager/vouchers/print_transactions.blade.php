<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Transactions - {{ $branch->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #fff;
            color: #000;
            font-family: Arial, sans-serif;
            font-size: 14px;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            @page {
                size: auto;
                margin: 15mm;
            }
            body {
                margin: 0;
            }
        }
        .table th {
            background-color: #f8f9fa !important;
            -webkit-print-color-adjust: exact;
        }
    </style>
</head>
<body onload="window.print()">
    <div class="container-fluid py-4">
        
        <div class="d-flex justify-content-between align-items-start mb-4">
            <div>
                <h2 class="mb-1 fw-bold">{{ $store->name }}</h2>
                <h4 class="text-muted">{{ $branch->name }} - Transactions Report</h4>
                <div class="small text-muted mt-2">
                    Generated on: {{ now()->format('M d, Y h:i A') }}
                </div>
            </div>
            <div class="no-print">
                <button onclick="window.print()" class="btn btn-primary me-2">Print</button>
                <button onclick="window.close()" class="btn btn-outline-secondary">Close</button>
            </div>
        </div>

        @if(count($filters) > 0)
            <div class="mb-4">
                <strong>Filters Applied:</strong>
                <ul class="list-inline mb-0">
                    @foreach($filters as $key => $value)
                        <li class="list-inline-item me-3">
                            <span class="text-muted">{{ $key }}:</span> {{ $value }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Voucher ID</th>
                    <th>Product</th>
                    <th>Gifter</th>
                    <th>Claimed By (Customer)</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th class="text-end">Price</th>
                </tr>
            </thead>
            <tbody>
                @forelse($vouchers as $voucher)
                    <tr>
                        <td><code>#{{ str_pad($voucher->id, 6, '0', STR_PAD_LEFT) }}</code></td>
                        <td>{{ $voucher->product->name }}</td>
                        <td>{{ $voucher->order->gifter->name ?? 'N/A' }}</td>
                        <td>{{ $voucher->claimed_by ?? '-' }}</td>
                        <td>{{ ucfirst($voucher->status) }}</td>
                        <td>{{ $voucher->claimed_at ? $voucher->claimed_at->format('M d, Y h:i A') : $voucher->created_at->format('M d, Y') }}</td>
                        <td class="text-end">₱{{ number_format($voucher->price ?? $voucher->product->price, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">No transactions found matching the criteria.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="6" class="text-end fw-bold">Grand Total:</td>
                    <td class="text-end fw-bold">₱{{ number_format($grandTotal, 2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</body>
</html>
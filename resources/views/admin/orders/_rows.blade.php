@foreach($orders as $order)
    <tr onclick="window.location='{{ route('admin.orders.show', $order) }}'" style="cursor: pointer;">
        <td class="ps-4 fw-bold text-dark"><code>#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</code></td>
        <td>{{ $order->gifter->name ?? $order->gifter->email ?? 'Guest' }}</td>
        <td>
            @if($order->items->count() > 0 && $order->items->first()->product && $order->items->first()->product->store)
                <span class="badge bg-light text-dark rounded-pill">{{ $order->items->first()->product->store->name }}</span>
                @if($order->items->count() > 1)
                    <span class="badge bg-secondary rounded-pill small">+{{ $order->items->count() - 1 }}</span>
                @endif
            @else
                <span class="text-muted small">Unknown</span>
            @endif
        </td>
        <td class="text-muted">{{ $order->created_at->format('M d, Y h:i A') }}</td>
        <td class="fw-bold text-primary">₱{{ number_format($order->total_amount, 2) }}</td>
        <td>
            @if($order->status === 'paid')
                <span class="badge bg-success rounded-pill">Paid</span>
            @elseif($order->status === 'pending')
                <span class="badge bg-warning text-dark rounded-pill">Pending</span>
            @else
                <span class="badge bg-danger rounded-pill">{{ ucfirst($order->status) }}</span>
            @endif
        </td>
    </tr>
@endforeach
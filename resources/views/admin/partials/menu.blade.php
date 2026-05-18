<div class="card p-3 shadow-sm border-0">
    <div class="card-body p-0">
        <h5 class="fw-bold text-primary mb-4">Admin Menu</h5>
        <div class="list-group list-group-flush">
            <a href="{{ route('admin.dashboard') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.dashboard') ? 'active' : '' }} rounded-pill mb-1">Dashboard</a>
            <a href="{{ route('admin.partners.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.partners.*') ? 'active' : '' }} rounded-pill mb-1">Partners</a>
            <a href="{{ route('admin.cities.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.cities.*') ? 'active' : '' }} rounded-pill mb-1">Cities</a>
            <a href="{{ route('admin.categories.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.categories.*') ? 'active' : '' }} rounded-pill mb-1">Categories</a>
            <a href="{{ route('admin.products.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.products.*') ? 'active' : '' }} rounded-pill mb-1">Products</a>
            <a href="{{ route('admin.orders.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.orders.*') ? 'active' : '' }} rounded-pill mb-1 border-0">Orders</a>
            <a href="{{ route('admin.vouchers.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.vouchers.*') ? 'active' : '' }} rounded-pill mb-1 border-0">Vouchers</a>
            <a href="{{ route('admin.payouts.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.payouts.*') ? 'active' : '' }} rounded-pill mb-1 border-0">Payouts</a>
            <a href="{{ route('admin.settings.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.settings.*') ? 'active' : '' }} rounded-pill mb-1 border-0">Settings</a>
            <form action="{{ route('admin.logout') }}" method="POST" class="mt-3">
                @csrf
                <button type="submit" class="btn btn-outline-danger btn-sm w-100 rounded-pill">Logout</button>
            </form>
        </div>
    </div>
</div>

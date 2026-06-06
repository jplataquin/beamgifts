<div class="card p-2 p-md-3 shadow-sm border-0 mb-4" id="adminMenuCard">
    <div class="card-body p-0">
        <h5 class="fw-bold text-primary mb-4 d-none d-md-block ps-2">Admin Menu</h5>
        <div class="list-group list-group-flush d-flex flex-row flex-md-column flex-nowrap overflow-auto border-0 pb-1 pb-md-0 custom-scrollbar-hide" id="adminMenuScroll">
            <a href="{{ route('admin.dashboard') }}" 
               class="list-group-item list-group-item-action border-0 rounded-pill mb-md-1 flex-shrink-0 w-auto me-2 me-md-0 px-3 py-1 small text-center text-md-start {{ request()->routeIs('admin.dashboard') ? 'active shadow-sm' : '' }}">
               <i class="bi bi-speedometer2 me-md-2"></i> Dashboard
            </a>
            <a href="{{ route('admin.partners.index') }}" 
               class="list-group-item list-group-item-action border-0 rounded-pill mb-md-1 flex-shrink-0 w-auto me-2 me-md-0 px-3 py-1 small text-center text-md-start {{ request()->routeIs('admin.partners.*') ? 'active shadow-sm' : '' }}">
               <i class="bi bi-people me-md-2"></i> Partners
            </a>
            <a href="{{ route('admin.cities.index') }}" 
               class="list-group-item list-group-item-action border-0 rounded-pill mb-md-1 flex-shrink-0 w-auto me-2 me-md-0 px-3 py-1 small text-center text-md-start {{ request()->routeIs('admin.cities.*') ? 'active shadow-sm' : '' }}">
               <i class="bi bi-geo-alt me-md-2"></i> Cities
            </a>
            <a href="{{ route('admin.categories.index') }}" 
               class="list-group-item list-group-item-action border-0 rounded-pill mb-md-1 flex-shrink-0 w-auto me-2 me-md-0 px-3 py-1 small text-center text-md-start {{ request()->routeIs('admin.categories.*') ? 'active shadow-sm' : '' }}">
               <i class="bi bi-grid me-md-2"></i> Categories
            </a>
            <a href="{{ route('admin.products.index') }}" 
               class="list-group-item list-group-item-action border-0 rounded-pill mb-md-1 flex-shrink-0 w-auto me-2 me-md-0 px-3 py-1 small text-center text-md-start {{ request()->routeIs('admin.products.*') ? 'active shadow-sm' : '' }}">
               <i class="bi bi-box-seam me-md-2"></i> Products
            </a>
            <a href="{{ route('admin.orders.index') }}" 
               class="list-group-item list-group-item-action border-0 rounded-pill mb-md-1 flex-shrink-0 w-auto me-2 me-md-0 px-3 py-1 small text-center text-md-start {{ request()->routeIs('admin.orders.*') ? 'active shadow-sm' : '' }}">
               <i class="bi bi-cart-check me-md-2"></i> Orders
            </a>
            <a href="{{ route('admin.refunds.index') }}" 
               class="list-group-item list-group-item-action border-0 rounded-pill mb-md-1 flex-shrink-0 w-auto me-2 me-md-0 px-3 py-1 small text-center text-md-start {{ request()->routeIs('admin.refunds.*') ? 'active shadow-sm' : '' }}">
               <i class="bi bi-arrow-counterclockwise me-md-2"></i> Refunds
            </a>
            <a href="{{ route('admin.vouchers.index') }}" 
               class="list-group-item list-group-item-action border-0 rounded-pill mb-md-1 flex-shrink-0 w-auto me-2 me-md-0 px-3 py-1 small text-center text-md-start {{ request()->routeIs('admin.vouchers.*') ? 'active shadow-sm' : '' }}">
               <i class="bi bi-ticket-perforated me-md-2"></i> Vouchers
            </a>
            <a href="{{ route('admin.payouts.index') }}" 
               class="list-group-item list-group-item-action border-0 rounded-pill mb-md-1 flex-shrink-0 w-auto me-2 me-md-0 px-3 py-1 small text-center text-md-start {{ request()->routeIs('admin.payouts.*') ? 'active shadow-sm' : '' }}">
               <i class="bi bi-cash-stack me-md-2"></i> Payouts
            </a>
            <a href="{{ route('admin.settings.index') }}" 
               class="list-group-item list-group-item-action border-0 rounded-pill mb-md-1 flex-shrink-0 w-auto me-2 me-md-0 px-3 py-1 small text-center text-md-start {{ request()->routeIs('admin.settings.*') ? 'active shadow-sm' : '' }}">
               <i class="bi bi-gear me-md-2"></i> Settings
            </a>
            
            <form action="{{ route('admin.logout') }}" method="POST" class="d-none d-md-block mt-3">
                @csrf
                <button type="submit" class="btn btn-outline-danger btn-sm w-100 rounded-pill">Logout</button>
            </form>
        </div>
    </div>
</div>

<style>
    .custom-scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    .custom-scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const scrollContainer = document.getElementById('adminMenuScroll');
        if (!scrollContainer) return;

        const activePill = scrollContainer.querySelector('.active');
        let scrollTimeout;

        if (!activePill) return;

        // Initial scroll to active on load
        activePill.scrollIntoView({ behavior: 'auto', block: 'nearest', inline: 'center' });

        const checkAndScroll = () => {
            if (window.innerWidth >= 768) return; // Only for mobile

            const containerRect = scrollContainer.getBoundingClientRect();
            const pillRect = activePill.getBoundingClientRect();

            // Check if pill is outside the horizontal bounds of the container
            const isVisible = (
                pillRect.left >= containerRect.left && 
                pillRect.right <= containerRect.right
            );

            if (!isVisible) {
                activePill.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
            }
        };

        const resetTimer = () => {
            clearTimeout(scrollTimeout);
            scrollTimeout = setTimeout(checkAndScroll, 3000);
        };

        // Watch for scroll events to prevent auto-scrolling while user is interacting
        scrollContainer.addEventListener('scroll', resetTimer);
        
        // Start the initial timer
        resetTimer();
    });
</script>

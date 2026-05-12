<div class="card p-2 p-md-3 shadow-sm border-0 mb-4" id="partnerMenuCard">
    <div class="card-body p-0">
        <h5 class="fw-bold text-primary mb-4 d-none d-md-block ps-2">Partner Menu</h5>
        <div class="list-group list-group-flush d-flex flex-row flex-md-column flex-nowrap overflow-auto border-0 pb-1 pb-md-0 custom-scrollbar-hide" id="partnerMenuScroll">
            <a href="{{ route('partner.dashboard') }}" 
               class="list-group-item list-group-item-action border-0 rounded-pill mb-md-1 flex-shrink-0 w-auto me-2 me-md-0 px-3 py-1 small text-center text-md-start {{ request()->routeIs('partner.dashboard') ? 'active shadow-sm' : '' }}">
               <i class="bi bi-speedometer2 me-md-2"></i> Dashboard
            </a>
            <a href="{{ route('partner.store.show') }}" 
               class="list-group-item list-group-item-action border-0 rounded-pill mb-md-1 flex-shrink-0 w-auto me-2 me-md-0 px-3 py-1 small text-center text-md-start {{ request()->routeIs('partner.store.*') || request()->routeIs('partner.stores.*') ? 'active shadow-sm' : '' }}">
               <i class="bi bi-shop me-md-2"></i> My Store
            </a>
            <a href="{{ route('partner.branches.index') }}" 
               class="list-group-item list-group-item-action border-0 rounded-pill mb-md-1 flex-shrink-0 w-auto me-2 me-md-0 px-3 py-1 small text-center text-md-start {{ request()->routeIs('partner.branches.*') ? 'active shadow-sm' : '' }}">
               <i class="bi bi-geo-alt me-md-2"></i> Branches
            </a>
            <a href="{{ route('partner.products.index') }}" 
               class="list-group-item list-group-item-action border-0 rounded-pill mb-md-1 flex-shrink-0 w-auto me-2 me-md-0 px-3 py-1 small text-center text-md-start {{ request()->routeIs('partner.products.*') ? 'active shadow-sm' : '' }}">
               <i class="bi bi-box-seam me-md-2"></i> Products
            </a>
            <a href="{{ route('partner.vouchers.index') }}" 
               class="list-group-item list-group-item-action border-0 rounded-pill mb-md-1 flex-shrink-0 w-auto me-2 me-md-0 px-3 py-1 small text-center text-md-start {{ request()->routeIs('partner.vouchers.*') ? 'active shadow-sm' : '' }}">
               <i class="bi bi-ticket-perforated me-md-2"></i> Vouchers
            </a>
            <a href="{{ route('partner.managers.index') }}" 
               class="list-group-item list-group-item-action border-0 rounded-pill mb-md-1 flex-shrink-0 w-auto me-2 me-md-0 px-3 py-1 small text-center text-md-start {{ request()->routeIs('partner.managers.*') ? 'active shadow-sm' : '' }}">
               <i class="bi bi-people me-md-2"></i> Managers
            </a>
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
        const scrollContainer = document.getElementById('partnerMenuScroll');
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

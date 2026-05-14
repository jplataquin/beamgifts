<div class="card p-2 p-md-3 shadow-sm border-0 mb-4" id="accountMenuCard">
    <div class="card-body p-0">
        <h5 class="fw-bold text-primary mb-4 d-none d-md-block ps-2">Account Menu</h5>
        <div class="list-group list-group-flush d-flex flex-row flex-md-column flex-nowrap overflow-auto border-0 pb-1 pb-md-0 custom-scrollbar-hide" id="accountMenuScroll">
            <a href="{{ route('profile') }}" 
               class="list-group-item list-group-item-action border-0 rounded-pill mb-md-1 flex-shrink-0 w-auto me-2 me-md-0 px-3 py-1 small text-center text-md-start {{ request()->routeIs('profile') ? 'active shadow-sm' : '' }}">
               <i class="bi bi-person me-md-2"></i> My Profile
            </a>
            <a href="{{ route('my-gifts') }}" 
               class="list-group-item list-group-item-action border-0 rounded-pill mb-md-1 flex-shrink-0 w-auto me-2 me-md-0 px-3 py-1 small text-center text-md-start {{ request()->routeIs('my-gifts') || request()->routeIs('vouchers.manage') ? 'active shadow-sm' : '' }}">
               <i class="bi bi-gift me-md-2"></i> My Gifts
            </a>
            <a href="{{ route('my-orders') }}" 
               class="list-group-item list-group-item-action border-0 rounded-pill mb-md-1 flex-shrink-0 w-auto me-2 me-md-0 px-3 py-1 small text-center text-md-start {{ request()->routeIs('my-orders') || request()->routeIs('my-orders.show') ? 'active shadow-sm' : '' }}">
               <i class="bi bi-clock-history me-md-2"></i> Order History
            </a>
            
            <form action="{{ route('logout') }}" method="POST" class="ms-auto ms-md-0 mt-md-3 flex-shrink-0">
                @csrf
                <button type="submit" class="list-group-item list-group-item-action border-0 rounded-pill px-3 py-1 small text-center text-md-start text-danger w-auto w-md-100">
                    <i class="bi bi-box-arrow-right me-md-2"></i> Logout
                </button>
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
        const scrollContainer = document.getElementById('accountMenuScroll');
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

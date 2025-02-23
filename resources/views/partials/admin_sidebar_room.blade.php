<aside id="sidebar" class="expand">
    <div class="d-flex" id="title">
        <button class="toggle-btn" type="button">
            <i class="lni lni-grid-alt mt-2"></i>
        </button>
        <div class="sidebar-logo">
            <a href="#" class="mt-3">BLC Delivery</a>
        </div>
    </div>
    <ul class="sidebar-nav">
        <li class="sidebar-item">
            <a href="/lobby/{{ $room->room_id }}" class="sidebar-link">
                <i class="lni lni-user"></i>
                <span>Manage Player</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="/lobby/{{ $room->room_id }}/settingPinjaman" class="sidebar-link">
                <i class="bi bi-credit-card"></i>
                <span>Loan</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="#" class="sidebar-link has-dropdown">
                <i class="lni lni-protection"></i>
                <span>Delivery</span>
            </a>
            <ul id="auth" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                <li class="sidebar-item">
                    <a href="/lobby/{{ $room->room_id }}/settingPengirimanLCL" class="sidebar-link" style="text-align: center"><i class="bi bi-water"></i>Less Container Load</a>
                </li>
                <li class="sidebar-item">
                    <a href="/lobby/{{ $room->room_id }}/settingPengirimanFCL" class="sidebar-link" style="text-align: center"><i class="bi bi-box-fill"></i>Full Container Load</a>
                </li>
                <li class="sidebar-item">
                    <a href="/lobby/{{ $room->room_id }}/settingPengirimanUdara" class="sidebar-link" style="text-align: center"><i class="bi bi-airplane-engines"></i>Air Delivery</a>
                </li>
            </ul>
        </li>

        <li class="sidebar-item">
            <a href="/lobby/{{ $room->room_id }}/settingBahanBaku" class="sidebar-link">
                <i class="bi bi-capsule"></i>
                <span>Raw Items</span>
            </a>
        </li>

        <li class="sidebar-item">
            <a href="/lobby/{{ $room->room_id }}/demandInformation" class="sidebar-link">
                <i class="bi bi-capsule"></i>
                <span>Demand Information</span>
            </a>
        </li>
        
        <li class="sidebar-item">
            <a href="/lobby/{{ $room->room_id }}/playerScore" class="sidebar-link">
                <i class="lni lni-cog"></i>
                <span>Result</span>
            </a>
        </li>
    </ul>
    <div class="sidebar-footer">
        <a href="/homeAdmin" class="sidebar-link">
            <i class="lni lni-exit"></i>
            <span>Exit Room</span>
        </a>
    </div>
</aside>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebarLinks = document.querySelectorAll('.sidebar-link');
    
    // Get current path
    const currentPath = window.location.pathname;

    sidebarLinks.forEach(link => {
        // Remove any existing active class
        link.classList.remove('active');
        
        // Add active class if href matches current path
        if (link.getAttribute('href') === currentPath) {
            link.classList.add('active');
        }

        // Add click handler
        link.addEventListener('click', function(e) {
            // Skip for dropdown toggles
            if (this.classList.contains('has-dropdown')) {
                return;
            }
            
            // Remove active class from all links
            sidebarLinks.forEach(l => l.classList.remove('active'));
            
            // Add active class to clicked link
            this.classList.add('active');
            
            // Store active state in localStorage
            localStorage.setItem('activeLink', this.getAttribute('href'));
        });
    });

    // Restore active state from localStorage
    const storedActiveLink = localStorage.getItem('activeLink');
    if (storedActiveLink) {
        const activeLink = document.querySelector(`a[href="${storedActiveLink}"]`);
        if (activeLink) {
            activeLink.classList.add('active');
        }
    }
});
</script>
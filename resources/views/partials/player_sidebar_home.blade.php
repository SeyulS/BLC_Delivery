<aside id="sidebar">
    {{-- Logo Section --}}
    <div class="sidebar-logo">
        <button class="toggle-btn" type="button" aria-label="Toggle Sidebar">
            <i class="bi bi-chevron-left"></i>
        </button>
        <a href="/blc-delivery">BLC Delivery</a>
    </div>

    {{-- Player Profile Section --}}
    <div class="player-profile">
        <div class="profile-info">
            <i class="bi bi-calendar"></i>
            <p>{{ $room->recent_day }}</p>
        </div>
        <div class="profile-info">
            <i class="bi bi-person"></i>
            <p>{{ $player->player_username }}</p>
        </div>
        <div class="profile-info">
            <i class="bi bi-cash-stack"></i>
            <p>{{ $player->revenue }}</p>
        </div>
    </div>

    {{-- Navigation Menu --}}
    <ul class="sidebar-nav">
        <li class="sidebar-item">
            <a href="/blc-delivery/player-lobby/{{ $room->room_id }}" 
               class="sidebar-link {{ Request::is('player-lobby/'.$room->room_id) ? 'active' : '' }}">
                <i class="bi bi-door-closed"></i>
                <span>Room Profile</span>
            </a>
        </li>

        <li class="sidebar-item">
            <a href="/blc-delivery/player-lobby/{{ $room->room_id }}/calendar" 
               class="sidebar-link {{ Request::is('player-lobby/'.$room->room_id.'/calendar') ? 'active' : '' }}">
                <i class="bi bi-calendar"></i>
                <span>Calendar</span>
            </a>
        </li>

        <li class="sidebar-item">
            <a href="/blc-delivery/player-lobby/{{ $room->room_id }}/playerProfile" 
               class="sidebar-link {{ $room->status == 1 ? 'able' : 'disabled' }} 
                              {{ Request::is('player-lobby/'.$room->room_id.'/playerProfile') ? 'active' : '' }}">
                <i class="bi bi-box"></i>
                <span>Financial & Inventory</span>
            </a>
        </li>

        <li class="sidebar-item">
            <a href="/blc-delivery/player-lobby/{{ $room->room_id }}/warehouseMachine" 
               class="sidebar-link {{ $room->status == 1 ? 'able' : 'disabled' }} 
                              {{ Request::is('player-lobby/'.$room->room_id.'/warehouseMachine') ? 'active' : '' }}">
                <i class="bi bi-cart"></i>
                <span>Purchasing</span>
            </a>
        </li>

        <li class="sidebar-item">
            <a href="/blc-delivery/player-lobby/{{ $room->room_id }}/production" 
               class="sidebar-link {{ ($room->status == 1 && $player->produce == 1) ? 'able' : 'disabled' }} 
                              {{ Request::is('player-lobby/'.$room->room_id.'/production') ? 'active' : '' }}">
                <i class="bi bi-hammer"></i>
                <span>Production</span>
            </a>
        </li>

        <li class="sidebar-item">
            <a href="/blc-delivery/player-lobby/{{ $room->room_id }}/marketIntelligence" 
               class="sidebar-link {{ Request::is('player-lobby/'.$room->room_id.'/marketIntelligence') ? 'active' : '' }}">
                <i class="bi bi-book"></i>
                <span>Market Intelligence</span>
            </a>
        </li>

        <li class="sidebar-item">
            <a href="/blc-delivery/player-lobby/{{ $room->room_id }}/listOfDemands" 
               class="sidebar-link {{ $room->status == 1 ? 'able' : 'disabled' }} 
                              {{ Request::is('player-lobby/'.$room->room_id.'/listOfDemands') ? 'active' : '' }}">
                <i class="bi bi-file-text"></i>
                <span>Demands</span>
            </a>
        </li>

        <li class="sidebar-item">
            <a href="/blc-delivery/player-lobby/{{ $room->room_id }}/payingOffDebt" 
               class="sidebar-link {{ $room->status == 1 ? 'able' : 'disabled' }} 
                              {{ Request::is('player-lobby/'.$room->room_id.'/payingOffDebt') ? 'active' : '' }}">
                <i class="bi bi-credit-card"></i>
                <span>Paying off Debt</span>
            </a>
        </li>
    </ul>
</aside>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.querySelector('.toggle-btn');
    const main = document.querySelector('.main');
    const sidebarLinks = document.querySelectorAll('.sidebar-link');
    let overlay;

    // Create overlay for mobile
    function createOverlay() {
        if (!overlay) {
            overlay = document.createElement('div');
            overlay.className = 'sidebar-overlay';
            document.body.appendChild(overlay);

            overlay.addEventListener('click', () => {
                closeSidebar();
            });
        }
    }

    // Open sidebar function
    function openSidebar() {
        sidebar.classList.add('expand');
        if (window.innerWidth <= 768) {
            overlay.style.display = 'block';
            setTimeout(() => {
                overlay.style.opacity = '1';
            }, 10);
        }
    }

    // Close sidebar function
    function closeSidebar() {
        sidebar.classList.remove('expand');
        if (overlay) {
            overlay.style.opacity = '0';
            setTimeout(() => {
                overlay.style.display = 'none';
            }, 300);
        }
    }

    // Toggle sidebar with animation
    toggleBtn.addEventListener('click', (e) => {
        e.preventDefault();
        
        // Add click effect to button
        toggleBtn.style.transform = 'scale(0.95)';
        setTimeout(() => {
            toggleBtn.style.transform = 'scale(1)';
        }, 150);

        if (sidebar.classList.contains('expand')) {
            closeSidebar();
        } else {
            openSidebar();
        }
    });

    // Handle window resize
    function handleResize() {
        if (window.innerWidth <= 768) {
            createOverlay();
            main.style.marginLeft = '0';
        } else {
            if (overlay) {
                overlay.remove();
                overlay = null;
            }
            main.style.marginLeft = sidebar.classList.contains('expand') ? 
                'var(--sidebar-width)' : 'var(--collapsed-width)';
        }
    }

    // Add animation to menu items
    sidebarLinks.forEach((link, index) => {
        link.style.transitionDelay = `${index * 0.05}s`;
        
        // Remove transition delay after animation
        setTimeout(() => {
            link.style.transitionDelay = '0s';
        }, 500);
        
        if (link.getAttribute('href') === window.location.pathname) {
            link.classList.add('active');
        }
    });

    // Handle hover effects for collapsed state
    if (!sidebar.classList.contains('expand')) {
        sidebarLinks.forEach(link => {
            link.addEventListener('mouseenter', function() {
                const tooltip = document.createElement('div');
                tooltip.className = 'sidebar-tooltip';
                tooltip.textContent = this.querySelector('span').textContent;
                tooltip.style.cssText = `
                    position: fixed;
                    background: var(--sidebar-bg);
                    color: white;
                    padding: 5px 10px;
                    border-radius: 4px;
                    font-size: 12px;
                    z-index: 1001;
                    pointer-events: none;
                `;
                document.body.appendChild(tooltip);
                
                const linkRect = this.getBoundingClientRect();
                tooltip.style.top = linkRect.top + (linkRect.height - tooltip.offsetHeight) / 2 + 'px';
                tooltip.style.left = linkRect.right + 10 + 'px';
                
                this.addEventListener('mouseleave', () => tooltip.remove());
            });
        });
    }

    // Initialize
    handleResize();
    window.addEventListener('resize', handleResize);

    // Save and restore sidebar state
    function saveSidebarState() {
        localStorage.setItem('sidebarExpanded', sidebar.classList.contains('expand'));
    }

    function restoreSidebarState() {
        if (localStorage.getItem('sidebarExpanded') === 'true') {
            sidebar.classList.add('expand');
        }
    }

    toggleBtn.addEventListener('click', saveSidebarState);
    
    // Restore state with animation
    setTimeout(restoreSidebarState, 100);
});
</script>
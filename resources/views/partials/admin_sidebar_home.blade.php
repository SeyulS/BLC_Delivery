<aside id="sidebar" class="expand">
    <!-- Sidebar Header -->
    <div class="d-flex" id="title">
        <button class="toggle-btn" type="button">
            <i class="lni lni-grid-alt mt-2"></i>
        </button>
        <div class="sidebar-logo">
            <a href="#" class="mt-3">BLC Delivery</a>
        </div>
    </div>

    <!-- Admin Profile Section with transition -->
    <!-- Admin Profile Section -->
    <div class="sidebar-profile">
        <div class="profile-wrapper">
            <div class="profile-image">
                <i class="lni lni-user"></i>
            </div>
            <div class="profile-info">
                <h6 class="admin-name">{{ $administrator->admin_username }}</h6>
                <span class="admin-role {{ $administrator->super_admin == 1 ? 'super-admin' : 'regular-admin' }}">
                    @if($administrator->super_admin == 1)
                    Super Administrator
                    @else
                    Administrator
                    @endif
                </span>
            </div>
        </div>
    </div>

    <!-- Navigation Menu -->
    <ul class="sidebar-nav">
        <li class="sidebar-item">
            <a href="/blc-delivery/manageAdmin" class="sidebar-link">
                <i class="lni lni-user"></i>
                <span>Manage Admin</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="/blc-delivery/manageAccount" class="sidebar-link">
                <i class="lni lni-user"></i>
                <span>Manage Account</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="/blc-delivery/homeAdmin" class="sidebar-link">
                <i class="lni lni-agenda"></i>
                <span>Manage Room</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="/blc-delivery/manageData" class="sidebar-link">
                <i class="lni lni-agenda"></i>
                <span>Manage Data</span>
            </a>
        </li>
    </ul>

    <!-- Sidebar Footer -->
    <div class="sidebar-footer">
        <a href="/blc-delivery/logoutAdmin" class="sidebar-link">
            <i class="lni lni-exit"></i>
            <span>Logout</span>
        </a>
    </div>
</aside>
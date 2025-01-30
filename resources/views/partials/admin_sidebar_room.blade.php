<aside id="sidebar" class="expand">
    <div class="d-flex">
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
                <i class="lni lni-agenda"></i>
                <span>Pinjaman</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="#" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse"
                data-bs-target="#auth" aria-expanded="false" aria-controls="auth">
                <i class="lni lni-protection"></i>
                <span>Pengiriman</span>
            </a>
            <ul id="auth" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                <li class="sidebar-item">
                    <a href="/lobby/{{ $room->room_id }}/settingPengirimanLCL" class="sidebar-link" style="text-align: left">Pengiriman LCL</a>
                </li>
                <li class="sidebar-item">
                    <a href="/lobby/{{ $room->room_id }}/settingPengirimanFCL" class="sidebar-link" style="text-align: left">Pengiriman FCL</a>
                </li>
                <li class="sidebar-item">
                    <a href="/lobby/{{ $room->room_id }}/settingPengirimanFCL" class="sidebar-link" style="text-align: left">Pengiriman Udara</a>
                </li>
            </ul>
        </li>
        <li class="sidebar-item">
            <a href="/lobby/{{ $room->room_id }}/settingBahanBaku" class="sidebar-link">
                <i class="lni lni-user"></i>
                <span>Bahan Baku</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="#" class="sidebar-link">
                <i class="lni lni-cog"></i>
                <span>Setting</span>
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
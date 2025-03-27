<aside id="sidebar" class="expand">
    <div class="d-flex">

        <div class="d-flex header-container">
            <button class="toggle-btn" type="button">
                <i class="lni lni-grid-alt"></i>
            </button>
            <div class="sidebar-logo">
                <span class="hide-menu">Hide this bar</span>
            </div>
        </div>

    </div>

    <div class="player-profile-container">
        <div class="player-profile">
            <div class="profile-item">
                <i class="bi bi-calendar"></i>
                <div>
                    <span class="label">Current Day</span>
                    <span class="value">Day {{ $room->recent_day }}</span>
                </div>
            </div>
            <div class="profile-item">
                <i class="bi bi-person"></i>
                <div>
                    <span class="label">Username</span>
                    <span class="value">{{ $player->player_username }}</span>
                </div>
            </div>
            <div class="profile-item">
                <i class="bi bi-cash-stack"></i>
                <div>
                    <span class="label">Cash</span>
                    <span class="value" id="sidebar_revenue">${{ number_format($player->revenue, 2, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    <ul class="sidebar-nav">
        <!-- Information Group -->
        <li class="sidebar-header">Information</li>
        <li class="sidebar-item sidebar-subitem">
            <a href="/player-lobby/{{ $room->room_id }}"
                class="sidebar-link {{ Request::is('player-lobby/'.$room->room_id) ? 'active' : '' }}">
                <i class="bi bi-door-closed"></i>
                <span>Room Profile</span>
            </a>
        </li>
        <li class="sidebar-item sidebar-subitem">
            <a href="/player-lobby/{{ $room->room_id }}/calendar"
                class="sidebar-link {{ Request::is('player-lobby/'.$room->room_id.'/calendar') ? 'active' : '' }}">
                <i class="bi bi-calendar"></i>
                <span>Calendar</span>
            </a>
        </li>
        <li class="sidebar-item sidebar-subitem">
            <a href="/player-lobby/{{ $room->room_id }}/marketIntelligence"
                class="sidebar-link {{ Request::is('player-lobby/'.$room->room_id.'/marketIntelligence') ? 'active' : '' }}">
                <i class="bi bi-book"></i>
                <span>Market Intelligence</span>
            </a>
        </li>

        <!-- Finance Group -->
        <li class="sidebar-header">Finance</li>
        <li class="sidebar-item sidebar-subitem">
            <a href="/player-lobby/{{ $room->room_id }}/payingOffDebt"
                class="sidebar-link {{ $room->status == 1 ? 'able' : 'disabled' }} 
               {{ Request::is('player-lobby/'.$room->room_id.'/payingOffDebt') ? 'active' : '' }}">
                <i class="bi bi-file-text"></i>
                <span>Paying off Debt</span>
            </a>
        </li>
        <li class="sidebar-item sidebar-subitem">
            <a href="/player-lobby/{{ $room->room_id }}/listOfDemands"
                class="sidebar-link {{ $room->status == 1 ? 'able' : 'disabled' }} 
               {{ Request::is('player-lobby/'.$room->room_id.'/listOfDemands') ? 'active' : '' }}">
                <i class="bi bi-file-text"></i>
                <span>Demands</span>
            </a>
        </li>

        <!-- Production Warehouse Group -->
        <li class="sidebar-header">Production Warehouse</li>
        <li class="sidebar-item sidebar-subitem">
            <a href="/player-lobby/{{ $room->room_id }}/playerProfile"
                class="sidebar-link
               {{ Request::is('player-lobby/'.$room->room_id.'/playerProfile') ? 'active' : '' }}">
                <i class="bi bi-box"></i>
                <span>Financial & Inventory</span>
            </a>
        </li>
        <li class="sidebar-item sidebar-subitem">
            <a href="/player-lobby/{{ $room->room_id }}/warehouseMachine"
                class="sidebar-link {{ $room->status == 1 ? 'able' : 'disabled' }}
               {{ Request::is('player-lobby/'.$room->room_id.'/warehouseMachine') ? 'active' : '' }}">
                <i class="bi bi-cart"></i>
                <span>Purchase</span>
            </a>
        </li>
        <li class="sidebar-item sidebar-subitem">
            <a href="/player-lobby/{{ $room->room_id }}/production" class="sidebar-link {{ $room->status == 1 ? 'able' : 'disabled' }}
            {{ Request::is('player-lobby/'.$room->room_id.'/production') ? 'active' : '' }}">
                <i class="bi bi-hammer"></i>
                <span>Production</span>
            </a>
        </li>

        <li class="sidebar-header">History</li>
        <li class="sidebar-item sidebar-subitem">
            <a href="/player-lobby/{{ $room->room_id }}/deliveredDemand" class="sidebar-link
            {{ Request::is('player-lobby/'.$room->room_id.'/deliveredDemand') ? 'active' : '' }}">
                <i class="bi bi-journal-text"></i>
                <span>Delivered Demand</span>
            </a>
        </li>
        <li class="sidebar-item sidebar-subitem">
            <a href="/player-lobby/{{ $room->room_id }}/purchasedRawItems" class="sidebar-link 
            {{ Request::is('player-lobby/'.$room->room_id.'/purchasedRawItems') ? 'active' : '' }}">
                <i class="bi bi-list-nested"></i>
                <span>Purchased Raw Item</span>
            </a>
        </li>
        <li class="sidebar-item sidebar-subitem">
            <a href="/player-lobby/{{ $room->room_id }}/transactionReceipt" class="sidebar-link
            {{ Request::is('player-lobby/'.$room->room_id.'/transactionReceipt') ? 'active' : '' }}">
                <i class="bi bi-receipt"></i>
                <span>Transaction Receipt</span>
            </a>
        </li>

    </ul>
</aside>
<aside id="sidebar" class="expand">
    <div class="d-flex">
        <button class="toggle-btn" type="button">
            <i class="lni lni-grid-alt mt-2"></i>
        </button>
        <div class="sidebar-logo">
            <a href="/" class="mt-3">BLC Delivery</a>
        </div>
    </div>

    <div class="d-flex mt-4">
        <div class="player-profile w-100">
            <hr>
            <div class="row mt-3">
                <div class="col-md-4">
                    <p class="ms-5"><i class="bi bi-calendar"></i></p>
                </div>
                <div class="col-md-4" id="player_name">
                    <p class="me-7">{{ $room->recent_day }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <p class="ms-5"><i class="bi bi-person"></i></p>
                </div>
                <div class="col-md-4" id="player_name">
                    <p class="me-7">{{ $player->player_username }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <p class="ms-5"><i class="bi bi-cash-stack"></i></p>
                </div>
                <div class="col-md-4" id="player_revenue">
                    <p class="me-7">{{ $player->revenue }}</p>
                </div>
            </div>
            <hr>
        </div>
    </div>

    <ul class="sidebar-nav mt-4">
        <li class="sidebar-item">
            <a href="/player-lobby/{{ $room->room_id }}"
                class="sidebar-link {{ Request::is('player-lobby/'.$room->room_id) ? 'active' : '' }}">
                <i class="bi bi-door-closed"></i>
                <span>Room Profile</span>
            </a>
        </li>

        <li class="sidebar-item">
            <a href="/player-lobby/{{ $room->room_id }}/calendar"
                class="sidebar-link {{ Request::is('player-lobby/'.$room->room_id.'/calendar') ? 'active' : '' }}">
                <i class="bi bi-calendar"></i>
                <span>Calendar</span>
            </a>
        </li>

        <li class="sidebar-item">
            <a href="/player-lobby/{{ $room->room_id }}/playerProfile"
                class="sidebar-link {{ $room->status == 1 ? 'able' : 'disabled' }}
               {{ Request::is('player-lobby/'.$room->room_id.'/playerProfile') ? 'active' : '' }}">
                <i class="bi bi-box"></i>
                <span>Financial & Inventory</span>
            </a>
        </li>

        <li class="sidebar-item">
            <a href="/player-lobby/{{ $room->room_id }}/warehouseMachine"
                class="sidebar-link {{ $room->status == 1 ? 'able' : 'disabled' }} 
               {{ Request::is('player-lobby/'.$room->room_id.'/warehouseMachine') ? 'active' : '' }}">
                <i class="bi bi-cart"></i>
                <span>Purchasing</span>
            </a>
        </li>

        <li class="sidebar-item">
            @if($room->status == 1)
            @if($player->produce == 1)
            <a href="/player-lobby/{{ $room->room_id }}/production"
                class="sidebar-link able {{ Request::is('player-lobby/'.$room->room_id.'/production') ? 'active' : '' }}">
                <i class="bi bi-hammer"></i>
                <span>Production</span>
            </a>
            @else
            <a href="/player-lobby/{{ $room->room_id }}/production" class="sidebar-link disabled">
                <i class="bi bi-hammer"></i>
                <span>Production</span>
            </a>
            @endif
            @else
            <a href="/player-lobby/{{ $room->room_id }}/production" class="sidebar-link disabled">
                <i class="bi bi-hammer"></i>
                <span>Production</span>
            </a>
            @endif
        </li>

        <li class="sidebar-item">
            <a href="/player-lobby/{{ $room->room_id }}/marketIntelligence"
                class="sidebar-link {{ Request::is('player-lobby/'.$room->room_id.'/marketIntelligence') ? 'active' : '' }}">
                <i class="bi bi-book"></i>
                <span>Market Intelligence</span>
            </a>
        </li>

        <li class="sidebar-item">
            <a href="/player-lobby/{{ $room->room_id }}/listOfDemands"
                class="sidebar-link {{ $room->status == 1 ? 'able' : 'disabled' }} 
               {{ Request::is('player-lobby/'.$room->room_id.'/listOfDemands') ? 'active' : '' }}">
                <i class="bi bi-file-text"></i>
                <span>Demands</span>
            </a>
        </li>

        <li class="sidebar-item">
            <a href="/player-lobby/{{ $room->room_id }}/payingOffDebt"
                class="sidebar-link {{ $room->status == 1 ? 'able' : 'disabled' }} 
               {{ Request::is('player-lobby/'.$room->room_id.'/payingOffDebt') ? 'active' : '' }}">
                <i class="bi bi-file-text"></i>
                <span>Paying off Debt</span>
            </a>
        </li>
    </ul>
</aside>
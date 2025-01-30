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
            <div class="row">
                <div class="col-md-4">
                    <p class="ms-5"><i class="bi bi-box"></i></p>
                </div>
                <div class="col-md-4" id="player_inventory">
                    <p class="me-7">{{ $player->inventory }}</p>
                </div>
            </div>
            <hr>
        </div>
    </div>


    <ul class="sidebar-nav mt-4">
        <li class="sidebar-item">
            <a href="/player-lobby/{{ $room->room_id }}" class="sidebar-link">
                <i class="bi bi-door-closed"></i>
                <span>Room Profile</span>
            </a>
        </li>

        <li class="sidebar-item">
            <a href="/player-lobby/{{ $room->room_id }}/playerProfile" class="sidebar-link {{ $room->status == 1 ? 'able' : 'disabled' }}">
                <i class="bi bi-box"></i>
                <span>Inventory</span>
            </a>
        </li>

        <li class="sidebar-item">
            <a href="/player-lobby/{{  $room->room_id }}/warehouseMachine" class="sidebar-link {{ $room->status == 1 ? 'able' : 'disabled' }}">
                <i class="bi bi-cart"></i>
                <span>Warehouse & Machine</span>
            </a>
        </li>

        <li class="sidebar-item">
            @if($room->status == 1)
            @if($player->produce == 1)
            <a href="/player-lobby/{{ $room->room_id }}/production" class="sidebar-link able">
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
            <a href="/player-lobby/{{ $room->room_id }}/listOfDemands" class="sidebar-link {{ $room->status == 1 ? 'able' : 'disabled' }}">
                <i class="lni bi-file-text"></i>
                <span>List Of Demands</span>
            </a>
        </li>

        <li class="sidebar-item">
            <a href="/test" class="sidebar-link {{ $room->status == 1 ? 'able' : 'disabled' }}">
                <i class="lni lni-agenda"></i>
                <span>Market Intelligence</span>
            </a>
        </li>

        <li class="sidebar-item">
            <a href="/test" class="sidebar-link {{ $room->status == 1 ? 'able' : 'disabled' }}">
                <i class="lni lni-agenda"></i>
                <span>Leaderboard</span>
            </a>
        </li>
    </ul>

</aside>
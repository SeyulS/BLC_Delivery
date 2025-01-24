<aside id="sidebar" class="expand">
    <div class="d-flex">
        <button class="toggle-btn" type="button">
            <i class="lni lni-grid-alt mt-2"></i>
        </button>
        <div class="sidebar-logo">
            <a href="/" class="mt-3">BLC Delivery</a>
        </div>
    </div>
    <ul class="sidebar-nav">
        <li class="sidebar-item">
            <a href="/player-lobby/{{ $room->room_id }}" class="sidebar-link">
                <i class="lni lni-user"></i>
                <span>Room Profile</span>
            </a>
        </li>

        <li class="sidebar-item">
            <a href="/player-lobby/{{ $room->room_id }}/playerProfile" class="sidebar-link {{ $room->status == 1 ? 'able' : 'disabled' }}">
                <i class="lni lni-user"></i>
                <span>Player Profile</span>
            </a>
        </li>

        <li class="sidebar-item">
            <a href="/player-lobby/{{  $room->room_id }}/warehouseMachine" class="sidebar-link {{ $room->status == 1 ? 'able' : 'disabled' }}">
                <i class="lni lni-agenda"></i>
                <span>Warehouse & Machine</span>
            </a>
        </li>

        <li class="sidebar-item">
            @if($room->status == 1)
            @if($player->produce == 1)
            <a href="/player-lobby/{{ $room->room_id }}/production" class="sidebar-link able">
                <i class="lni lni-agenda"></i>
                <span>Production</span>
            </a>
            @else
            <a href="/player-lobby/{{ $room->room_id }}/production" class="sidebar-link disabled">
                <i class="lni lni-agenda"></i>
                <span>Production</span>
            </a>
            @endif
            @else
            <a href="/player-lobby/{{ $room->room_id }}/production" class="sidebar-link disabled">
                <i class="lni lni-agenda"></i>
                <span>Production</span>
            </a>
            @endif

        </li>


        <li class="sidebar-item">
            <a href="/test" class="sidebar-link {{ $room->status == 1 ? 'able' : 'disabled' }}">
                <i class="lni lni-agenda"></i>
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
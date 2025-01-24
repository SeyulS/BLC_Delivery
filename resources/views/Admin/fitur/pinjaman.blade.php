@extends('layout.admin_room')

@section('script')
<!-- Load jQuery terlebih dahulu -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Load Select2 setelah jQuery -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endsection

@section('container')
<div class="container mt-4">
@if(session('success'))
    <script>
        $(document).ready(function() {
            toastr.success('{{ session("success") }}');
        });
    </script>
@endif

@if(session('fail'))
    <script>
        $(document).ready(function() {
            toastr.error('{{ session("fail") }}');
        });
    </script>
@endif

    <h3>Pinjaman</h3>
    <br>
    <div class="container">
        <form action="/set_pinjaman" method="POST" id="pinjaman-form">
            @csrf
            <input type="hidden" name="room_id" value="{{ $room->room_id }}">
            <div class="row">
                <div class="col-md-4">
                    <select class="form-select form-select-lg mb-3" id="team-select" name="team" aria-label="Large select example">
                        <option value="" selected disabled>Select Team</option>
                        @foreach($players as $player)
                            <option value="{{ $player->player_username }}">{{ $player->player_username }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <select class="form-select form-select-lg mb-3" id="pinjaman-select" name="pinjaman" aria-label="Large select example">
                        <option value="" selected disabled>Select Pinjaman</option>
                        @foreach($pinjaman as $p)
                            <option value="{{ $p->pinjaman_id }}">{{ $p->pinjaman_id }} | {{ $p->pinjaman_value }} | {{ $p->lama_pinjaman }} days | {{ $p->bunga_pinjaman }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <br>
            <button type="submit" class="btn btn-danger">Set Pinjaman</button>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#team-select').select2({
            placeholder: "Select Team",
            allowClear: true,
            width: '100%',
            height: '100%'
        });

        $('#pinjaman-select').select2({
            placeholder: "Select Pinjaman",
            allowClear: true,
            width: '100%',
            height: '100%'
        });

        $('#pinjaman-form').on('submit', function(event) {
            const selectedTeam = $('#team-select').val();
            const selectedPinjaman = $('#pinjaman-select').val();

            if (selectedTeam == null || selectedPinjaman == null) {
                event.preventDefault();
                toastr.error('Player and Pinjaman must be selected.');
            }
        });

    });




</script>
@endsection

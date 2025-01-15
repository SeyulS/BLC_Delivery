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

    <h3>Utility Room Controller</h3>
    <form action="/startSimulation" method="POST">
        @csrf
        <button type="submit" name="room_id" value="{{ $room_id }}" class="btn btn-primary">Start Simulation</button>
    </form>
    <br>
    <form action="/pauseSimulation" method="POST">
        @csrf
        <button type="submit" name="room_id" value="{{ $room_id }}" class="btn btn-primary">Pause Simulation</button>
    </form>
    <br>
    <form action="/resumeSimulation" method="POST">
        @csrf
        <button type="submit" name="room_id" value="{{ $room_id }}" class="btn btn-primary">Resume Simulation</button>
    </form>
    <br>
    <form action="/nextDaySimulation" method="POST">
        @csrf
        <button type="submit" name="room_id" value="{{ $room_id }}" class="btn btn-primary">Next Day</button>
    </form>
    <br>
    <form action="/endSimulation" method="POST">
        @csrf
        <button type="submit" name="room_id" value="{{ $room_id }}" class="btn btn-primary">End Simulation</button>
    </form>


</div>
@endsection
@extends('layout.main')

@section('script')
<style>
    input::placeholder {
        text-align: center;
    }

    input {
        text-align: center
    }
</style>
@endsection


@section('container')
<div class="main">
    <div class="row mt-4">
        <form action='/createRoom' method="POST">
            @csrf
            <div class="form-group">
                <label for="roomCode" class="text-center d-block">Room Code</label>
                <input type="text" class="form-control room-code-input mx-auto w-25 mt-3" name="roomCode" id="roomCode" maxlength="3" placeholder="XXX">
            </div>
            <br>
            @if (session()->has('error'))
            <div class="d-flex justify-content-center" ;>
                <div class="alert alert-danger w-25">
                    <p class="text-danger text-center">{{ session('error') }} !!!</p>
                </div>
            </div>

            @endif
            <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-dark btn-sm">Create</button>
            </div>
        </form>
    </div>

    <div class="row mt-4">
        @foreach($rooms as $room)
        <div class="col-md-3">
            <div class="card text-dark bg-light mb-3">
                <div class="card-header text-center">{{ $room->room_id }}</div>
                <div class="card-body text-center">
                    <ul class="list-unstyled">Day : 3</ul>
                    <ul class="list-unstyled">Total Player : </ul>


                    <div class="d-flex justify-content-center">
                        <a href="/lobby/{{ $room->room_id }}">
                            <button type="submit" class="btn btn-primary btn-sm">Handle</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

@endsection
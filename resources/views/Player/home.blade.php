<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BLC Delivery | Join Lobby</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<div class="join-lobby-container">
    <div class="lobby-card">
        <h2 class="lobby-title">Join Room</h2>
        
        @if (session()->has('error'))
            <div class="alert alert-danger fade show">
                <i class="fas fa-exclamation-circle"></i>
                {{ session('error') }}
            </div>
        @endif

        @if (session()->has('success'))
            <div class="alert alert-success fade show">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        <form action="/joinRoom" method="POST" id="joinForm">
            @csrf
            <div class="room-code-group">
                <label for="roomCode">Enter Room Code</label>
                <input type="text" 
                       class="room-code-input" 
                       name="roomCode" 
                       id="roomCode" 
                       maxlength="3" 
                       placeholder="XXX"
                       autocomplete="off">
                <div class="room-code-helper">Enter 3-digit room code</div>
            </div>

            <button type="submit" class="join-button">
                <i class="fas fa-sign-in-alt"></i>
                Join Room
            </button>
        </form>
    </div>
</div>

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
}

body {
    margin: 0;
    padding: 0;
}

.join-lobby-container {
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    padding: 20px;
}

.lobby-card {
    background: white;
    padding: 2.5rem;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    width: 100%;
    max-width: 400px;
    animation: fadeIn 0.5s ease-out;
}

.lobby-title {
    text-align: center;
    color: #2c3e50;
    margin-bottom: 2rem;
    font-size: 2rem;
    font-weight: 600;
}

.room-code-group {
    margin-bottom: 2rem;
}

.room-code-group label {
    display: block;
    margin-bottom: 0.5rem;
    color: #2c3e50;
    font-weight: 500;
}

.room-code-input {
    width: 100%;
    padding: 1rem;
    font-size: 1.5rem;
    text-align: center;
    letter-spacing: 0.5rem;
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    transition: all 0.3s ease;
    text-transform: uppercase;
}

.room-code-input:focus {
    border-color: #3498db;
    box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
    outline: none;
}

.room-code-helper {
    font-size: 0.875rem;
    color: #666;
    margin-top: 0.5rem;
    text-align: center;
}

.join-button {
    width: 100%;
    padding: 1rem;
    background: #3498db;
    color: white;
    border: none;
    border-radius: 10px;
    font-size: 1.1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.join-button:hover {
    background: #2980b9;
    transform: translateY(-2px);
}

.join-button:active {
    transform: translateY(0);
}

.create-room-link {
    margin-top: 1.5rem;
    text-align: center;
    color: #666;
}

.create-room-link a {
    color: #3498db;
    text-decoration: none;
    font-weight: 500;
    margin-left: 0.5rem;
}

.create-room-link a:hover {
    text-decoration: underline;
}

.alert {
    padding: 1rem;
    margin-bottom: 1.5rem;
    border-radius: 10px;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.alert-danger {
    background-color: #fff5f5;
    color: #dc3545;
    border: 1px solid #ffebeb;
}

.alert-success {
    background-color: #f0fff4;
    color: #28a745;
    border: 1px solid #ebfff0;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const joinForm = document.getElementById('joinForm');
    const roomCodeInput = document.getElementById('roomCode');

    // Auto-format room code input
    roomCodeInput.addEventListener('input', function(e) {
        this.value = this.value.replace(/[^A-Za-z0-9]/g, '').toUpperCase();
    });

    joinForm.addEventListener('submit', function(e) {
        e.preventDefault();

        if (roomCodeInput.value.length !== 3) {
            Swal.fire({
                title: 'Invalid Code',
                text: 'Please enter a valid 3-digit room code',
                icon: 'error',
                confirmButtonColor: '#3498db'
            });
            return;
        }

        Swal.fire({
            title: 'Joining Room',
            text: 'Please wait...',
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        setTimeout(() => {
            joinForm.submit();
        }, 500);
    });
});
</script>
</body>
</html> 
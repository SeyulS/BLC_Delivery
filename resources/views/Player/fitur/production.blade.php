@extends('layout.player_room')

@section('container')
<style>
    :root {
        --primary-color: #4361ee;
        --secondary-color: #3f37c9;
        --success-color: #2ea44f;
        --warning-color: #f7b731;
        --danger-color: #dc3545;
        --dark-color: #1e2a35;
        --light-color: #f8f9fa;
        --border-color: #e2e8f0;
    }

    .production-dashboard {
        background-color: #f8f9fa;
        min-height: 100vh;
        padding: 2rem 0;
    }

    .machine-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
        transition: all 0.3s ease;
        height: 100%;
    }

    .machine-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
    }

    .machine-icon-container {
        position: relative;
        height: 180px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8fafc;
        border-bottom: 1px solid var(--border-color);
    }

    .machine-icon {
        font-size: 4rem;
        color: #4a5568;
        transition: all 0.3s ease;
    }

    .machine-card:hover .machine-icon {
        color: var(--primary-color);
        transform: scale(1.1);
    }

    .machine-content {
        padding: 1.5rem;
    }

    .machine-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--dark-color);
        margin-bottom: 1rem;
    }

    .machine-stats {
        background: #f8f9fa;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1rem;
    }

    .production-input {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-top: 1rem;
    }

    .quantity-input {
        width: 100px;
        text-align: center;
        border: 2px solid var(--border-color);
        border-radius: 8px;
        padding: 0.5rem;
        font-size: 1.1rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .quantity-input:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
    }

    .capacity-badge {
        background: var(--dark-color);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.875rem;
        font-weight: 600;
    }

    .locked-machine {
        position: relative;
    }

    .locked-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        color: white;
        z-index: 2;
    }

    .lock-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
        color: #fff;
    }

    .btn-produce {
        background: var(--primary-color);
        color: white;
        padding: 1rem 2rem;
        border-radius: 10px;
        font-weight: 600;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        border: none;
        margin-top: 2rem;
    }

    .btn-produce:hover {
        background: var(--secondary-color);
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(67, 97, 238, 0.3);
    }

    .toast-success { background-color: #059669 !important; }
    .toast-error { background-color: #dc2626 !important; }
    .toast-info { background-color: #2563eb !important; }
</style>

<div class="production-dashboard">
    <div class="container">
        <form action="/produceItem" method="POST">
            @csrf
            <div class="row g-4 mb-4">
                @for ($i = 0; $i < count($roomMachine); $i++)
                    <input type="hidden" name="machine_id[]" value="{{ $roomMachine[$i] }}">
                    <div class="col-md-4">
                        <div class="machine-card {{ $playerMachineCapacity[$i] == 0 ? 'locked-machine' : '' }}">
                            @if($playerMachineCapacity[$i] == 0)
                            <div class="locked-overlay">
                                <i class="bi bi-lock-fill lock-icon"></i>
                                <input type="hidden" name="quantityProduce[]" value="0">
                                <p>Machine Locked</p>
                            </div>
                            @endif

                            <div class="machine-icon-container">
                                <i class="bi bi-gear-wide-connected machine-icon"></i>
                            </div>

                            <div class="machine-content">
                                <h5 class="machine-title">
                                    <i class="bi bi-tools me-2"></i>
                                    {{ $roomMachineName[$i] }}
                                </h5>

                                <div class="machine-stats">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-muted">Max Production Capacity</span>
                                        <span class="capacity-badge">
                                            {{ $playerMachineCapacity[$i] }} units
                                        </span>
                                    </div>
                                </div>

                                <div class="production-input">
                                    <input type="number"
                                        class="quantity-input"
                                        min="0"
                                        max="{{ $playerMachineCapacity[$i] }}"
                                        name="quantityProduce[]"
                                        value="{{ old('quantityProduce.' . $i, 0) }}"
                                        {{ $playerMachineCapacity[$i] == 0 ? 'disabled' : '' }}
                                        placeholder="0">
                                </div>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>

            <button type="submit" class="btn btn-produce w-100">
                <i class="bi bi-play-circle-fill me-2"></i>
                Start Production
            </button>
        </form>
    </div>
</div>

<script>
    $(document).ready(() => {
        // Toast notifications
        @if(session('success'))
        toastr.success("{{ session('success') }}");
        @endif

        @if(session('fail'))
        toastr.error("{{ session('fail') }}");
        @endif

        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "3000",
            "extendedTimeOut": "1000",
        };

        // Form submission handling
        $("form[action='/produceItem']").on("submit", function(e) {
            e.preventDefault();

            let hasProduction = false;
            $("input[name='quantityProduce[]']").each(function() {
                if (parseInt($(this).val()) > 0) {
                    hasProduction = true;
                }
            });

            if (!hasProduction) {
                toastr.info('Please set production quantity for at least one machine');
                return;
            }

            Swal.fire({
                title: 'Confirm Production',
                html: `
                <div class="text-start">
                    <p>Are you sure you want to start production with these quantities?</p>
                    <small class="text-muted">This action cannot be undone</small>
                </div>
            `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Start Production',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#4361ee',
                cancelButtonColor: '#718096'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });

        // Your existing Echo listeners...
    });
</script>
@endsection
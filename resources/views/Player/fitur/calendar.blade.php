@extends('layout.player_room')

@section('container')
<style>
    :root {
        --primary: #4361ee;
        --secondary: #3f37c9;
        --success: #2ea44f;
        --warning: #f7b731;
        --danger: #ff4757;
        --dark: #1e2a35;
        --border: #e2e8f0;
        --light: #f8fafc;
    }


    .calendar-dashboard {
        min-height: 100vh;
        background-color: #f1f5f9;
        padding: 2rem 0;
    }

    /* Header Card */
    .dashboard-header {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
    }

    .header-content {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 2rem;
    }

    .time-display {
        position: relative;
        padding-left: 1rem;
        border-left: 4px solid var(--primary);
    }

    .time-label {
        font-size: 0.85rem;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 0.5rem;
    }

    .time-value {
        font-family: 'JetBrains Mono', monospace;
        font-size: 1.75rem;
        font-weight: 600;
        color: var(--dark);
        letter-spacing: -0.5px;
    }

    .user-info {
        text-align: right;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 1rem;
    }

    .user-avatar {
        background: var(--primary);
        color: white;
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    /* Calendar Card */
    .calendar-card {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
    }

    .calendar-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .calendar-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--dark);
    }

    .weekdays {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 0.5rem;
        margin-bottom: 1rem;
    }

    .weekday {
        text-align: center;
        font-size: 0.875rem;
        font-weight: 600;
        color: #64748b;
        padding: 0.75rem;
    }

    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 0.5rem;
    }

    .calendar-day {
        aspect-ratio: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        font-weight: 500;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
        background: var(--light);
        border: 1px solid var(--border);
    }

    .calendar-day:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
    }

    .calendar-day.special {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
        border: none;
    }

    .calendar-day.past-month {
        opacity: 0.5;
        background: #e2e8f0;
        cursor: not-allowed;
    }

    .calendar-legend {
        display: flex;
        justify-content: center;
        gap: 2rem;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid var(--border);
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .legend-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
    }

    .legend-dot.normal {
        background: var(--light);
        border: 1px solid var(--border);
    }

    .legend-dot.special {
        background: var(--primary);
    }

    .legend-label {
        font-size: 0.875rem;
        color: #64748b;
    }
</style>

<div class="calendar-dashboard">
    <div class="container">


        <!-- Calendar -->
        <div class="calendar-card">

            <!-- Weekdays -->
            <div class="weekdays">
                @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                <div class="weekday">{{ $day }}</div>
                @endforeach
            </div>

            <!-- Calendar Grid -->
            <div class="calendar-grid" id="calendarGrid">
                <!-- Days will be dynamically inserted here -->
            </div>

            <!-- Legend -->
            <div class="calendar-legend">
                <div class="legend-item">
                    <div class="legend-dot normal"></div>
                    <span class="legend-label">Normal Day</span>
                </div>
                <div class="legend-item">
                    <div class="legend-dot special"></div>
                    <span class="legend-label">Special Day</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        const maxDays = "{{ $room->max_day }}";
        const roomId = "{{ $room->room_id }}";
        const specialDays = JSON.parse(@json($room->special_day)).map(Number);
        console.log(specialDays);

        let calendarHTML = '';

        // Current month days
        for (let i = 1; i <= maxDays; i++) {
            const isSpecial = specialDays.includes(i);
            calendarHTML += `
            <div class="calendar-day ${isSpecial ? 'special' : ''}" data-day="${i}">
                ${i}
            </div>
        `;
        }

        $('#calendarGrid').html(calendarHTML);

        // Add hover effect to display day info
        $('.calendar-day:not(.past-month)').hover(function() {
            const day = $(this).data('day');
            const isSpecial = specialDays.includes(day);
            $(this).attr('title', `Day ${day}${isSpecial ? ' - Special Day' : ''}`);
        });

        window.Echo.channel('player-remove')
            .listen('.PlayerRemoveEvent', (event) => {
                if (event.playerUsername == playerUsername) {
                    window.location.href = '/homePlayer'
                }
                if (event.roomId == roomId) {
                    datatable.ajax.reload();
                }

            });

        window.Echo.channel('start-simulation')
            .listen('.StartSimulationEvent', (event) => {
                if (event.roomId = roomId) {
                    Swal.fire({
                        title: 'Loading...',
                        text: 'The simulation has started',
                        icon: 'info',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        timer: 5000,
                        didOpen: () => {
                            Swal.showLoading();
                        },
                    });

                    setTimeout(() => {
                        window.location.href = `/player-lobby/${roomId}`;
                    }, 5000);
                }
            });

        window.Echo.channel('pause-simulation')
            .listen('.PauseSimulationEvent', (event) => {
                if (event.roomId == roomId) {
                    Swal.fire({
                        title: 'Loading...',
                        text: 'The simulation was paused',
                        icon: 'info',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        timer: 5000,
                        didOpen: () => {
                            Swal.showLoading();
                        },
                    });

                    setTimeout(() => {
                        window.location.href = `/player-lobby/${roomId}`;
                    }, 5000);
                }
            });

        window.Echo.channel('resume-simulation')
            .listen('.ResumeSimulationEvent', (event) => {
                if (event.roomId == roomId) {
                    Swal.fire({
                        title: 'Loading...',
                        text: 'The simulation was resumed',
                        icon: 'info',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        timer: 5000,
                        didOpen: () => {
                            Swal.showLoading();
                        },
                    });

                    setTimeout(() => {
                        window.location.href = `/player-lobby/${roomId}`;
                    }, 5000);
                }
            });

        window.Echo.channel('next-day')
            .listen('.NextDaySimulationEvent', (event) => {
                console.log(event.roomId, roomId);
                if (event.roomId == roomId) {
                    Swal.fire({
                        title: 'Loading...',
                        text: 'Moving to the next day. Please wait.',
                        icon: 'info',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        timer: 5000,
                        didOpen: () => {
                            Swal.showLoading();
                        },
                    });

                    setTimeout(() => {
                        window.location.href = `/player-lobby/${roomId}`;
                    }, 5000);
                }
            });

        window.Echo.channel('end-simulation')
            .listen('.EndSimulationEvent', (event) => {
                if (event.roomId == roomId) {
                    Swal.fire({
                        title: 'Simulation Ended',
                        text: 'The simulation has ended',
                        icon: 'info',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        timer: 5000,
                        didOpen: () => {
                            Swal.showLoading();
                        },
                    });

                    setTimeout(() => {
                        window.location.href = '/homePlayer';
                    }, 5000);
                }
            });

    });
</script>
@endsection
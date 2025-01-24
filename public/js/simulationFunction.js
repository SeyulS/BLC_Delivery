function setupSimulationEvents(roomId) {
    window.Echo.channel('start-simulation')
        .listen('StartSimulation', () => {
            window.location.href = `/player-lobby/${roomId}`;
        });

    window.Echo.channel('pause-simulation')
        .listen('PauseSimulation', () => {
            window.location.href = `/player-lobby/${roomId}`;
        });

    window.Echo.channel('next-day')
        .listen('NextDaySimulation', () => {
            Swal.fire({
                title: 'Loading...',
                text: 'Moving to the next day. Please wait.',
                icon: 'info',
                allowOutsideClick: false, // Prevent closing by clicking outside
                showConfirmButton: false, // Remove the "OK" button
                timer: 5000, // Timer for 5 seconds
                didOpen: () => {
                    Swal.showLoading(); // Show the loading animation
                },
            });

            // Redirect after 5 seconds (same duration as SweetAlert timer)
            setTimeout(() => {
                window.location.href = `/player-lobby/${roomId}`;
            }, 5000);
        });
}

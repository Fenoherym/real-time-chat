import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});

// Dans echo.js
window.Echo.connector.pusher.connection.bind('connected', () => {
    console.log('Connected to Reverb!');
});

window.Echo.connector.pusher.connection.bind('error', (error) => {
    console.log('Reverb connection error:', error);
});


window.Echo.private('chat.2')
            .listen('ChatMessage', (e) => {
                console.log('Received message:', e);
            })

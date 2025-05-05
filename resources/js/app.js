import './bootstrap';

import Alpine from 'alpinejs';
import { createApp } from 'vue';
import ChatMessage from './components/ChatMessage.vue';

const app = createApp({});

app.component('chat-message', ChatMessage);
app.mount('#app');

window.Alpine = Alpine;

Alpine.start();

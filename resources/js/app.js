import './bootstrap';

import Alpine from 'alpinejs';
import { createApp } from 'vue';
import ChatMessage from './components/ChatMessage.vue';
import { VueQueryPlugin } from '@tanstack/vue-query';

const app = createApp({});
app.use(VueQueryPlugin);
app.component('chat-message', ChatMessage);
app.mount('#app');

window.Alpine = Alpine;

Alpine.start();

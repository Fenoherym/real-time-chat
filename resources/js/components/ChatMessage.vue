<template>
    <div class="min-h-screen bg-gray-100">
                <div class="max-w-4xl mx-auto p-4">
                        <div class="bg-white rounded-lg shadow-lg">
                <!-- Header modifié pour afficher l'interlocuteur -->
                <div class="p-4 border-b flex items-center space-x-4">
<div class="relative">
                    <div class="h-12 w-12 rounded-full bg-blue-500 flex items-center justify-center">
                        <span class="text-white font-bold text-lg">JS</span>
</div>
                        <div class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 rounded-full border-2 border-white"></div>
                    </div>
                    <div>
                        <h2 class="font-semibold text-lg">John Smith</h2>
                        <p class="text-sm text-green-500">Online</p>
                    </div>
                </div>

                <!-- Zone de messages -->
                <div class="h-[500px] overflow-y-auto p-4 space-y-4" ref="messageContainer">
                    <div v-for="message in messages" :key="message.id" 
                         :class="message.isMe ? 'flex justify-end' : 'flex justify-start'">
                        <div :class="[
                            'max-w-[70%] rounded-lg px-4 py-2 relative',
                            message.isMe ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-800'
                        ]">
                            <p class="text-sm">{{ message.content }}</p>
                            <div class="flex items-center justify-end gap-1 mt-1">
                                <p class="text-xs" :class="message.isMe ? 'text-blue-100' : 'text-gray-500'">
                                    {{ message.time }}
                                </p>
                                <!-- Status indicators pour mes messages -->
                                <span v-if="message.isMe" class="text-xs">
                                    <!-- Sent -->
                                    <svg v-if="message.status === 'sent'" class="w-4 h-4 text-blue-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <!-- Delivered -->
                                    <svg v-if="message.status === 'delivered'" class="w-4 h-4 text-blue-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7M5 13l4 4L19 7"></path>
                                    </svg>
                                    <!-- Read -->
                                    <svg v-if="message.status === 'read'" class="w-4 h-4 text-blue-100 fill-current" viewBox="0 0 24 24">
                                        <path d="M5 13l4 4L19 7M5 13l4 4L19 7"></path>
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Zone de saisie -->
                <div class="p-4 border-t">
                    <div class="flex space-x-2">
                        <input 
                            v-model="newMessage"
                            type="text"
                            class="flex-1 border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Write a message..."
                            @keyup.enter="sendMessage"
                        >
                        <button 
                            @click="sendMessage"
                            class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition-colors duration-200"
                        >
                            Send
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, nextTick } from 'vue'

const newMessage = ref('')
const messageContainer = ref(null)

// Messages avec uniquement 2 participants
const messages = ref([
    {
        id: 1,
        content: 'Hi John! Are you available for a quick chat?',
        time: '10:00',
        isMe: true,
        status: 'read'
    },
    {
        id: 2,
        content: 'Yes, of course! What\'s on your mind?',
        time: '10:01',
        isMe: false
    },
    {
        id: 3,
        content: 'I wanted to discuss the new project',
        time: '10:02',
        isMe: true,
        status: 'delivered'
    }
])

const scrollToBottom = async () => {
    await nextTick()
    messageContainer.value.scrollTop = messageContainer.value.scrollHeight
}

const sendMessage = async () => {
    if (newMessage.value.trim()) {
        const message = {
            id: Date.now(),
            content: newMessage.value,
            time: new Date().toLocaleTimeString('en-US', {
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            }),
            isMe: true,
            status: 'sent'
        }
        
        messages.value.push(message)
        newMessage.value = ''
        await scrollToBottom()
        
        // Simuler la livraison et la lecture du message
        setTimeout(() => {
            message.status = 'delivered'
        }, 2000)
        
        setTimeout(() => {
            message.status = 'read'
        }, 4000)
    }
}

onMounted(() => {
    scrollToBottom()
})
</script>
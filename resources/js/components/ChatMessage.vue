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
                         :class="message.sender_id === current_user.id ? 'flex justify-end' : 'flex justify-start'">
                        <div :class="[
                            'max-w-[70%] rounded-lg px-4 py-2 relative',
                            message.sender_id === current_user.id ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-800'
                        ]">
                            <p class="text-sm">{{ message.message }}</p>
                            <div class="flex items-center justify-end gap-1 mt-2">
                                <p class="text-xs" :class="message.sender_id === current_user.id ? 'text-blue-100' : 'text-gray-500'">
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
import { ref, onMounted, nextTick, watchEffect } from 'vue'
import { useQuery } from '@tanstack/vue-query'
import axios from 'axios'
import Echo from 'laravel-echo';


const props = defineProps({
    current_user: {
        type: Object,
        required: true
    },
    receiver_id: {
        type: Number,
        required: true
    }
})



// Séparer la fonction de fetch
const fetchMessages = async () => {
    try {
        const response = await fetch('/chat/1')        
        if (!response.ok) {
            throw new Error('Network response was not ok')
        }        
        return response.json()
    } catch (error) {
        throw new Error('Error fetching messages: ' + error.message)
    }
}





// Utilisation correcte de useQuery
const { data, isLoading, isError, error } = useQuery({
    queryKey: ['messages'],
    queryFn: fetchMessages,
})

const newMessage = ref('')
const messageContainer = ref(null)
const messages = ref([])

// Observer les changements de data
watchEffect(() => {
    if (data.value) {
        messages.value = data.value.messages 
    
    }
})

const scrollToBottom = async () => {
    await nextTick()
    messageContainer.value.scrollTop = messageContainer.value.scrollHeight
}

const sendMessage = async () => {
    if (newMessage.value.trim()) {
        console.log('Sending message to:', props.receiver_id);
        
        try {
            const response = await axios.post('/chat', {
                message: newMessage.value,            
                receiver_id: props.receiver_id
            });
            
            console.log('Message sent response:', response.data);
            messages.value = [...messages.value, response.data.message];
        } catch (error) {
            console.error('Error sending message:', error);
        }

        newMessage.value = '';
        await scrollToBottom();     
    }   
}

onMounted(() => {
    scrollToBottom()
    console.log('Component mounted, attempting to connect to channel:', `chat.${props.current_user.id}`)
    
    const channel = window.Echo.private(`chat.${props.current_user.id}`)
    
    // Log quand la connexion au canal est réussie
    channel.subscribed(() => {
        console.log('Successfully subscribed to channel:', `chat.${props.current_user.id}`)
    })
    
    // Log en cas d'erreur de connexion
    channel.error((error) => {
        console.error('Channel subscription error:', error)
    })
    
    channel.listen('MessageSent', (e) => {
        console.log('Event received:', e)
        messages.value = [...messages.value, e.message]
        console.log('Updated messages:', messages.value)
    })
})
</script>
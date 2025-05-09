<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Chat') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">               
               
                    <chat-message 
                        :current_user='@json($current_user)'
                        :receiver_id='{{ $receiver_id }}'
                    ></chat-message>
                
            </div>
        </div>
    </div>
</x-app-layout>

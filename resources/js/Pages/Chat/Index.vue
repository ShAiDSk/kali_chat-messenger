<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { ref, onMounted, nextTick, computed, onUnmounted } from 'vue';
import axios from 'axios';

// --- STATE MANAGEMENT ---
const page = usePage();
const currentUser = page.props.auth.user;
const users = ref([]);
const onlineUserIds = ref(new Set());
const messages = ref([]);
const activeUser = ref(null);
const newMessage = ref('');
const boxRef = ref(null);
const fileInput = ref(null);

// Interactive States
const isTyping = ref(false); 
const peerTyping = ref(false); 
const typingTimer = ref(null);
const isDragging = ref(false); 
const notificationSound = new Audio('/sounds/pop.mp3'); 

// --- METHODS ---

const loadMessages = async (user) => {
    activeUser.value = user;
    user.unread = 0;
    messages.value = [];
    peerTyping.value = false;

    try {
        const response = await axios.get(`/chat/messages/${user.id}`);
        messages.value = response.data;
        scrollToBottom();
        markAsRead();
        setupWhisperListener(user.id);
    } catch (error) { console.error(error); }
};

const markAsRead = async () => {
    if (!activeUser.value) return;
    try { await axios.post('/chat/read', { sender_id: activeUser.value.id }); } catch (error) {}
};

const selectImage = () => fileInput.value.click();

const handleFileChange = (e) => {
    const file = e.target.files ? e.target.files[0] : e.dataTransfer.files[0];
    if (!file) return;
    
    if (!file.type.startsWith('image/')) {
        alert('Only image files are allowed.');
        return;
    }
    
    sendMessage(file);
    isDragging.value = false; 
};

const sendMessage = async (file = null) => {
    if ((!newMessage.value.trim() && !file) || !activeUser.value) return;

    const formData = new FormData();
    formData.append('receiver_id', activeUser.value.id);
    if (newMessage.value) formData.append('content', newMessage.value);
    if (file) formData.append('image', file);

    const tempMsg = {
        id: Date.now(),
        content: newMessage.value,
        image_path: file ? URL.createObjectURL(file) : null,
        user_id: currentUser.id,
        user: currentUser,
        receiver_id: activeUser.value.id,
        created_at: new Date().toISOString(),
        read_at: null
    };

    messages.value.push(tempMsg);
    newMessage.value = '';
    scrollToBottom();
    playNotificationSound(true);

    try {
        await axios.post('/chat/send', formData, { headers: { 'Content-Type': 'multipart/form-data' }});
    } catch (e) { console.error(e); }
};

const handleTyping = () => {
    if (!activeUser.value) return;
    window.Echo.private(`chat.${activeUser.value.id}`)
        .whisper('typing', { userID: currentUser.id });
};

const setupWhisperListener = (peerId) => {
    window.Echo.private(`chat.${currentUser.id}`)
        .listenForWhisper('typing', (e) => {
            if (activeUser.value && e.userID === activeUser.value.id) {
                peerTyping.value = true;
                if (typingTimer.value) clearTimeout(typingTimer.value);
                typingTimer.value = setTimeout(() => { peerTyping.value = false; }, 3000);
            }
        });
};

const scrollToBottom = () => {
    nextTick(() => { if (boxRef.value) boxRef.value.scrollTop = boxRef.value.scrollHeight; });
};

const formatTime = (date) => {
    return new Date(date).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
};

const playNotificationSound = (isSelf = false) => {
    if (document.hidden || !isSelf) {
        notificationSound.currentTime = 0;
        notificationSound.play().catch(e => {});
    }
    if (document.hidden && !isSelf && activeUser.value) {
        new Notification(`Message from ${activeUser.value.name}`, { body: "New encrypted data.", icon: '/favicon.ico' });
    }
};

onMounted(async () => {
    if (Notification.permission !== "granted") Notification.requestPermission();

    try {
        const response = await axios.get('/users');
        users.value = response.data;
    } catch (error) { console.error(error); }

    window.Echo.join('chat')
        .here((u) => u.forEach(user => onlineUserIds.value.add(user.id)))
        .joining((user) => onlineUserIds.value.add(user.id))
        .leaving((user) => onlineUserIds.value.delete(user.id));

    window.Echo.private(`chat.${currentUser.id}`)
        .listen('PrivateMessageSent', (e) => {
            if (activeUser.value && e.message.user_id === activeUser.value.id) {
                messages.value.push(e.message);
                scrollToBottom();
                markAsRead();
                playNotificationSound();
                peerTyping.value = false;
            } else {
                const sender = users.value.find(user => user.id === e.message.user_id);
                if (sender) {
                    sender.unread = (sender.unread || 0) + 1;
                    playNotificationSound();
                }
            }
        })
        .listen('MessageRead', (e) => {
            messages.value.forEach(msg => {
                if (e.messageIds.includes(msg.id)) msg.read_at = new Date().toISOString();
            });
        });
});
</script>

<template>
    <Head title="Reverb Chat" />

    <AuthenticatedLayout>
        
        <div class="fixed inset-0 z-0 pointer-events-none bg-[#030305]">
            <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-10 mix-blend-soft-light"></div>
            <div class="absolute inset-0 bg-grid-animate opacity-30"></div>
            
            <div class="absolute top-[-20%] left-[-10%] w-[800px] h-[800px] bg-cyan-500/20 rounded-full blur-[120px] animate-blob mix-blend-screen"></div>
            <div class="absolute bottom-[-20%] right-[-10%] w-[800px] h-[800px] bg-purple-600/30 rounded-full blur-[120px] animate-blob animation-delay-4000 mix-blend-screen"></div>
            <div class="absolute top-[40%] left-[30%] w-[500px] h-[500px] bg-pink-500/10 rounded-full blur-[100px] animate-blob animation-delay-2000"></div>
        </div>

        <div class="py-8 h-[calc(100vh-80px)] relative z-10" 
             @dragover.prevent="isDragging = true" 
             @dragleave.prevent="isDragging = false" 
             @drop.prevent="handleFileChange">
            
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 h-full">
                
                <div class="bg-[#0A0A12]/60 backdrop-blur-3xl shadow-[0_0_50px_rgba(0,0,0,0.5)] sm:rounded-2xl h-full flex overflow-hidden border border-white/10 ring-1 ring-white/5 slide-up-main relative">
                    
                    <Transition name="fade">
                        <div v-if="isDragging && activeUser" class="absolute inset-0 z-50 bg-indigo-900/90 backdrop-blur-md flex flex-col items-center justify-center border-4 border-dashed border-cyan-400 m-4 rounded-xl shadow-[0_0_50px_rgba(34,211,238,0.3)]">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-24 h-24 text-cyan-400 animate-bounce">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                            </svg>
                            <h2 class="text-4xl font-black text-white mt-4 tracking-widest uppercase">Drop Data Here</h2>
                            <p class="text-cyan-200 mt-2 font-mono">Initiate secure file transfer protocol</p>
                        </div>
                    </Transition>

                    <div class="w-1/3 md:w-80 border-r border-white/10 bg-[#05050A]/40 flex flex-col">
                        <div class="p-6 border-b border-white/10 flex justify-between items-center bg-white/5">
                            <span class="font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-indigo-400 text-xl tracking-wide uppercase drop-shadow-sm">Contacts</span>
                            <span class="bg-indigo-500/20 text-indigo-200 text-sm px-3 py-1 rounded-full font-bold border border-indigo-500/30 shadow-[0_0_10px_rgba(99,102,241,0.2)]">
                                {{ users.length }}
                            </span>
                        </div>
                        
                        <div class="overflow-y-auto flex-1 p-3 space-y-2 custom-scrollbar">
                            <button v-for="user in users" :key="user.id" @click="loadMessages(user)"
                                class="w-full p-3 flex items-center gap-4 rounded-xl transition-all duration-300 group relative overflow-hidden"
                                :class="activeUser?.id === user.id 
                                    ? 'bg-gradient-to-r from-indigo-500/20 to-purple-500/20 border border-indigo-500/50 shadow-[0_0_20px_rgba(99,102,241,0.15)] translate-x-1' 
                                    : 'hover:bg-white/5 border border-transparent hover:border-white/10'">
                                
                                <div v-if="activeUser?.id === user.id" class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-cyan-400 to-indigo-500 shadow-[0_0_10px_#22d3ee]"></div>

                                <div class="relative">
                                    <div class="w-12 h-12 rounded-full flex items-center justify-center font-bold text-lg shadow-inner transition-transform group-hover:scale-105"
                                         :class="activeUser?.id === user.id ? 'bg-gradient-to-br from-indigo-500 to-purple-600 text-white ring-2 ring-white/20' : 'bg-[#151520] border border-white/10 text-gray-300'">
                                        {{ user.name.charAt(0).toUpperCase() }}
                                    </div>
                                    
                                    <span v-if="onlineUserIds.has(user.id)" class="absolute bottom-0 right-0 w-3.5 h-3.5 bg-green-500 border-2 border-[#05050A] rounded-full shadow-[0_0_8px_#22c55e] animate-pulse"></span>
                                    
                                    <span v-if="user.unread > 0" class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 border-2 border-[#05050A] rounded-full flex items-center justify-center text-[10px] text-white font-bold shadow-[0_0_10px_#ef4444] animate-bounce">
                                        {{ user.unread }}
                                    </span>
                                </div>
                                
                                <div class="flex-1 min-w-0 text-left">
                                    <div class="font-bold truncate text-base transition-colors"
                                         :class="activeUser?.id === user.id ? 'text-white' : 'text-gray-200 group-hover:text-white'">
                                        {{ user.name }}
                                    </div>
                                    <div class="text-xs truncate font-medium flex items-center gap-1 transition-colors"
                                         :class="activeUser?.id === user.id ? 'text-indigo-200' : 'text-gray-400 group-hover:text-gray-300'">
                                        <span v-if="onlineUserIds.has(user.id)" class="w-1.5 h-1.5 rounded-full bg-green-400 inline-block shadow-[0_0_5px_#4ade80]"></span>
                                        {{ onlineUserIds.has(user.id) ? 'Online' : 'Offline' }}
                                    </div>
                                </div>
                            </button>
                        </div>
                    </div>

                    <div class="flex-1 flex flex-col bg-[#030305]/60 relative">
                        
                        <div v-if="activeUser" class="px-6 py-4 bg-white/5 backdrop-blur-md border-b border-white/10 flex items-center justify-between z-10 sticky top-0 shadow-sm">
                            <div class="flex items-center gap-4">
                                <div class="relative">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-cyan-900 to-indigo-900 flex items-center justify-center text-cyan-200 font-bold text-lg border border-cyan-500/30 shadow-[0_0_15px_rgba(34,211,238,0.2)]">
                                        {{ activeUser.name.charAt(0) }}
                                    </div>
                                    <span v-if="onlineUserIds.has(activeUser.id)" class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-[#05050A] rounded-full shadow-[0_0_8px_#22c55e]"></span>
                                </div>
                                <div>
                                    <div class="font-extrabold text-white text-lg tracking-wide">{{ activeUser.name }}</div>
                                    <div class="text-xs font-semibold flex items-center gap-2" :class="onlineUserIds.has(activeUser.id) ? 'text-green-400' : 'text-gray-400'">
                                        <span v-if="peerTyping" class="text-pink-400 animate-pulse font-bold tracking-widest flex items-center gap-1">
                                            <span class="w-1 h-1 bg-pink-400 rounded-full"></span> TYPING...
                                        </span>
                                        <span v-else>
                                            {{ onlineUserIds.has(activeUser.id) ? 'Active Uplink' : 'Last seen recently' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <button class="p-2 text-gray-400 hover:text-cyan-400 hover:bg-cyan-400/10 rounded-full transition-all duration-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </button>
                                <button class="p-2 text-gray-400 hover:text-purple-400 hover:bg-purple-400/10 rounded-full transition-all duration-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg>
                                </button>
                            </div>
                        </div>

                        <div v-else class="flex-1 flex flex-col items-center justify-center text-center p-8 opacity-0 animate-fade-in-up">
                            <div class="w-32 h-32 bg-gradient-to-br from-indigo-500/10 to-purple-500/10 rounded-full flex items-center justify-center mb-6 border border-indigo-500/20 shadow-[0_0_30px_rgba(99,102,241,0.2)] group cursor-pointer hover:scale-110 transition-transform duration-500">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-16 h-16 text-indigo-400 group-hover:text-cyan-400 transition-colors duration-500">
                                    <path fill-rule="evenodd" d="M4.848 2.771A49.144 49.144 0 0112 2.25c2.43 0 4.817.178 7.152.52 1.978.292 3.348 2.024 3.348 3.97v6.02c0 1.946-1.37 3.678-3.348 3.97a48.901 48.901 0 01-3.476.383.39.39 0 00-.297.17l-2.755 4.133a.75.75 0 01-1.248 0l-2.755-4.133a.39.39 0 00-.297-.17 48.9 48.9 0 01-3.476-.384c-1.978-.29-3.348-2.024-3.348-3.97V6.741c0-1.946 1.37-3.68 3.348-3.97z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <h3 class="text-3xl font-extrabold text-white mb-2 tracking-tight">Secure Channel Ready</h3>
                            <p class="text-gray-300 font-medium max-w-sm">Select a contact from the grid to initialize encrypted neural-link.</p>
                        </div>

                        <div v-if="activeUser" ref="boxRef" class="flex-1 overflow-y-auto p-6 space-y-6 custom-scrollbar relative z-0 scroll-smooth">
                            <TransitionGroup name="spring-message" tag="div" class="space-y-6">
                                <div v-for="msg in messages" :key="msg.id" class="flex flex-col group">
                                    <div class="flex items-end gap-3" :class="msg.user_id === currentUser.id ? 'flex-row-reverse' : 'flex-row'">
                                        <div v-if="msg.user_id !== currentUser.id" class="w-8 h-8 rounded-full bg-[#1A1A23] flex items-center justify-center text-xs font-bold text-gray-300 mb-1 border border-white/20 shadow-md">
                                            {{ activeUser.name.charAt(0) }}
                                        </div>

                                        <div class="max-w-[70%] px-5 py-3 shadow-xl relative text-sm transition-all duration-300 backdrop-blur-sm"
                                            :class="[
                                                msg.user_id === currentUser.id 
                                                    ? 'bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-600 text-white rounded-2xl rounded-tr-sm shadow-[0_4px_15px_rgba(79,70,229,0.4)] border border-white/10' 
                                                    : 'bg-[#1A1A23] text-white rounded-2xl rounded-tl-sm border border-white/10 hover:border-indigo-500/30 hover:bg-[#20202A] transition-colors',
                                                msg.image_path ? 'p-2' : ''
                                            ]">
                                            
                                            <div v-if="msg.image_path" class="mb-2 rounded-xl overflow-hidden shadow-lg border border-white/10 group-hover:border-white/20 transition-all">
                                                <img :src="msg.image_path.startsWith('blob:') ? msg.image_path : '/storage/' + msg.image_path" class="w-full h-auto max-h-64 object-cover cursor-pointer hover:scale-105 transition-transform duration-500">
                                            </div>
                                            
                                            <p v-if="msg.content" class="leading-relaxed font-medium tracking-wide">{{ msg.content }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-1 mt-1 opacity-0 group-hover:opacity-100 transition-opacity duration-300 text-[10px] font-mono tracking-wider" 
                                         :class="msg.user_id === currentUser.id ? 'justify-end pr-1' : 'justify-start pl-12'">
                                        <span class="text-gray-400">{{ formatTime(msg.created_at) }}</span>
                                        <span v-if="msg.user_id === currentUser.id" class="ml-1">
                                            <svg v-if="msg.read_at" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-3.5 h-3.5 text-green-400 drop-shadow-[0_0_5px_rgba(74,222,128,0.8)]"><path fill-rule="evenodd" d="M19.916 4.626a.75.75 0 0 1 .208 1.04l-9 13.5a.75.75 0 0 1-1.154.114l-6-6a.75.75 0 0 1 1.06-1.06l5.353 5.353 8.493-12.74a.75.75 0 0 1 1.04-.207Zm-13.84 9.04 1.415-1.415L9.917 14.67l7.769-11.652a.75.75 0 0 1 1.227.872l-8.5 12.75a.75.75 0 0 1-1.17.067l-3.208-3.207Z" clip-rule="evenodd" /></svg>
                                            <svg v-else-if="onlineUserIds.has(activeUser.id)" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-3.5 h-3.5 text-gray-400"><path fill-rule="evenodd" d="M19.916 4.626a.75.75 0 0 1 .208 1.04l-9 13.5a.75.75 0 0 1-1.154.114l-6-6a.75.75 0 0 1 1.06-1.06l5.353 5.353 8.493-12.74a.75.75 0 0 1 1.04-.207Z" clip-rule="evenodd" /></svg>
                                            <svg v-else xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-3.5 h-3.5 text-gray-500"><path fill-rule="evenodd" d="M19.916 4.626a.75.75 0 0 1 .208 1.04l-9 13.5a.75.75 0 0 1-1.154.114l-6-6a.75.75 0 0 1 1.06-1.06l5.353 5.353 8.493-12.74a.75.75 0 0 1 1.04-.207Z" clip-rule="evenodd" /></svg>
                                        </span>
                                    </div>
                                </div>
                            </TransitionGroup>
                            
                            <div v-if="peerTyping" class="flex gap-3 items-center mt-4 animate-fade-in-up">
                                <div class="w-8 h-8 rounded-full bg-[#1A1A23] flex items-center justify-center text-xs font-bold text-gray-400 border border-white/10">
                                    {{ activeUser.name.charAt(0) }}
                                </div>
                                <div class="bg-[#1A1A23] px-4 py-3 rounded-2xl rounded-tl-sm flex gap-1 border border-white/5 shadow-md">
                                    <div class="w-2 h-2 bg-pink-500 rounded-full animate-bounce"></div>
                                    <div class="w-2 h-2 bg-purple-500 rounded-full animate-bounce delay-100"></div>
                                    <div class="w-2 h-2 bg-indigo-500 rounded-full animate-bounce delay-200"></div>
                                </div>
                            </div>
                        </div>

                        <div v-if="activeUser" class="p-4 bg-[#0A0A12]/95 border-t border-white/10 relative z-20">
                            <form @submit.prevent="sendMessage(null)" 
                                  class="flex gap-3 items-end max-w-4xl mx-auto bg-[#15151A] p-2 rounded-[2rem] border border-white/5 shadow-xl transition-all duration-300 focus-within:shadow-[0_0_30px_rgba(99,102,241,0.25)] focus-within:border-indigo-500/60 focus-within:scale-[1.01] focus-within:bg-[#1A1A20]">
                                
                                <input type="file" ref="fileInput" @change="handleFileChange" class="hidden" accept="image/*">
                                <button type="button" @click="selectImage" class="p-3 text-gray-400 hover:text-cyan-400 hover:bg-cyan-900/20 rounded-full transition-all duration-200 hover:rotate-12 active:scale-90">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="m18.375 12.739-7.693 7.693a4.5 4.5 0 0 1-6.364-6.364l10.94-10.94A3 3 0 1 1 19.5 7.372L8.552 18.32m.009-.01-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 0 0 2.112 2.13" /></svg>
                                </button>

                                <input v-model="newMessage" @keydown="handleTyping" type="text" 
                                    class="flex-1 bg-transparent border-none focus:ring-0 text-white placeholder-gray-400 px-2 py-3" 
                                    placeholder="Type message..." 
                                    autocomplete="off"
                                >

                                <button type="submit" 
                                    class="p-3 rounded-full text-white shadow-lg transition-all duration-200 transform active:scale-75 flex items-center justify-center hover:shadow-[0_0_15px_rgba(236,72,153,0.6)]"
                                    :class="(newMessage.trim() || fileInput) ? 'bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500' : 'bg-gray-700 cursor-not-allowed'"
                                    :disabled="!newMessage.trim() && !fileInput">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 translate-x-0.5"><path d="M3.478 2.404a.75.75 0 0 0-.926.941l2.432 7.905H13.5a.75.75 0 0 1 0 1.5H4.984l-2.432 7.905a.75.75 0 0 0 .926.94 60.519 60.519 0 0 0 18.445-8.986.75.75 0 0 0 0-1.218A60.517 60.517 0 0 0 3.478 2.404Z" /></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
/* ANIMATION UTILS */
.slide-up-main { animation: slideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
@keyframes slideUp { from { opacity: 0; transform: translateY(40px) scale(0.98); } to { opacity: 1; transform: translateY(0) scale(1); } }

.spring-message-enter-active { transition: all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1); }
.spring-message-enter-from { opacity: 0; transform: translateY(30px) scale(0.9); filter: blur(4px); }

.bg-grid-animate {
    background-image: linear-gradient(rgba(255, 255, 255, 0.03) 1px, transparent 1px),
    linear-gradient(90deg, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
    background-size: 50px 50px;
    mask-image: radial-gradient(circle at center, black 40%, transparent 100%);
    animation: gridMove 20s linear infinite;
}
@keyframes gridMove { 0% { transform: translateY(0); } 100% { transform: translateY(50px); } }

.custom-scrollbar::-webkit-scrollbar { width: 5px; }
.custom-scrollbar::-webkit-scrollbar-thumb { background-color: rgba(255, 255, 255, 0.1); border-radius: 20px; }
.custom-scrollbar::-webkit-scrollbar-track { background: transparent; }

.animate-blob { animation: blob 15s infinite; }
@keyframes blob {
    0% { transform: translate(0px, 0px) scale(1); }
    33% { transform: translate(30px, -50px) scale(1.1); }
    66% { transform: translate(-20px, 20px) scale(0.9); }
    100% { transform: translate(0px, 0px) scale(1); }
}
.animation-delay-2000 { animation-delay: 2s; }
.animation-delay-4000 { animation-delay: 4s; }

.fade-enter-active, .fade-leave-active { transition: opacity 0.3s; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>
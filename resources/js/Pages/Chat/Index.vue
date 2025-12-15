<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { ref, onMounted, nextTick } from 'vue';
import axios from 'axios';

// --- STATE ---
const page = usePage();
const currentUser = page.props.auth.user;
const users = ref([]);
const onlineUserIds = ref(new Set());
const messages = ref([]);
const activeUser = ref(null);
const newMessage = ref('');
const boxRef = ref(null);
const fileInput = ref(null);
const isTyping = ref(false); 

// --- METHODS ---
const loadMessages = async (user) => {
    activeUser.value = user;
    user.unread = 0;
    messages.value = [];
    try {
        const response = await axios.get(`/chat/messages/${user.id}`);
        messages.value = response.data;
        scrollToBottom();
        markAsRead();
    } catch (error) { console.error(error); }
};

const markAsRead = async () => {
    if (!activeUser.value) return;
    try { await axios.post('/chat/read', { sender_id: activeUser.value.id }); } catch (error) {}
};

const selectImage = () => fileInput.value.click();
const handleFileChange = (e) => { if (e.target.files[0]) sendMessage(e.target.files[0]); };

const sendMessage = async (file = null) => {
    if ((!newMessage.value.trim() && !file) || !activeUser.value) return;

    const formData = new FormData();
    formData.append('receiver_id', activeUser.value.id);
    if (newMessage.value) formData.append('content', newMessage.value);
    if (file) formData.append('image', file);

    const tempMsg = {
        id: Date.now(), // Temporary ID for animation
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

    isTyping.value = true;
    setTimeout(() => isTyping.value = false, 300); // Extended slightly for better visual

    try {
        await axios.post('/chat/send', formData, { headers: { 'Content-Type': 'multipart/form-data' }});
    } catch (e) { console.error(e); }
};

const scrollToBottom = () => {
    nextTick(() => { if (boxRef.value) boxRef.value.scrollTop = boxRef.value.scrollHeight; });
};

const formatTime = (date) => {
    return new Date(date).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
};

// --- REAL-TIME LISTENERS ---
onMounted(async () => {
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
            } else {
                const sender = users.value.find(user => user.id === e.message.user_id);
                if (sender) sender.unread = (sender.unread || 0) + 1;
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
            <div class="absolute inset-0 bg-grid-animate opacity-20"></div>
            <div class="absolute top-[-20%] left-[-10%] w-[800px] h-[800px] bg-indigo-600/20 rounded-full blur-[120px] animate-blob"></div>
            <div class="absolute bottom-[-20%] right-[-10%] w-[800px] h-[800px] bg-purple-600/20 rounded-full blur-[120px] animate-blob animation-delay-4000"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full h-full bg-radial-gradient from-transparent to-[#030305]"></div>
        </div>
        <div class="py-8 h-[calc(100vh-80px)] relative z-10">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 h-full">
                
                <div class="bg-white/80 dark:bg-[#0A0A12]/70 backdrop-blur-2xl shadow-2xl sm:rounded-2xl h-full flex overflow-hidden border border-white/20 dark:border-white/10 ring-1 ring-white/5 slide-up-main">
                    
                    <div class="w-1/3 md:w-80 border-r border-gray-200/50 dark:border-white/10 bg-gray-50/50 dark:bg-[#05050A]/50 flex flex-col">
                        <div class="p-6 border-b border-gray-200/50 dark:border-white/10 flex justify-between items-center bg-white/30 dark:bg-white/5">
                            <span class="font-extrabold text-gray-800 dark:text-white text-lg tracking-wide">Messages</span>
                            <span class="bg-blue-100 text-blue-700 text-xs px-3 py-1 rounded-full font-bold shadow-sm ring-1 ring-blue-200 dark:bg-indigo-500/20 dark:text-indigo-300 dark:ring-indigo-500/30">
                                {{ users.length }}
                            </span>
                        </div>
                        
                        <div class="overflow-y-auto flex-1 p-3 space-y-2 custom-scrollbar">
                            <button v-for="user in users" :key="user.id" @click="loadMessages(user)"
                                class="w-full p-3 flex items-center gap-4 rounded-xl transition-all duration-300 group relative overflow-hidden border border-transparent"
                                :class="activeUser?.id === user.id 
                                    ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg shadow-indigo-500/30 translate-x-1 border-indigo-400/30' 
                                    : 'hover:bg-white/50 dark:hover:bg-white/10 text-gray-700 dark:text-gray-300 hover:shadow-md hover:border-white/10 hover:translate-x-0.5'">
                                
                                <div v-if="activeUser?.id === user.id" class="absolute left-0 top-0 bottom-0 w-1 bg-white/50 animate-pulse"></div>

                                <div class="relative flex-shrink-0">
                                    <div class="w-12 h-12 rounded-full flex items-center justify-center font-bold text-lg shadow-inner transition-transform duration-300 group-hover:scale-110 group-hover:rotate-3"
                                         :class="activeUser?.id === user.id ? 'bg-white/20 text-white' : 'bg-gradient-to-tr from-gray-200 to-gray-100 dark:from-gray-800 dark:to-gray-700 text-gray-600 dark:text-gray-300'">
                                        {{ user.name.charAt(0).toUpperCase() }}
                                    </div>
                                    
                                    <span v-if="onlineUserIds.has(user.id)" class="absolute bottom-0 right-0 w-3.5 h-3.5 bg-green-500 border-2 border-white dark:border-[#05050A] rounded-full shadow-sm animate-pulse-slow"></span>
                                    <span v-if="user.unread > 0" class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 border-2 border-white dark:border-[#05050A] rounded-full flex items-center justify-center text-[10px] text-white font-bold shadow-md animate-bounce">
                                        {{ user.unread }}
                                    </span>
                                </div>
                                
                                <div class="flex-1 min-w-0 text-left">
                                    <div class="font-bold truncate text-sm group-hover:text-indigo-500 dark:group-hover:text-indigo-300 transition-colors">{{ user.name }}</div>
                                    <div class="text-xs truncate opacity-70 flex items-center gap-1">
                                        <span v-if="onlineUserIds.has(user.id)" class="w-1.5 h-1.5 rounded-full bg-green-400 inline-block shadow-[0_0_5px_rgba(74,222,128,0.8)]"></span>
                                        {{ onlineUserIds.has(user.id) ? 'Online' : 'Offline' }}
                                    </div>
                                </div>
                            </button>
                        </div>
                    </div>

                    <div class="flex-1 flex flex-col bg-white/50 dark:bg-[#05050A]/30 relative">
                        <div class="absolute inset-0 opacity-[0.05] pointer-events-none mix-blend-overlay" 
                             style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');">
                        </div>

                        <div v-if="activeUser" class="px-6 py-4 bg-white/80 dark:bg-[#0A0A12]/80 backdrop-blur-md border-b border-gray-200/50 dark:border-white/10 flex items-center justify-between z-10 sticky top-0 shadow-sm animate-fade-in-down">
                            <div class="flex items-center gap-4">
                                <div class="relative">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-blue-100 to-indigo-100 dark:from-gray-800 dark:to-gray-700 flex items-center justify-center text-blue-600 dark:text-blue-400 font-bold text-lg shadow-sm border border-white/10">
                                        {{ activeUser.name.charAt(0) }}
                                    </div>
                                    <span v-if="onlineUserIds.has(activeUser.id)" class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-white dark:border-[#05050A] rounded-full shadow-[0_0_8px_rgba(34,197,94,0.6)]"></span>
                                </div>
                                <div>
                                    <div class="font-bold text-gray-900 dark:text-white">{{ activeUser.name }}</div>
                                    <div class="text-xs font-medium flex items-center gap-1" :class="onlineUserIds.has(activeUser.id) ? 'text-green-500' : 'text-gray-400'">
                                        <span v-if="onlineUserIds.has(activeUser.id)" class="relative flex h-2 w-2">
                                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                          <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                                        </span>
                                        {{ onlineUserIds.has(activeUser.id) ? 'Active now' : 'Last seen recently' }}
                                    </div>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <button class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-white/10 rounded-full transition-all duration-200 hover:rotate-12">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
                                </button>
                                <button class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-white/10 rounded-full transition-all duration-200 hover:rotate-12">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z" /></svg>
                                </button>
                            </div>
                        </div>

                        <div v-else class="flex-1 flex flex-col items-center justify-center text-center p-8 opacity-0 animate-fade-in-up delay-200">
                            <div class="w-32 h-32 bg-blue-50 dark:bg-white/5 rounded-full flex items-center justify-center mb-6 shadow-inner border border-white/10 relative group">
                                <div class="absolute inset-0 bg-blue-500/20 rounded-full blur-xl group-hover:blur-2xl transition-all duration-500"></div>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-16 h-16 text-blue-500/50 relative z-10 group-hover:scale-110 transition-transform">
                                    <path fill-rule="evenodd" d="M4.848 2.771A49.144 49.144 0 0112 2.25c2.43 0 4.817.178 7.152.52 1.978.292 3.348 2.024 3.348 3.97v6.02c0 1.946-1.37 3.678-3.348 3.97a48.901 48.901 0 01-3.476.383.39.39 0 00-.297.17l-2.755 4.133a.75.75 0 01-1.248 0l-2.755-4.133a.39.39 0 00-.297-.17 48.9 48.9 0 01-3.476-.384c-1.978-.29-3.348-2.024-3.348-3.97V6.741c0-1.946 1.37-3.68 3.348-3.97z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-2">Welcome to Reverb</h3>
                            <p class="text-gray-500 max-w-sm">Select a contact from the sidebar to start a real-time conversation.</p>
                        </div>

                        <div v-if="activeUser" ref="boxRef" class="flex-1 overflow-y-auto p-6 space-y-6 custom-scrollbar relative z-0">
                            <TransitionGroup name="spring-message" tag="div" class="space-y-6 relative">
                                <div v-for="msg in messages" :key="msg.id" class="flex flex-col group">
                                    <div class="flex items-end gap-2" :class="msg.user_id === currentUser.id ? 'flex-row-reverse' : 'flex-row'">
                                        <div v-if="msg.user_id !== currentUser.id" class="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-xs font-bold text-gray-600 mb-1 shadow-sm">
                                            {{ activeUser.name.charAt(0) }}
                                        </div>

                                        <div class="max-w-[70%] px-5 py-3 shadow-lg relative text-sm transition-all duration-300 backdrop-blur-sm hover:scale-[1.02]"
                                            :class="[
                                                msg.user_id === currentUser.id 
                                                    ? 'bg-gradient-to-br from-blue-600 to-indigo-600 text-white rounded-2xl rounded-tr-sm hover:shadow-indigo-500/30' 
                                                    : 'bg-white dark:bg-white/10 text-gray-800 dark:text-gray-100 rounded-2xl rounded-tl-sm border border-gray-100 dark:border-white/10 hover:shadow-md',
                                                msg.image_path ? 'p-2' : ''
                                            ]">
                                            
                                            <div v-if="msg.image_path" class="mb-2 rounded-xl overflow-hidden shadow-sm relative group/img">
                                                <div class="absolute inset-0 bg-white/20 opacity-0 group-hover/img:opacity-100 transition-opacity z-10 pointer-events-none mix-blend-overlay"></div>
                                                <img :src="msg.image_path.startsWith('blob:') ? msg.image_path : '/storage/' + msg.image_path" class="w-full h-auto max-h-64 object-cover cursor-pointer">
                                            </div>
                                            
                                            <p v-if="msg.content" class="leading-relaxed" :class="msg.image_path ? 'px-2 pb-1' : ''">{{ msg.content }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-1 mt-1 opacity-0 text-[10px] font-medium transition-all duration-300 group-hover:opacity-100 group-hover:translate-y-0 translate-y-2" 
                                         :class="msg.user_id === currentUser.id ? 'justify-end pr-1' : 'justify-start pl-11'">
                                        <span class="text-gray-400">{{ formatTime(msg.created_at) }}</span>
                                        
                                        <span v-if="msg.user_id === currentUser.id" class="ml-1">
                                            <svg v-if="msg.read_at" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-3.5 h-3.5 text-green-500 drop-shadow-[0_0_5px_rgba(34,197,94,0.5)]">
                                                <path fill-rule="evenodd" d="M19.916 4.626a.75.75 0 0 1 .208 1.04l-9 13.5a.75.75 0 0 1-1.154.114l-6-6a.75.75 0 0 1 1.06-1.06l5.353 5.353 8.493-12.74a.75.75 0 0 1 1.04-.207Zm-13.84 9.04 1.415-1.415L9.917 14.67l7.769-11.652a.75.75 0 0 1 1.227.872l-8.5 12.75a.75.75 0 0 1-1.17.067l-3.208-3.207Z" clip-rule="evenodd" />
                                            </svg>
                                            <svg v-else-if="onlineUserIds.has(activeUser.id)" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-3.5 h-3.5 text-green-500">
                                                <path fill-rule="evenodd" d="M19.916 4.626a.75.75 0 0 1 .208 1.04l-9 13.5a.75.75 0 0 1-1.154.114l-6-6a.75.75 0 0 1 1.06-1.06l5.353 5.353 8.493-12.74a.75.75 0 0 1 1.04-.207Z" clip-rule="evenodd" />
                                            </svg>
                                            <svg v-else xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-3.5 h-3.5 text-gray-300">
                                                <path fill-rule="evenodd" d="M19.916 4.626a.75.75 0 0 1 .208 1.04l-9 13.5a.75.75 0 0 1-1.154.114l-6-6a.75.75 0 0 1 1.06-1.06l5.353 5.353 8.493-12.74a.75.75 0 0 1 1.04-.207Z" clip-rule="evenodd" />
                                            </svg>
                                        </span>
                                    </div>
                                </div>
                            </TransitionGroup>
                        </div>

                        <div v-if="activeUser" class="p-4 bg-white dark:bg-[#0A0A12]/90 border-t border-gray-100 dark:border-white/10 relative z-20">
                            <form @submit.prevent="sendMessage(null)" 
                                  class="flex gap-3 items-end max-w-4xl mx-auto bg-gray-50 dark:bg-white/5 p-2 rounded-[2rem] border border-gray-200 dark:border-white/10 shadow-sm transition-all duration-300 focus-within:shadow-lg focus-within:shadow-indigo-500/20 focus-within:border-indigo-500/50 focus-within:scale-[1.01] group/form">
                                
                                <input type="file" ref="fileInput" @change="handleFileChange" class="hidden" accept="image/*">
                                <button type="button" @click="selectImage" class="p-3 text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 bg-transparent hover:bg-blue-50 dark:hover:bg-white/10 rounded-full transition-all duration-200 hover:rotate-12 active:scale-90">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="m18.375 12.739-7.693 7.693a4.5 4.5 0 0 1-6.364-6.364l10.94-10.94A3 3 0 1 1 19.5 7.372L8.552 18.32m.009-.01-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 0 0 2.112 2.13" /></svg>
                                </button>

                                <input v-model="newMessage" type="text" 
                                    class="flex-1 bg-transparent border-none focus:ring-0 text-gray-700 dark:text-gray-200 placeholder-gray-400 px-2 py-3 transition-all" 
                                    placeholder="Type a message..."
                                    autocomplete="off"
                                >

                                <button type="submit" 
                                    class="p-3 rounded-full text-white shadow-lg transition-all duration-200 transform active:scale-75 flex items-center justify-center hover:shadow-xl"
                                    :class="(newMessage.trim() || fileInput) ? 'bg-gradient-to-r from-blue-600 to-indigo-600 hover:shadow-indigo-500/50 hover:-translate-y-0.5' : 'bg-gray-300 dark:bg-gray-700 cursor-not-allowed'"
                                    :disabled="!newMessage.trim() && !fileInput">
                                    <svg v-if="!isTyping" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 translate-x-0.5 transition-transform group-hover/form:translate-x-1"><path d="M3.478 2.404a.75.75 0 0 0-.926.941l2.432 7.905H13.5a.75.75 0 0 1 0 1.5H4.984l-2.432 7.905a.75.75 0 0 0 .926.94 60.519 60.519 0 0 0 18.445-8.986.75.75 0 0 0 0-1.218A60.517 60.517 0 0 0 3.478 2.404Z" /></svg>
                                    <div v-else class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
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
/* BACKGROUND ANIMATION CLASSES */
.bg-grid-animate {
    background-image: linear-gradient(rgba(255, 255, 255, 0.03) 1px, transparent 1px),
    linear-gradient(90deg, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
    background-size: 50px 50px;
    mask-image: radial-gradient(circle at center, black 40%, transparent 100%);
    animation: gridMove 20s linear infinite;
}

@keyframes gridMove {
    0% { transform: translateY(0); }
    100% { transform: translateY(50px); }
}

.animate-blob { animation: blob 15s infinite; }
@keyframes blob {
    0% { transform: translate(0px, 0px) scale(1); }
    33% { transform: translate(30px, -50px) scale(1.1); }
    66% { transform: translate(-20px, 20px) scale(0.9); }
    100% { transform: translate(0px, 0px) scale(1); }
}
.animation-delay-4000 { animation-delay: 4s; }

/* UI UTILS */
.animate-pulse-slow { animation: pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite; }

/* ENTRANCE ANIMATIONS */
@keyframes slideUpMain {
    from { opacity: 0; transform: translateY(40px) scale(0.98); }
    to { opacity: 1; transform: translateY(0) scale(1); }
}
.slide-up-main { animation: slideUpMain 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; }

@keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
.animate-fade-in-up { animation: fadeInUp 0.6s ease-out forwards; }

@keyframes fadeInDown { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
.animate-fade-in-down { animation: fadeInDown 0.6s ease-out forwards; }

/* SPRINGY MESSAGE ANIMATIONS */
.spring-message-enter-active {
  transition: all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1); /* Bouncy spring */
}
.spring-message-leave-active {
  transition: all 0.3s ease-in;
}
.spring-message-enter-from {
  opacity: 0;
  transform: translateY(30px) scale(0.9);
  filter: blur(4px);
}
.spring-message-leave-to {
  opacity: 0;
  transform: scale(0.9);
  filter: blur(2px);
}

/* SCROLLBAR */
.custom-scrollbar::-webkit-scrollbar { width: 6px; }
.custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
.custom-scrollbar::-webkit-scrollbar-thumb { background-color: rgba(156, 163, 175, 0.3); border-radius: 20px; }
.custom-scrollbar::-webkit-scrollbar-thumb:hover { background-color: rgba(156, 163, 175, 0.5); }
</style>
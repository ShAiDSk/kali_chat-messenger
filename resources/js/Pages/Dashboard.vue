<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';

const page = usePage();
const user = page.props.auth.user;

// --- STATE ---
const time = ref('');
const date = ref('');
let timer = null;

// --- 3D TILT LOGIC ---
const handleTilt = (e) => {
    const card = e.currentTarget;
    const rect = card.getBoundingClientRect();
    const x = e.clientX - rect.left;
    const y = e.clientY - rect.top;
    
    const centerX = rect.width / 2;
    const centerY = rect.height / 2;
    
    const rotateX = ((y - centerY) / 10) * -1; // Invert rotation
    const rotateY = (x - centerX) / 10;

    card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale3d(1.02, 1.02, 1.02)`;
    
    // Move glare effect
    const glare = card.querySelector('.glare');
    if (glare) {
        glare.style.left = `${x}px`;
        glare.style.top = `${y}px`;
        glare.style.opacity = '1';
    }
};

const resetTilt = (e) => {
    const card = e.currentTarget;
    card.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg) scale3d(1, 1, 1)';
    const glare = card.querySelector('.glare');
    if (glare) glare.style.opacity = '0';
};

// --- LIFECYCLE ---
const updateTime = () => {
    const now = new Date();
    time.value = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' });
    date.value = now.toLocaleDateString([], { weekday: 'long', month: 'short', day: 'numeric' });
};

onMounted(() => {
    updateTime();
    timer = setInterval(updateTime, 1000);
    
    // Play subtle entrance sound
    const audio = new Audio('/sounds/pop.mp3'); // Reuse your pop sound
    audio.volume = 0.2;
    audio.play().catch(() => {}); 
});

onUnmounted(() => clearInterval(timer));
</script>

<template>
    <Head title="Command Deck" />

    <AuthenticatedLayout>
        <div class="fixed inset-0 z-0 pointer-events-none bg-[#030305]">
            <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-10 mix-blend-soft-light"></div>
            <div class="absolute inset-0 bg-grid-animate opacity-20"></div>
            <div class="absolute top-0 left-1/4 w-[600px] h-[600px] bg-indigo-600/10 rounded-full blur-[120px] animate-pulse-slow"></div>
            <div class="absolute bottom-0 right-1/4 w-[600px] h-[600px] bg-purple-600/10 rounded-full blur-[120px] animate-pulse-slow delay-1000"></div>
        </div>

        <div class="relative z-10 py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                
                <div class="flex justify-between items-end mb-8 animate-fade-in-down">
                    <div>
                        <h2 class="text-3xl font-black text-white tracking-tight uppercase flex items-center gap-3">
                            <span class="w-3 h-8 bg-indigo-500 rounded-sm shadow-[0_0_15px_rgba(99,102,241,0.8)]"></span>
                            Command Deck
                        </h2>
                        <p class="text-gray-400 font-mono text-xs mt-1 tracking-widest ml-6">SECURE UPLINK ESTABLISHED</p>
                    </div>
                    <div class="text-right hidden md:block">
                        <div class="text-3xl font-bold text-white font-mono">{{ time }}</div>
                        <div class="text-indigo-400 text-xs font-bold uppercase tracking-widest">{{ date }}</div>
                    </div>
                </div>

                <div class="relative group mb-12 animate-slide-up" style="animation-delay: 0.1s;">
                    <div class="absolute inset-0 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-3xl blur opacity-20 group-hover:opacity-40 transition-opacity duration-500"></div>
                    
                    <div class="relative bg-[#0A0A12]/80 backdrop-blur-2xl border border-white/10 p-10 rounded-3xl flex flex-col md:flex-row items-center justify-between gap-8 overflow-hidden">
                        <div class="absolute top-0 bottom-0 left-[-20%] w-20 bg-gradient-to-r from-transparent via-white/5 to-transparent skew-x-12 animate-scan pointer-events-none"></div>

                        <div>
                            <h1 class="text-4xl md:text-5xl font-black text-white tracking-tight mb-2">
                                Welcome, <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-cyan-400">{{ user.name }}</span>
                            </h1>
                            <p class="text-gray-400 font-mono text-sm flex items-center gap-3 mt-4">
                                <span class="relative flex h-3 w-3">
                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                  <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                                </span>
                                SYSTEM STATUS: <span class="text-green-400 font-bold">OPTIMAL</span>
                            </p>
                        </div>
                        
                        <Link :href="route('chat')" class="relative px-10 py-4 bg-white text-black font-bold rounded-xl overflow-hidden group/btn hover:scale-105 transition-transform shadow-[0_0_40px_-10px_rgba(255,255,255,0.3)]">
                            <span class="relative z-10 flex items-center gap-2 uppercase tracking-wider text-sm">
                                Launch Interface <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </span>
                            <div class="absolute inset-0 bg-gradient-to-r from-indigo-400 to-cyan-400 opacity-0 group-hover/btn:opacity-100 transition-opacity duration-300 mix-blend-color-burn"></div>
                        </Link>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    
                    <div class="card-3d relative h-48 rounded-3xl bg-[#0A0A12]/60 border border-white/10 backdrop-blur-xl group cursor-pointer transition-transform duration-100 ease-out"
                         @mousemove="handleTilt" @mouseleave="resetTilt">
                        <div class="glare absolute w-64 h-64 bg-white/10 rounded-full blur-3xl pointer-events-none opacity-0 transition-opacity duration-300 -translate-x-1/2 -translate-y-1/2"></div>
                        
                        <div class="p-8 h-full flex flex-col justify-between relative z-10">
                            <div class="flex justify-between items-start">
                                <div class="p-3 bg-indigo-500/10 rounded-xl text-indigo-400 group-hover:bg-indigo-500 group-hover:text-white transition-colors duration-300">
                                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" /></svg>
                                </div>
                                <span class="text-xs font-mono text-gray-500 group-hover:text-indigo-300 transition-colors">Total_Msg</span>
                            </div>
                            <div>
                                <div class="text-4xl font-black text-white mb-1">1,240</div>
                                <div class="w-full bg-gray-800 h-1 rounded-full overflow-hidden">
                                    <div class="bg-indigo-500 w-3/4 h-full animate-progress"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-3d relative h-48 rounded-3xl bg-[#0A0A12]/60 border border-white/10 backdrop-blur-xl group cursor-pointer transition-transform duration-100 ease-out"
                         @mousemove="handleTilt" @mouseleave="resetTilt">
                        <div class="glare absolute w-64 h-64 bg-white/10 rounded-full blur-3xl pointer-events-none opacity-0 transition-opacity duration-300 -translate-x-1/2 -translate-y-1/2"></div>
                        
                        <div class="p-8 h-full flex flex-col justify-between relative z-10">
                            <div class="flex justify-between items-start">
                                <div class="p-3 bg-purple-500/10 rounded-xl text-purple-400 group-hover:bg-purple-500 group-hover:text-white transition-colors duration-300">
                                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                                </div>
                                <span class="text-xs font-mono text-gray-500 group-hover:text-purple-300 transition-colors">Agents_Online</span>
                            </div>
                            <div>
                                <div class="text-4xl font-black text-white mb-1">5</div>
                                <div class="w-full bg-gray-800 h-1 rounded-full overflow-hidden">
                                    <div class="bg-purple-500 w-1/2 h-full animate-progress" style="animation-delay: 0.2s"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-3d relative h-48 rounded-3xl bg-[#0A0A12]/60 border border-white/10 backdrop-blur-xl group cursor-pointer transition-transform duration-100 ease-out"
                         @mousemove="handleTilt" @mouseleave="resetTilt">
                        <div class="glare absolute w-64 h-64 bg-white/10 rounded-full blur-3xl pointer-events-none opacity-0 transition-opacity duration-300 -translate-x-1/2 -translate-y-1/2"></div>
                        
                        <div class="p-8 h-full flex flex-col justify-between relative z-10">
                            <div class="flex justify-between items-start">
                                <div class="p-3 bg-green-500/10 rounded-xl text-green-400 group-hover:bg-green-500 group-hover:text-white transition-colors duration-300">
                                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01" /></svg>
                                </div>
                                <span class="text-xs font-mono text-gray-500 group-hover:text-green-300 transition-colors">Server_Heartbeat</span>
                            </div>
                            <div>
                                <div class="text-4xl font-black text-white mb-1">99.9%</div>
                                <div class="w-full bg-gray-800 h-1 rounded-full overflow-hidden">
                                    <div class="bg-green-500 w-full h-full animate-progress" style="animation-delay: 0.4s"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
/* BACKGROUND ANIMATION */
.bg-grid-animate {
    background-image: linear-gradient(rgba(255, 255, 255, 0.03) 1px, transparent 1px),
    linear-gradient(90deg, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
    background-size: 50px 50px;
    mask-image: radial-gradient(circle at center, black 40%, transparent 100%);
    animation: gridMove 20s linear infinite;
}
@keyframes gridMove { 0% { transform: translateY(0); } 100% { transform: translateY(50px); } }

.animate-pulse-slow { animation: pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite; }

/* SCANNING LIGHT */
@keyframes scan {
    0% { left: -20%; opacity: 0; }
    50% { opacity: 1; }
    100% { left: 120%; opacity: 0; }
}
.animate-scan { animation: scan 3s linear infinite; }

/* LOADING BARS */
@keyframes progress {
    0% { width: 0; }
    100% { width: var(--width, 100%); }
}
.animate-progress { animation: progress 1.5s ease-out forwards; }

/* ENTRANCE ANIMATIONS */
@keyframes slideUp { from { opacity: 0; transform: translateY(40px); } to { opacity: 1; transform: translateY(0); } }
.animate-slide-up { animation: slideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; }

@keyframes fadeInDown { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
.animate-fade-in-down { animation: fadeInDown 0.6s ease-out forwards; }

/* 3D CARD */
.card-3d {
    transform-style: preserve-3d;
    will-change: transform;
}
</style>
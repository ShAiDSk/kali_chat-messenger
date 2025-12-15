<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import NavBar from '@/Components/NavBar.vue'; // Uses the fixed NavBar

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});

// --- FIX: DEFINING THE MISSING VARIABLES ---
const heroCard = ref(null);
const mouseX = ref(0); // Added this
const mouseY = ref(0); // Added this
const typedText = ref('');
const fullText = "THE FUTURE OF CHAT.";
let typeIndex = 0;

// Demo Chat Data
const demoMessages = ref([]);
const fullDemoConversation = [
    { id: 1, type: 'ai', text: 'Reverb Protocol v2.0 Online. 🟢', delay: 800 },
    { id: 2, type: 'user', text: 'Latency check. How fast is this?', delay: 1800 },
    { id: 3, type: 'ai', text: 'Sub-millisecond response. Encrypted. ⚡', delay: 2800 },
];

// --- FIX: MATCHING FUNCTION NAME WITH TEMPLATE ---
const handleMouseMove = (e) => {
    if (!heroCard.value) return;

    const rect = heroCard.value.getBoundingClientRect();
    const x = e.clientX - rect.left;
    const y = e.clientY - rect.top;

    const centerX = rect.width / 2;
    const centerY = rect.height / 2;

    // Calculate rotation
    const rotateX = ((y - centerY) / 25) * -1;
    const rotateY = (x - centerX) / 25;

    // Store raw values for 3D transform in template
    mouseX.value = rotateY; // Assigning rotation values to X/Y
    mouseY.value = rotateX;
};

const resetTilt = () => {
    mouseX.value = 0;
    mouseY.value = 0;
};

// --- ANIMATION LOOP ---
const typeWriter = () => {
    if (typeIndex < fullText.length) {
        typedText.value += fullText.charAt(typeIndex);
        typeIndex++;
        setTimeout(typeWriter, 100);
    }
};

const playDemoChat = () => {
    fullDemoConversation.forEach(msg => {
        setTimeout(() => {
            demoMessages.value.push(msg);
        }, msg.delay);
    });
};

onMounted(() => {
    setTimeout(typeWriter, 500);
    playDemoChat();
});
</script>

<template>
    <Head title="Welcome" />

    <div @mousemove="handleMouseMove" @mouseleave="resetTilt"
        class="min-h-screen bg-[#05050A] text-white selection:bg-indigo-500 selection:text-white relative overflow-hidden font-sans perspective-1000">

        <div class="fixed inset-0 z-0 pointer-events-none">
            <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20"></div>
            <div class="absolute inset-0 grid-background">
                <div class="scan-line"></div>
            </div>
            <div v-for="n in 10" :key="n" class="particle" :style="`--delay: ${n * 2}s; --left: ${n * 10}%;`"></div>
            <div class="absolute top-[-10%] left-[20%] w-[500px] h-[500px] bg-purple-600/20 rounded-full blur-[120px] animate-blob"></div>
            <div class="absolute top-[20%] right-[-10%] w-[600px] h-[600px] bg-indigo-600/20 rounded-full blur-[120px] animate-blob animation-delay-2000"></div>
            <div class="absolute bottom-[-20%] left-[10%] w-[600px] h-[600px] bg-blue-600/20 rounded-full blur-[120px] animate-blob animation-delay-4000"></div>
        </div>

        <NavBar /> 

        <main class="relative z-10 mt-12 px-6 max-w-7xl mx-auto text-center flex flex-col items-center">

            <div class="animate-float">
                <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full border border-indigo-500/30 bg-indigo-500/10 text-indigo-300 text-xs font-bold uppercase tracking-widest hover:bg-indigo-500/20 transition cursor-pointer backdrop-blur-md">
                    <span class="w-2 h-2 rounded-full bg-indigo-400 animate-ping"></span>
                    Live Websockets
                </span>
            </div>

            <h1 class="mt-8 text-5xl md:text-8xl font-extrabold leading-tight tracking-tight">
                Real-Time Chat <br class="hidden md:block" />
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-400 animate-gradient">
                    {{ typedText }}<span class="animate-blink text-white">_</span>
                </span>
            </h1>

            <p class="mt-6 text-gray-400 text-lg md:text-xl max-w-2xl leading-relaxed">
                Experience sub-millisecond latency with our Laravel Reverb architecture.
                <span class="text-gray-200 font-semibold">Zero delay. Secure. Infinite scale.</span>
            </p>

            <div class="mt-10 flex flex-col sm:flex-row gap-4">
                <Link :href="route('register')"
                    class="relative px-8 py-4 bg-white text-black rounded-full font-bold text-lg transition-all shadow-[0_0_40px_-10px_rgba(255,255,255,0.3)] hover:scale-105 hover:shadow-[0_0_60px_-15px_rgba(255,255,255,0.5)]">
                    Start Chatting Now
                </Link>
                <a href="/#" class="px-8 py-4 bg-white/5 hover:bg-white/10 border border-white/10 text-gray-300 hover:text-white rounded-full font-bold text-lg transition-all backdrop-blur-sm hover:scale-105">
                    Live Demo
                </a>
            </div>

            <div class="mt-20 w-full max-w-5xl" style="perspective: 2000px;">
                <div ref="heroCard"
                    class="relative bg-[#0F1117]/90 border border-gray-800 rounded-2xl shadow-2xl overflow-hidden transition-transform duration-100 ease-out group"
                    :style="{ transform: `rotateY(${mouseX}deg) rotateX(${mouseY}deg)` }">

                    <div class="absolute inset-0 bg-gradient-to-tr from-white/5 to-transparent pointer-events-none z-20"></div>

                    <div class="h-10 bg-[#161b22] border-b border-gray-800 flex items-center px-4 gap-2 justify-between">
                        <div class="flex gap-2">
                            <div class="w-3 h-3 rounded-full bg-red-500/80"></div>
                            <div class="w-3 h-3 rounded-full bg-yellow-500/80"></div>
                            <div class="w-3 h-3 rounded-full bg-green-500/80"></div>
                        </div>
                        <div class="text-xs text-gray-600 font-mono">reverb-secure-channel: 8080</div>
                    </div>

                    <div class="p-4 grid grid-cols-4 gap-4 h-[500px] text-left">
                        <div class="col-span-1 bg-gray-900/50 rounded-xl border border-gray-800/50 p-4 space-y-4 hidden md:block backdrop-blur-md">
                            <div class="h-8 bg-gray-800 rounded-lg w-3/4 animate-pulse"></div>
                            <div class="space-y-3">
                                <div class="h-12 bg-gray-800/50 rounded-lg w-full flex items-center gap-3 px-3 hover:bg-white/5 transition-colors">
                                    <div class="w-8 h-8 rounded-full bg-indigo-500/20 relative">
                                        <div class="absolute bottom-0 right-0 w-2 h-2 bg-green-500 rounded-full border border-gray-900"></div>
                                    </div>
                                    <div class="w-20 h-3 bg-gray-700 rounded"></div>
                                </div>
                                <div class="h-12 bg-gray-800/50 rounded-lg w-full flex items-center gap-3 px-3">
                                    <div class="w-8 h-8 rounded-full bg-purple-500/20"></div>
                                    <div class="w-16 h-3 bg-gray-700 rounded"></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-span-4 md:col-span-3 bg-gray-900/30 rounded-xl border border-gray-800/50 relative flex flex-col p-6 backdrop-blur-md">
                            <div class="flex-1 space-y-6">
                                <TransitionGroup name="list">
                                    <div v-for="msg in demoMessages" :key="msg.id" class="flex gap-4" :class="{ 'flex-row-reverse': msg.type === 'user' }">
                                        <div class="w-8 h-8 rounded-full flex-shrink-0 flex items-center justify-center font-bold text-xs"
                                             :class="msg.type === 'ai' ? 'bg-indigo-500' : 'bg-purple-500'">
                                            {{ msg.type === 'ai' ? 'AI' : 'ME' }}
                                        </div>
                                        <div class="p-3 rounded-2xl max-w-sm text-sm text-gray-300 shadow-md"
                                             :class="msg.type === 'ai' ? 'bg-gray-800 rounded-tl-none' : 'bg-gradient-to-r from-indigo-600 to-purple-600 rounded-tr-none text-white'">
                                            {{ msg.text }}
                                        </div>
                                    </div>
                                </TransitionGroup>
                            </div>
                            
                            <div class="mt-4 h-12 bg-gray-800 rounded-full border border-gray-700 flex items-center px-4 justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="w-1 h-4 bg-indigo-500 animate-blink"></span>
                                    <span class="text-gray-500 text-sm">Type command...</span>
                                </div>
                                <div class="w-8 h-8 bg-indigo-600 rounded-full flex items-center justify-center shadow-lg cursor-pointer hover:scale-110 transition-transform">
                                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M12 5l7 7-7 7" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="features" class="mt-32 grid grid-cols-1 md:grid-cols-3 gap-8 w-full max-w-7xl pb-20">
                <div v-for="(feature, index) in features" :key="index"
                    class="p-8 rounded-3xl bg-white/5 border border-white/10 hover:bg-white/10 transition-all duration-300 group hover:-translate-y-2 backdrop-blur-sm relative overflow-hidden">
                    <div class="absolute -right-10 -top-10 w-32 h-32 bg-indigo-500/20 rounded-full blur-2xl group-hover:bg-indigo-500/30 transition-colors"></div>
                    <div class="w-12 h-12 bg-indigo-500/20 rounded-xl flex items-center justify-center mb-6 text-indigo-400 group-hover:text-white group-hover:scale-110 transition-all duration-300">
                        <component :is="feature.icon" class="w-6 h-6" />
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">{{ feature.title }}</h3>
                    <p class="text-gray-400 leading-relaxed">{{ feature.desc }}</p>
                </div>
            </div>

        </main>

        <footer class="mt-10 border-t border-white/5 py-12 text-center text-gray-500 text-sm relative z-10">
            <p>&copy; 2025 Reverb Messenger. Built on Kali Linux.</p>
        </footer>
    </div>
</template>

<script>
import { h } from 'vue';
const BoltIcon = h('svg', { fill: "none", viewBox: "0 0 24 24", stroke: "currentColor", strokeWidth: 2 }, [h('path', { strokeLinecap: "round", strokeLinejoin: "round", d: "M13 10V3L4 14h7v7l9-11h-7z" })]);
const LockIcon = h('svg', { fill: "none", viewBox: "0 0 24 24", stroke: "currentColor", strokeWidth: 2 }, [h('path', { strokeLinecap: "round", strokeLinejoin: "round", d: "M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" })]);
const PhotoIcon = h('svg', { fill: "none", viewBox: "0 0 24 24", stroke: "currentColor", strokeWidth: 2 }, [h('path', { strokeLinecap: "round", strokeLinejoin: "round", d: "M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" })]);

export default {
    data() {
        return {
            features: [
                { title: 'Instant Sync', desc: 'Powered by WebSockets, messages are delivered in real-time. No page refreshes.', icon: BoltIcon },
                { title: 'Secure Channels', desc: 'Every conversation is isolated in its own secure channel using Reverb auth.', icon: LockIcon },
                { title: 'Media Sharing', desc: 'Share images instantly with dynamic preview generation and optimized storage.', icon: PhotoIcon },
            ]
        }
    }
}
</script>

<style scoped>
/* BACKGROUND GRID ANIMATION */
.grid-background {
    background-image: linear-gradient(rgba(255, 255, 255, 0.03) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
    background-size: 50px 50px;
}

.scan-line {
    width: 100%;
    height: 5px;
    background: linear-gradient(to bottom, rgba(99, 102, 241, 0), rgba(99, 102, 241, 0.5));
    position: absolute;
    top: 0;
    animation: scan 8s linear infinite;
    box-shadow: 0 0 20px rgba(99, 102, 241, 0.4);
}

@keyframes scan {
    0% { top: -10%; opacity: 0; }
    10% { opacity: 1; }
    90% { opacity: 1; }
    100% { top: 110%; opacity: 0; }
}

.particle {
    position: absolute;
    bottom: -10px;
    width: 4px;
    height: 4px;
    background: rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    animation: floatUp 10s linear infinite;
    animation-delay: var(--delay);
    left: var(--left);
}

@keyframes floatUp {
    0% { transform: translateY(0) scale(1); opacity: 0; }
    20% { opacity: 1; }
    100% { transform: translateY(-100vh) scale(0); opacity: 0; }
}

.animate-blink { animation: blink 1s step-end infinite; }
@keyframes blink { 0%, 100% { opacity: 1; } 50% { opacity: 0; } }

.animate-float { animation: float 6s ease-in-out infinite; }
@keyframes float { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-10px); } }

.perspective-1000 { perspective: 1000px; }

.animate-blob { animation: blob 15s infinite; }
@keyframes blob {
    0% { transform: translate(0px, 0px) scale(1); }
    33% { transform: translate(30px, -50px) scale(1.1); }
    66% { transform: translate(-20px, 20px) scale(0.9); }
    100% { transform: translate(0px, 0px) scale(1); }
}

/* LIST ANIMATION FOR CHAT DEMO */
.list-enter-active,
.list-leave-active {
  transition: all 0.5s ease;
}
.list-enter-from,
.list-leave-to {
  opacity: 0;
  transform: translateX(-30px);
}
</style>
<script setup>
import { ref, onMounted, computed } from 'vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link, usePage } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);
const page = usePage();

// FIX: Make currentUser reactive so it handles null (logged out) states safely
const currentUser = computed(() => page.props.auth.user);
const unreadCount = ref(0);

onMounted(() => {
    if (currentUser.value && window.Echo) {
        window.Echo.private(`chat.${currentUser.value.id}`)
            .listen('PrivateMessageSent', (e) => {
                if (!window.location.pathname.includes('/chat')) {
                    unreadCount.value++;
                    new Audio('/sounds/pop.mp3').play().catch(()=>{});
                }
            });
    }
});
</script>

<template>
    <nav class="sticky top-0 z-50 w-full bg-[#030305]/90 backdrop-blur-2xl border-b border-white/10 shadow-[0_4px_30px_rgba(0,0,0,0.5)] transition-all duration-300">
        <div class="absolute top-0 left-0 w-full h-[1px] bg-gradient-to-r from-transparent via-indigo-500/50 to-transparent"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                
                <div class="flex items-center gap-10">
                    <Link :href="route('home')" class="flex items-center gap-3 group">
                        <div class="relative w-10 h-10 flex items-center justify-center">
                            <div class="absolute inset-0 bg-indigo-600 rounded-xl blur-lg opacity-40 group-hover:opacity-100 transition-opacity duration-500"></div>
                            <div class="relative w-full h-full rounded-xl bg-gradient-to-br from-[#1a1a2e] to-[#16213e] border border-white/10 flex items-center justify-center text-white shadow-2xl group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            </div>
                        </div>
                        <span class="text-2xl font-black tracking-tighter text-white group-hover:text-indigo-400 transition-colors">
                            Kali-Chat<span class="text-indigo-600"></span>
                        </span>
                    </Link>

                    <div v-if="currentUser" class="hidden space-x-1 sm:-my-px sm:flex items-center bg-white/5 rounded-full px-2 py-1.5 border border-white/5">
                        
                        <NavLink :href="route('home')" :active="route().current('home')"
                            class="px-6 py-2 rounded-full text-xs font-bold uppercase tracking-wider transition-all duration-300"
                            :class="route().current('home') ? 'text-white bg-white/10 shadow-lg font-extrabold' : 'text-gray-300 hover:text-white'">
                            Home
                        </NavLink>

                        <NavLink :href="route('dashboard')" :active="route().current('dashboard')"
                            class="px-6 py-2 rounded-full text-xs font-bold uppercase tracking-wider transition-all duration-300"
                            :class="route().current('dashboard') ? 'text-white bg-white/10 shadow-lg font-extrabold' : 'text-gray-300 hover:text-white'">
                            Dashboard
                        </NavLink>
                        
                        <NavLink :href="route('chat')" :active="route().current('chat')" 
                            class="px-6 py-2 rounded-full text-xs font-bold uppercase tracking-wider transition-all duration-300 flex items-center gap-2"
                            :class="route().current('chat') ? 'text-white bg-white/10 shadow-lg font-extrabold' : 'text-gray-300 hover:text-white'">
                            Messenger
                            <span v-if="unreadCount > 0" class="flex h-2 w-2 relative">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                            </span>
                        </NavLink>
                    </div>
                </div>

                <div class="hidden sm:flex sm:items-center sm:ml-6">
                    <Dropdown v-if="currentUser" align="right" width="48">
                        <template #trigger>
                            <button class="flex items-center gap-4 pl-4 pr-2 py-2 rounded-full border border-white/10 bg-[#0A0A12] hover:bg-[#151520] transition-all duration-300 group shadow-lg cursor-pointer hover:border-indigo-500/50">
                                <div class="text-right hidden lg:block">
                                    <div class="text-sm font-bold text-white leading-none group-hover:text-indigo-200 transition-colors">{{ currentUser.name }}</div>
                                    <div class="text-[10px] font-mono text-green-400 leading-none mt-1 font-bold">ONLINE</div>
                                </div>
                                <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-indigo-600 to-purple-600 p-[2px]">
                                    <div class="w-full h-full rounded-full bg-[#05050A] flex items-center justify-center text-white text-sm font-bold shadow-inner">
                                        {{ currentUser.name.charAt(0) }}
                                    </div>
                                </div>
                            </button>
                        </template>
                        <template #content>
                            <div class="px-4 py-3 border-b border-gray-800 bg-[#0A0A12]">
                                <p class="text-[10px] text-gray-400 uppercase font-black tracking-widest">Account</p>
                                <p class="text-sm font-bold text-white truncate font-mono">{{ currentUser.email }}</p>
                            </div>
                            <div class="bg-[#0A0A12]">
                                <DropdownLink :href="route('profile.edit')" class="text-green-300 hover:text-white font-medium hover:bg-white/5 transition-colors">Profile</DropdownLink>
                                <DropdownLink :href="route('logout')" method="post" as="button" class="text-red-400 hover:text-red-300 hover:bg-red-900/20 font-medium transition-colors">Log Out</DropdownLink>
                            </div>
                        </template>
                    </Dropdown>

                    <div v-else class="flex items-center gap-4">
                        <Link :href="route('login')" class="text-xs font-bold text-gray-300 hover:text-white transition uppercase tracking-widest hover:underline decoration-indigo-500 decoration-2 underline-offset-4">
                            Log In
                        </Link>
                        <Link :href="route('register')" class="group relative px-6 py-2.5 bg-white text-black rounded-full text-xs font-black uppercase tracking-widest hover:scale-105 transition-transform shadow-lg shadow-white/10 overflow-hidden">
                            <span class="relative z-10">Get Started</span>
                            <div class="absolute inset-0 bg-gradient-to-r from-indigo-400 to-cyan-400 opacity-0 group-hover:opacity-100 transition-opacity duration-300 mix-blend-screen"></div>
                        </Link>
                    </div>
                </div>

                <div class="-mr-2 flex items-center sm:hidden">
                    <button @click="showingNavigationDropdown = !showingNavigationDropdown" class="p-2 rounded-lg text-gray-300 hover:text-white hover:bg-white/10 transition border border-transparent hover:border-white/10">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{ hidden: showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{ hidden: !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }" class="sm:hidden border-t border-white/10 bg-[#030305]/95 backdrop-blur-xl">
            <div v-if="currentUser" class="pt-2 pb-3 space-y-1 px-2">
                <ResponsiveNavLink :href="route('home')" :active="route().current('home')" class="text-gray-300 hover:text-white hover:bg-white/5 rounded-lg">Home</ResponsiveNavLink>
                <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')" class="text-gray-300 hover:text-white hover:bg-white/5 rounded-lg">Dashboard</ResponsiveNavLink>
                <ResponsiveNavLink :href="route('chat')" :active="route().current('chat')" class="text-gray-300 hover:text-white hover:bg-white/5 rounded-lg flex justify-between items-center">
                    Messenger
                    <span v-if="unreadCount > 0" class="bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full ml-2">{{ unreadCount }}</span>
                </ResponsiveNavLink>
            </div>
            
            <div v-if="currentUser" class="pt-4 pb-1 border-t border-white/10 px-2">
                <div class="px-4 py-2 bg-white/5 rounded-lg mb-2">
                    <div class="font-bold text-base text-white">{{ currentUser.name }}</div>
                    <div class="font-medium text-xs text-gray-400">{{ currentUser.email }}</div>
                </div>
                <div class="mt-1 space-y-1">
                    <ResponsiveNavLink :href="route('profile.edit')" class="text-gray-400 hover:text-white hover:bg-white/5 rounded-lg">Profile</ResponsiveNavLink>
                    <ResponsiveNavLink :href="route('logout')" method="post" as="button" class="text-red-400 hover:text-red-300 hover:bg-red-900/20 rounded-lg w-full text-left">Log Out</ResponsiveNavLink>
                </div>
            </div>

            <div v-else class="pt-4 pb-4 border-t border-white/10 space-y-3 px-4">
                <Link :href="route('login')" class="block w-full text-center py-3 text-gray-300 border border-white/10 rounded-xl hover:bg-white/5 hover:text-white transition font-bold text-sm uppercase tracking-wide">Log In</Link>
                <Link :href="route('register')" class="block w-full text-center py-3 bg-white text-black font-black rounded-xl hover:bg-gray-200 transition text-sm uppercase tracking-wide shadow-lg">Get Started</Link>
            </div>
        </div>
    </nav>
</template>
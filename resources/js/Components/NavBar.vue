<script setup>
import { ref, onMounted } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link, usePage } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);
const page = usePage();
const currentUser = page.props.auth.user;
const unreadCount = ref(0);

onMounted(() => {
    if (currentUser) {
        // SAFETY CHECK: Wait until Echo is actually loaded
        const interval = setInterval(() => {
            if (window.Echo) {
                clearInterval(interval);

                // Start Listening Globally
                window.Echo.private(`chat.${currentUser.id}`)
                    .listen('PrivateMessageSent', (e) => {
                        if (!window.location.pathname.includes('/chat')) {
                            unreadCount.value++;
                        }
                    });
            }
        }, 100);
    }
});
</script>

<template>
    <nav class="sticky top-0 z-50 bg-white/80 dark:bg-gray-900/80 backdrop-blur-md border-b border-gray-100 dark:border-gray-800 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center gap-8">
                    <div class="shrink-0 flex items-center">
                        <Link :href="route('dashboard')" class="flex items-center gap-2 group">
                            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-600 to-indigo-600 flex items-center justify-center text-white shadow-lg shadow-blue-500/30 transition-transform group-hover:scale-110">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 0 1-.825-.242m9.345-8.334a2.126 2.126 0 0 0-.476-.095 48.64 48.64 0 0 0-8.048 0c-1.18.063-2.33.129-3.45.196m2.425-5.942.306 4.755 1.768-.96 1.768.96.306-4.755c.162-2.502.527-3.812 1.436-4.694C16.59 2.162 18.23 2 19.664 2c.795 0 1.539.192 2.196.536.657.344 1.209.846 1.62 1.488.411.642.613 1.412.556 2.22l-1.002 10.02a2.25 2.25 0 0 1-2.235 2.03h-1.036" />
                                </svg>
                            </div>
                            <span class="font-bold text-xl tracking-tight text-gray-800 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                Kali-Chat
                            </span>
                        </Link>
                    </div>

                    <div class="hidden space-x-1 sm:-my-px sm:flex items-center">
                        <NavLink :href="route('dashboard')" :active="route().current('dashboard')"
                            class="rounded-md px-3 py-2 text-sm font-medium transition-colors hover:bg-gray-100 dark:hover:bg-gray-800 border-none"
                            :class="route().current('dashboard') ? 'bg-gray-100 dark:bg-gray-800 text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400'">
                            Dashboard
                        </NavLink>
                        
                        <NavLink :href="route('chat')" :active="route().current('chat')" 
                            class="relative rounded-md px-3 py-2 text-sm font-medium transition-colors hover:bg-gray-100 dark:hover:bg-gray-800 border-none"
                            :class="route().current('chat') ? 'bg-gray-100 dark:bg-gray-800 text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400'">
                            Messenger
                            <span v-if="unreadCount > 0" 
                                  class="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full bg-gradient-to-r from-red-500 to-pink-500 text-[10px] text-white font-bold shadow-lg shadow-red-500/40 animate-pulse border-2 border-white dark:border-gray-900">
                                {{ unreadCount > 9 ? '9+' : unreadCount }}
                            </span>
                        </NavLink>
                    </div>
                </div>

                <div class="hidden sm:flex sm:items-center sm:ml-6">
                    <Dropdown align="right" width="48">
                        <template #trigger>
                            <span class="inline-flex rounded-md">
                                <button type="button" class="group inline-flex items-center gap-2 px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-full text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-800 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none transition ease-in-out duration-150">
                                    <div class="w-6 h-6 rounded-full bg-gradient-to-tr from-blue-500 to-purple-500 flex items-center justify-center text-white text-xs font-bold">
                                        {{ currentUser.name.charAt(0) }}
                                    </div>
                                    {{ currentUser.name }}
                                    <svg class="ml-1 -mr-0.5 h-4 w-4 text-gray-400 group-hover:text-gray-600 transition-transform group-hover:rotate-180" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </span>
                        </template>

                        <template #content>
                            <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Signed in as</p>
                                <p class="text-sm font-bold text-gray-900 dark:text-white truncate">{{ currentUser.email }}</p>
                            </div>
                            <DropdownLink :href="route('profile.edit')"> Profile </DropdownLink>
                            
                            <DropdownLink :href="route('logout')" method="post" as="button" class="text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20">
                                Log Out
                            </DropdownLink>
                        </template>
                    </Dropdown>
                </div>

                <div class="-mr-2 flex items-center sm:hidden">
                    <button @click="showingNavigationDropdown = !showingNavigationDropdown" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{ hidden: showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{ hidden: !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }" class="sm:hidden border-t border-gray-100 dark:border-gray-800 bg-white/95 dark:bg-gray-900/95 backdrop-blur-xl">
            <div class="pt-2 pb-3 space-y-1">
                <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                    Dashboard
                </ResponsiveNavLink>
                <ResponsiveNavLink :href="route('chat')" :active="route().current('chat')" class="flex justify-between items-center pr-4">
                    <span>Messenger</span>
                    <span v-if="unreadCount > 0" class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full shadow-sm animate-pulse">
                        {{ unreadCount }} New
                    </span>
                </ResponsiveNavLink>
            </div>

            <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-700">
                <div class="px-4 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-blue-500 to-purple-500 flex items-center justify-center text-white font-bold shadow-md">
                        {{ currentUser.name.charAt(0) }}
                    </div>
                    <div>
                        <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ currentUser.name }}</div>
                        <div class="font-medium text-sm text-gray-500">{{ currentUser.email }}</div>
                    </div>
                </div>

                <div class="mt-3 space-y-1">
                    <ResponsiveNavLink :href="route('profile.edit')"> Profile </ResponsiveNavLink>
                    <ResponsiveNavLink :href="route('logout')" method="post" as="button" class="text-red-600 dark:text-red-400">
                        Log Out
                    </ResponsiveNavLink>
                </div>
            </div>
        </div>
    </nav>
</template>
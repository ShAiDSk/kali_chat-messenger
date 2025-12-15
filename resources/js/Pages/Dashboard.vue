<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Command Deck" />

    <AuthenticatedLayout>
        <div class="fixed inset-0 z-0 pointer-events-none bg-[#030305]">
            <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20 mix-blend-soft-light"></div>
            <div class="absolute top-[-20%] left-[-10%] w-[800px] h-[800px] bg-indigo-600/10 rounded-full blur-[150px] animate-pulse-slow"></div>
            <div class="absolute bottom-[-20%] right-[-10%] w-[800px] h-[800px] bg-purple-600/10 rounded-full blur-[150px] animate-pulse-slow delay-1000"></div>
            <div class="absolute inset-0" style="background-image: linear-gradient(rgba(255, 255, 255, 0.03) 1px, transparent 1px), linear-gradient(90deg, rgba(255, 255, 255, 0.03) 1px, transparent 1px); background-size: 50px 50px; mask-image: radial-gradient(circle at center, black, transparent 80%);"></div>
        </div>

        <div class="relative z-10 py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                
                <div class="relative group mb-12">
                    <div class="absolute inset-0 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-3xl blur opacity-20 group-hover:opacity-40 transition-opacity duration-500"></div>
                    <div class="relative bg-[#0A0A12]/80 backdrop-blur-2xl border border-white/10 p-10 rounded-3xl flex flex-col md:flex-row items-center justify-between gap-8">
                        <div>
                            <h1 class="text-5xl font-black text-white tracking-tight mb-2">
                                Hello, <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-cyan-400">{{ $page.props.auth.user.name }}</span>
                            </h1>
                            <p class="text-gray-400 font-mono text-sm flex items-center gap-3">
                                <span class="relative flex h-3 w-3">
                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                  <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                                </span>
                                SECURE CONNECTION ESTABLISHED // REVERB PROTOCOL V2.0
                            </p>
                        </div>
                        <Link :href="route('chat')" class="relative px-10 py-4 bg-white text-black font-bold rounded-xl overflow-hidden group/btn hover:scale-105 transition-transform">
                            <span class="relative z-10 flex items-center gap-2">
                                INITIATE CHAT <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </span>
                            <div class="absolute inset-0 bg-gradient-to-r from-indigo-400 to-cyan-400 opacity-0 group-hover/btn:opacity-100 transition-opacity duration-300 mix-blend-color-burn"></div>
                        </Link>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="group relative p-1 rounded-2xl bg-gradient-to-b from-white/10 to-transparent hover:from-indigo-500/50 transition-all duration-500">
                        <div class="bg-[#08080C] p-6 rounded-xl h-full relative overflow-hidden">
                            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                                <svg class="w-24 h-24 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" /></svg>
                            </div>
                            <div class="text-gray-500 text-xs font-mono mb-2 uppercase tracking-widest">Total Transmissions</div>
                            <div class="text-4xl font-black text-white group-hover:text-indigo-400 transition-colors">1,240</div>
                            <div class="w-full h-1 bg-gray-800 mt-4 rounded-full overflow-hidden">
                                <div class="h-full bg-indigo-500 w-3/4 animate-pulse"></div>
                            </div>
                        </div>
                    </div>

                    <div class="group relative p-1 rounded-2xl bg-gradient-to-b from-white/10 to-transparent hover:from-purple-500/50 transition-all duration-500">
                        <div class="bg-[#08080C] p-6 rounded-xl h-full relative overflow-hidden">
                            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                                <svg class="w-24 h-24 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                            </div>
                            <div class="text-gray-500 text-xs font-mono mb-2 uppercase tracking-widest">Active Agents</div>
                            <div class="text-4xl font-black text-white group-hover:text-purple-400 transition-colors">5</div>
                            <div class="w-full h-1 bg-gray-800 mt-4 rounded-full overflow-hidden">
                                <div class="h-full bg-purple-500 w-1/2 animate-pulse"></div>
                            </div>
                        </div>
                    </div>

                    <div class="group relative p-1 rounded-2xl bg-gradient-to-b from-white/10 to-transparent hover:from-green-500/50 transition-all duration-500">
                        <div class="bg-[#08080C] p-6 rounded-xl h-full relative overflow-hidden">
                            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                                <svg class="w-24 h-24 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01" /></svg>
                            </div>
                            <div class="text-gray-500 text-xs font-mono mb-2 uppercase tracking-widest">Server Uptime</div>
                            <div class="text-4xl font-black text-white group-hover:text-green-400 transition-colors">99.9%</div>
                            <div class="w-full h-1 bg-gray-800 mt-4 rounded-full overflow-hidden">
                                <div class="h-full bg-green-500 w-full animate-pulse"></div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.animate-pulse-slow { animation: pulse 8s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
</style>
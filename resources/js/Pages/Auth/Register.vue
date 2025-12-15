<script setup>
import InputError from '@/Components/InputError.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Register" />

    <div class="min-h-screen flex bg-white dark:bg-gray-950 text-gray-900 dark:text-gray-100 font-sans selection:bg-blue-500 selection:text-white">
        
        <div class="w-full lg:w-1/2 flex flex-col justify-center p-8 sm:p-12 lg:p-24 relative z-10">
            <div class="lg:hidden absolute top-0 left-0 w-full h-full overflow-hidden -z-10 pointer-events-none">
                <div class="absolute top-[-20%] right-[-20%] w-[80%] h-[80%] bg-indigo-600/10 rounded-full blur-3xl"></div>
            </div>

            <div class="mb-10 flex items-center gap-2">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-600 to-purple-600 flex items-center justify-center text-white shadow-lg shadow-indigo-500/20">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                </div>
                <span class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Kali-Chat</span>
            </div>

            <div class="mb-8">
                <h1 class="text-4xl font-extrabold tracking-tight mb-2 text-transparent bg-clip-text bg-gradient-to-r from-gray-900 to-gray-600 dark:from-white dark:to-gray-400">
                    Create Account
                </h1>
                <p class="text-gray-500 dark:text-gray-400 text-lg">
                    Join the community and start chatting in seconds.
                </p>
            </div>

            <form @submit.prevent="submit" class="space-y-5">
                <div class="space-y-2">
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Full Name</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-indigo-500 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5"><path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM3.751 20.105a8.25 8.25 0 0116.498 0 .75.75 0 01-.437.695A18.683 18.683 0 0112 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 01-.437-.695z" clip-rule="evenodd" /></svg>
                        </div>
                        <input id="name" type="text" v-model="form.name" required autofocus
                            class="block w-full pl-11 pr-4 py-3.5 bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 dark:text-white dark:placeholder-gray-500 transition-all shadow-sm group-hover:border-gray-300 dark:group-hover:border-gray-600"
                            placeholder="John Doe">
                    </div>
                    <InputError :message="form.errors.name" />
                </div>

                <div class="space-y-2">
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email Address</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-indigo-500 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5"><path d="M1.5 8.67v8.58a3 3 0 003 3h15a3 3 0 003-3V8.67l-8.928 5.493a3 3 0 01-3.144 0L1.5 8.67z" /><path d="M22.5 6.908V6.75a3 3 0 00-3-3h-15a3 3 0 00-3 3v.158l9.714 5.978a1.5 1.5 0 001.572 0L22.5 6.908z" /></svg>
                        </div>
                        <input id="email" type="email" v-model="form.email" required
                            class="block w-full pl-11 pr-4 py-3.5 bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 dark:text-white dark:placeholder-gray-500 transition-all shadow-sm group-hover:border-gray-300 dark:group-hover:border-gray-600"
                            placeholder="name@company.com">
                    </div>
                    <InputError :message="form.errors.email" />
                </div>

                <div class="space-y-2">
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-indigo-500 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5"><path fill-rule="evenodd" d="M12 1.5a5.25 5.25 0 00-5.25 5.25v3a3 3 0 00-3 3v6.75a3 3 0 003 3h10.5a3 3 0 003-3v-6.75a3 3 0 00-3-3v-3c0-2.9-2.35-5.25-5.25-5.25zm3.75 8.25v-3a3.75 3.75 0 10-7.5 0v3h7.5z" clip-rule="evenodd" /></svg>
                        </div>
                        <input id="password" type="password" v-model="form.password" required autocomplete="new-password"
                            class="block w-full pl-11 pr-4 py-3.5 bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 dark:text-white dark:placeholder-gray-500 transition-all shadow-sm group-hover:border-gray-300 dark:group-hover:border-gray-600"
                            placeholder="••••••••">
                    </div>
                    <InputError :message="form.errors.password" />
                </div>

                <div class="space-y-2">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Confirm Password</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-indigo-500 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5"><path fill-rule="evenodd" d="M12 1.5a5.25 5.25 0 00-5.25 5.25v3a3 3 0 00-3 3v6.75a3 3 0 003 3h10.5a3 3 0 003-3v-6.75a3 3 0 00-3-3v-3c0-2.9-2.35-5.25-5.25-5.25zm3.75 8.25v-3a3.75 3.75 0 10-7.5 0v3h7.5z" clip-rule="evenodd" /></svg>
                        </div>
                        <input id="password_confirmation" type="password" v-model="form.password_confirmation" required autocomplete="new-password"
                            class="block w-full pl-11 pr-4 py-3.5 bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 dark:text-white dark:placeholder-gray-500 transition-all shadow-sm group-hover:border-gray-300 dark:group-hover:border-gray-600"
                            placeholder="••••••••">
                    </div>
                    <InputError :message="form.errors.password_confirmation" />
                </div>

                <button type="submit" 
                        class="w-full py-3.5 px-4 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-500/30 transform transition-all duration-200 hover:scale-[1.02] active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
                        :disabled="form.processing">
                    <span v-if="form.processing">Creating Account...</span>
                    <span v-else>Create Account</span>
                </button>

                <p class="text-center text-sm text-gray-500 dark:text-gray-400 mt-4">
                    Already have an account? 
                    <Link :href="route('login')" class="font-bold text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 transition-colors">Log in</Link>
                </p>
            </form>
        </div>

        <div class="hidden lg:flex w-1/2 bg-gray-900 relative overflow-hidden items-center justify-center p-12">
            <div class="absolute inset-0">
                <div class="absolute top-[-10%] right-[-10%] w-[600px] h-[600px] bg-indigo-600/30 rounded-full blur-[100px] animate-pulse-slow"></div>
                <div class="absolute bottom-[-10%] left-[-10%] w-[600px] h-[600px] bg-purple-600/30 rounded-full blur-[100px] animate-pulse-slow delay-1000"></div>
                <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20"></div>
            </div>

            <div class="relative z-10 w-full max-w-md p-10 bg-white/10 backdrop-blur-xl border border-white/10 rounded-3xl shadow-2xl text-white">
                <div class="w-12 h-12 bg-indigo-500/20 rounded-xl flex items-center justify-center mb-6">
                    <svg class="w-6 h-6 text-indigo-400" fill="currentColor" viewBox="0 0 24 24"><path d="M11.7 2.805a.75.75 0 01.6 0A60.65 60.65 0 0122.83 8.72a.75.75 0 01-.231 1.337 49.949 49.949 0 00-9.902 3.912l-.003.002-.34.18a.75.75 0 01-.707 0A50.009 50.009 0 007.5 12.174v-.224c0-.131.067-.248.182-.311a51.02 51.02 0 01-.182-3.12 51.02 51.02 0 01.182-3.12 60.65 60.65 0 018.72-5.918zM9 13.5v-2.57c.436-.131.882-.252 1.332-.364.552.16 1.12.306 1.7.439.636.143 1.285.26 1.948.348v1.734c0 .506-.356.938-.847.986-1.372.136-2.776.136-4.148 0a.992.992 0 01-.847-.986zM4.5 9.75v8.25a.75.75 0 001.5 0v-8.25a.75.75 0 00-1.5 0z" /></svg>
                </div>
                <h3 class="text-3xl font-bold mb-4 leading-tight">Secure by Design.</h3>
                <p class="text-gray-300 text-lg leading-relaxed mb-8">
                    "We built Reverb to prioritize security without sacrificing speed. Your conversations are yours alone."
                </p>
                
                <div class="flex flex-col gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-5 h-5 rounded-full bg-green-500/20 flex items-center justify-center">
                            <svg class="w-3 h-3 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                        </div>
                        <span class="text-gray-200 text-sm">End-to-end encrypted channels</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-5 h-5 rounded-full bg-green-500/20 flex items-center justify-center">
                            <svg class="w-3 h-3 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                        </div>
                        <span class="text-gray-200 text-sm">Instant message delivery</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-5 h-5 rounded-full bg-green-500/20 flex items-center justify-center">
                            <svg class="w-3 h-3 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                        </div>
                        <span class="text-gray-200 text-sm">99.9% Server Uptime</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.animate-pulse-slow {
    animation: pulse 8s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}
@keyframes pulse {
    0%, 100% { opacity: 0.6; transform: scale(1); }
    50% { opacity: 0.3; transform: scale(1.1); }
}
</style>
<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: { type: Boolean },
    status: { type: String },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Log in" />

    <div class="min-h-screen flex bg-white dark:bg-gray-950 text-gray-900 dark:text-gray-100 font-sans selection:bg-blue-500 selection:text-white">
        
        <div class="w-full lg:w-1/2 flex flex-col justify-center p-8 sm:p-12 lg:p-24 relative z-10">
            <div class="lg:hidden absolute top-0 left-0 w-full h-full overflow-hidden -z-10 pointer-events-none">
                <div class="absolute top-[-20%] right-[-20%] w-[80%] h-[80%] bg-blue-600/10 rounded-full blur-3xl"></div>
            </div>

            <div class="mb-10 flex items-center gap-2">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-600 to-indigo-600 flex items-center justify-center text-white shadow-lg shadow-blue-500/20">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                </div>
                <span class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Kali-Chat</span>
            </div>

            <div class="mb-8">
                <h1 class="text-4xl font-extrabold tracking-tight mb-2 text-transparent bg-clip-text bg-gradient-to-r from-gray-900 to-gray-600 dark:from-white dark:to-gray-400">
                    Welcome Back
                </h1>
                <p class="text-gray-500 dark:text-gray-400 text-lg">
                    Enter your credentials to access your workspace.
                </p>
            </div>

            <div v-if="status" class="mb-4 font-medium text-sm text-green-600 p-3 bg-green-50 dark:bg-green-900/30 rounded-lg">
                {{ status }}
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <div class="space-y-2">
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email Address</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-blue-500 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5"><path d="M1.5 8.67v8.58a3 3 0 003 3h15a3 3 0 003-3V8.67l-8.928 5.493a3 3 0 01-3.144 0L1.5 8.67z" /><path d="M22.5 6.908V6.75a3 3 0 00-3-3h-15a3 3 0 00-3 3v.158l9.714 5.978a1.5 1.5 0 001.572 0L22.5 6.908z" /></svg>
                        </div>
                        <input id="email" type="email" v-model="form.email" required autofocus
                            class="block w-full pl-11 pr-4 py-3.5 bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 dark:text-white dark:placeholder-gray-500 transition-all shadow-sm group-hover:border-gray-300 dark:group-hover:border-gray-600"
                            placeholder="name@company.com">
                    </div>
                    <InputError :message="form.errors.email" />
                </div>

                <div class="space-y-2">
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-blue-500 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5"><path fill-rule="evenodd" d="M12 1.5a5.25 5.25 0 00-5.25 5.25v3a3 3 0 00-3 3v6.75a3 3 0 003 3h10.5a3 3 0 003-3v-6.75a3 3 0 00-3-3v-3c0-2.9-2.35-5.25-5.25-5.25zm3.75 8.25v-3a3.75 3.75 0 10-7.5 0v3h7.5z" clip-rule="evenodd" /></svg>
                        </div>
                        <input id="password" type="password" v-model="form.password" required
                            class="block w-full pl-11 pr-4 py-3.5 bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 dark:text-white dark:placeholder-gray-500 transition-all shadow-sm group-hover:border-gray-300 dark:group-hover:border-gray-600"
                            placeholder="••••••••">
                    </div>
                    <InputError :message="form.errors.password" />
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center cursor-pointer">
                        <Checkbox name="remember" v-model:checked="form.remember" class="w-5 h-5 text-blue-600 rounded border-gray-300 dark:border-gray-600 dark:bg-gray-800 focus:ring-blue-500/50" />
                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-400 select-none">Remember me</span>
                    </label>
                    <Link v-if="canResetPassword" :href="route('password.request')" class="text-sm font-semibold text-blue-600 dark:text-blue-400 hover:text-blue-500 hover:underline">
                        Forgot password?
                    </Link>
                </div>

                <button type="submit" 
                        class="w-full py-3.5 px-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-blue-500/30 transform transition-all duration-200 hover:scale-[1.02] active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
                        :disabled="form.processing">
                    <span v-if="form.processing">Signing in...</span>
                    <span v-else>Sign In to Account</span>
                </button>

                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-200 dark:border-gray-700"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white dark:bg-gray-950 text-gray-500">Or continue with</span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <button type="button" class="flex items-center justify-center gap-2 py-2.5 border border-gray-200 dark:border-gray-700 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-200 font-medium text-sm shadow-sm">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M12.545,10.239v3.821h5.445c-0.712,2.315-2.647,3.972-5.445,3.972c-3.332,0-6.033-2.701-6.033-6.032s2.701-6.032,6.033-6.032c1.498,0,2.866,0.549,3.921,1.453l2.814-2.814C17.503,2.988,15.139,2,12.545,2C7.021,2,2.543,6.477,2.543,12s4.478,10,10.002,10c8.396,0,10.249-7.85,9.426-11.748L12.545,10.239z"/></svg>
                        Google
                    </button>
                    <button type="button" class="flex items-center justify-center gap-2 py-2.5 border border-gray-200 dark:border-gray-700 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-200 font-medium text-sm shadow-sm">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd"></path></svg>
                        GitHub
                    </button>
                </div>

                <p class="text-center text-sm text-gray-500 dark:text-gray-400 mt-8">
                    Don't have an account? 
                    <Link :href="route('register')" class="font-bold text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition-colors">Sign up for free</Link>
                </p>
            </form>
        </div>

        <div class="hidden lg:flex w-1/2 bg-gray-900 relative overflow-hidden items-center justify-center p-12">
            <div class="absolute inset-0">
                <div class="absolute top-[-10%] right-[-10%] w-[600px] h-[600px] bg-purple-600/30 rounded-full blur-[100px] animate-pulse-slow"></div>
                <div class="absolute bottom-[-10%] left-[-10%] w-[600px] h-[600px] bg-blue-600/30 rounded-full blur-[100px] animate-pulse-slow delay-1000"></div>
                <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20"></div>
            </div>

            <div class="relative z-10 w-full max-w-md p-10 bg-white/10 backdrop-blur-xl border border-white/10 rounded-3xl shadow-2xl text-white">
                <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center mb-6">
                    <svg class="w-6 h-6 text-blue-400" fill="currentColor" viewBox="0 0 24 24"><path d="M4.5 4.5a3 3 0 00-3 3v9a3 3 0 003 3h8.25a3 3 0 003-3v-9a3 3 0 00-3-3H4.5zM19.94 18.75l-2.69-2.69V7.94l2.69-2.69c.944-.945 2.56-.276 2.56 1.06v11.38c0 1.336-1.616 2.005-2.56 1.06z" /></svg>
                </div>
                <h3 class="text-3xl font-bold mb-4 leading-tight">Connect with your team instantly.</h3>
                <p class="text-gray-300 text-lg leading-relaxed mb-8">
                    "Reverb's real-time architecture changed how we communicate. Zero latency, beautiful interface, and rock-solid reliability."
                </p>
                
                <div class="flex items-center gap-4">
                    <div class="flex -space-x-3">
                        <img class="w-10 h-10 rounded-full border-2 border-gray-800" src="https://i.pravatar.cc/100?img=1" alt="Avatar">
                        <img class="w-10 h-10 rounded-full border-2 border-gray-800" src="https://i.pravatar.cc/100?img=2" alt="Avatar">
                        <img class="w-10 h-10 rounded-full border-2 border-gray-800" src="https://i.pravatar.cc/100?img=3" alt="Avatar">
                    </div>
                    <div class="text-sm text-gray-400">
                        Joined by <span class="text-white font-bold">4,000+</span> developers
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
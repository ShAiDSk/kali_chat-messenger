<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head, usePage } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: { type: Boolean },
    status: { type: String },
});

const user = usePage().props.auth.user;
</script>

<template>
    <Head title="Profile Settings" />

    <AuthenticatedLayout>
        <div class="relative h-64 w-full bg-gradient-to-r from-blue-700 via-indigo-700 to-purple-700 overflow-hidden">
            <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-10"></div>
            <div class="absolute bottom-0 left-0 w-full h-32 bg-gradient-to-t from-gray-100 dark:from-gray-900 to-transparent"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-28 relative z-10 pb-12">
            
            <div class="flex flex-col md:flex-row items-end md:items-center gap-8 mb-12">
                <div class="relative group">
                    <div class="w-36 h-36 rounded-3xl bg-white dark:bg-gray-800 p-2 shadow-2xl ring-4 ring-white/50 dark:ring-gray-700/50">
                        <div class="w-full h-full rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-500 flex items-center justify-center text-6xl font-extrabold text-white shadow-inner">
                            {{ user.name.charAt(0).toUpperCase() }}
                        </div>
                    </div>
                    <div class="absolute bottom-3 right-3 w-7 h-7 bg-green-500 border-4 border-white dark:border-gray-800 rounded-full shadow-md"></div>
                </div>
                <div class="pb-4">
                    <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white tracking-tight drop-shadow-sm">{{ user.name }}</h1>
                    <p class="text-lg font-semibold text-gray-700 dark:text-gray-300 mt-1 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 opacity-70"><path d="M1.5 8.67v8.58a3 3 0 003 3h15a3 3 0 003-3V8.67l-8.928 5.493a3 3 0 01-3.144 0L1.5 8.67z" /><path d="M22.5 6.908V6.75a3 3 0 00-3-3h-15a3 3 0 00-3 3v.158l9.714 5.978a1.5 1.5 0 001.572 0L22.5 6.908z" /></svg>
                        {{ user.email }}
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <div class="lg:col-span-8 space-y-8">
                    
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="p-6 sm:p-8 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/30">
                            <div class="flex items-center gap-4">
                                <div class="p-3 bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300 rounded-xl">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-7 h-7"><path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM3.751 20.105a8.25 8.25 0 0116.498 0 .75.75 0 01-.437.695A18.683 18.683 0 0112 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 01-.437-.695z" clip-rule="evenodd" /></svg>
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">General Information</h2>
                                    <p class="text-base font-medium text-gray-600 dark:text-gray-300">Update your public profile details.</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-6 sm:p-8 bg-white dark:bg-gray-800">
                            <UpdateProfileInformationForm
                                :must-verify-email="mustVerifyEmail"
                                :status="status"
                                class="max-w-xl"
                            />
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="p-6 sm:p-8 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/30">
                            <div class="flex items-center gap-4">
                                <div class="p-3 bg-purple-100 dark:bg-purple-900/50 text-purple-700 dark:text-purple-300 rounded-xl">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-7 h-7"><path fill-rule="evenodd" d="M12 1.5a5.25 5.25 0 00-5.25 5.25v3a3 3 0 00-3 3v6.75a3 3 0 003 3h10.5a3 3 0 003-3v-6.75a3 3 0 00-3-3v-3c0-2.9-2.35-5.25-5.25-5.25zm3.75 8.25v-3a3.75 3.75 0 10-7.5 0v3h7.5z" clip-rule="evenodd" /></svg>
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Security</h2>
                                    <p class="text-base font-medium text-gray-600 dark:text-gray-300">Manage your password and authentication.</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-6 sm:p-8 bg-white dark:bg-gray-800">
                            <UpdatePasswordForm class="max-w-xl" />
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-4 space-y-8">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-red-200 dark:border-red-900 overflow-hidden">
                        <div class="p-6 border-b border-red-200 dark:border-red-900 bg-red-50 dark:bg-red-900/20">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-red-100 dark:bg-red-900/50 text-red-700 dark:text-red-400 rounded-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6"><path fill-rule="evenodd" d="M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003zM12 8.25a.75.75 0 01.75.75v3.75a.75.75 0 01-1.5 0V9a.75.75 0 01.75-.75zm0 8.25a.75.75 0 100-1.5.75.75 0 000 1.5z" clip-rule="evenodd" /></svg>
                                </div>
                                <h2 class="text-lg font-bold text-red-700 dark:text-red-400">Danger Zone</h2>
                            </div>
                        </div>
                        <div class="p-6 bg-white dark:bg-gray-800">
                            <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-6 leading-relaxed">
                                Permanently delete your account and all associated data. This action cannot be undone.
                            </p>
                            <DeleteUserForm />
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-indigo-700 to-blue-700 rounded-2xl shadow-xl p-6 text-white relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
                        <h3 class="font-bold text-xl mb-2">Need Help?</h3>
                        <p class="text-blue-100 text-sm font-medium mb-6 leading-relaxed">
                            Having trouble with your profile? Our support team is here to assist you 24/7.
                        </p>
                        <button class="w-full py-3 bg-white text-blue-700 rounded-xl text-sm font-bold shadow-lg hover:bg-gray-50 transition-transform active:scale-95">
                            Contact Support
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
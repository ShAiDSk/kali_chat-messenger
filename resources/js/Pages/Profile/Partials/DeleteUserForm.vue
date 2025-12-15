<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({
    password: '',
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;
    nextTick(() => passwordInput.value.focus());
};

const deleteUser = () => {
    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;
    form.reset();
};
</script>

<template>
    <section class="space-y-6">
        <header>
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Delete Account</h2>
            <p class="mt-1 text-sm font-medium text-gray-600 dark:text-gray-300">
                Once your account is deleted, all of its resources and data will be permanently deleted.
            </p>
        </header>

        <DangerButton @click="confirmUserDeletion" class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white font-bold rounded-lg shadow-md hover:shadow-lg transition-all duration-200 active:scale-95">
            Delete Account
        </DangerButton>

        <Modal :show="confirmingUserDeletion" @close="closeModal">
            <div class="p-8 bg-white dark:bg-gray-800">
                <div class="flex items-center gap-4 mb-6">
                    <div class="p-3 bg-red-100 dark:bg-red-900/30 text-red-600 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-8 h-8"><path fill-rule="evenodd" d="M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003zM12 8.25a.75.75 0 01.75.75v3.75a.75.75 0 01-1.5 0V9a.75.75 0 01.75-.75zm0 8.25a.75.75 0 100-1.5.75.75 0 000 1.5z" clip-rule="evenodd" /></svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                        Are you sure you want to delete your account?
                    </h2>
                </div>

                <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                    Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.
                </p>

                <div class="mt-6">
                    <InputLabel for="password" value="Password" class="sr-only" />
                    <TextInput
                        id="password"
                        ref="passwordInput"
                        v-model="form.password"
                        type="password"
                        class="mt-1 block w-3/4 rounded-xl border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:border-red-500 focus:ring-red-500 py-3"
                        placeholder="Enter your password to confirm"
                        @keyup.enter="deleteUser"
                    />
                    <InputError :message="form.errors.password" class="mt-2" />
                </div>

                <div class="mt-8 flex justify-end gap-3">
                    <SecondaryButton @click="closeModal" class="px-5 py-2.5 font-bold rounded-lg border-2"> 
                        Cancel 
                    </SecondaryButton>

                    <DangerButton
                        class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white font-bold rounded-lg shadow-lg active:scale-95"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="deleteUser"
                    >
                        Yes, Delete My Account
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </section>
</template>
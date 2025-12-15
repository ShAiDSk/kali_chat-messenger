<script setup>
import NavBar from '@/Components/NavBar.vue'; // <--- Import the new component
</script>

<template>
    <div>
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            
            <NavBar />

            <header class="bg-white dark:bg-gray-800 shadow" v-if="$slots.header">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <main>
                <slot />
            </main>
        </div>
    </div>
</template>
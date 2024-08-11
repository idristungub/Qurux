<script setup lang="ts">
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <div>
            <Link href="/">
                <p class="text-[50px] font-bold">Qurux</p>
            </Link>
        </div>

        <div
            class="lg:w-full w-[350px] rounded-[10px] sm:max-w-md m-6 px-6 py-4 bg-[#6B8F71] shadow-md overflow-hidden sm:rounded-lg "
        >
            <slot />
        </div>
    </div>
</template>

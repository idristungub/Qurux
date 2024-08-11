<script setup lang="ts">
import { Link} from '@inertiajs/vue3'
import {route} from "ziggy-js";
import { ref} from "vue";


const isMenuOpen = ref(false)
const toggleMenu = () => {
    isMenuOpen.value = !isMenuOpen.value
}

</script>

<template>
    <div class="navbar flex justify-between items-center bg-[#6B8F71] w-full lg:h-[99px] h-[60px] px-6">
        <p class="text-[20px] font-bold">Qurux</p>
        <div class="flex items-center gap-10 text-white">
            <div v-if="$page.props.auth.user" class="flex items-center gap-6">
                <Link class="hidden lg:block" :href="route('quran-quest.home')">Home</Link>
                <Link class="hidden lg:block" :href="route('achievements.create')">Achievement</Link>
                <Link class="hidden lg:block" :href="route('leaderboard.create')">Leaderboard</Link>
                <span class="hidden lg:block font-bold">{{ $page.props.auth.user.username }} (Points: {{$page.props.auth.user.points}})</span>
                <Link class="bg-[#AAD2BA] w-[100.83px] h-[42px] rounded-[10px] py-2.5 px-5 hidden lg:block" :href="route('logout')" method="post">Log Out</Link>
            </div>
            <template v-else>
                <Link class="hidden lg:block" :href="route('quran-quest.home')">QuranQuest</Link>
                <Link class="bg-[#AAD2BA] w-[82.83px] h-[42px] rounded-[10px] py-2 px-5 hidden lg:block" :href="route('login')">Login</Link>
                <Link class="bg-[#AAD2BA] w-[82.83px] h-[42px] rounded-[10px] py-2 px-3 hidden lg:block" :href="route('register')">Register</Link>
            </template>
            <div>
                <svg @click="toggleMenu" v-if="!isMenuOpen" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-[38px] h-[36px] text-[#1D1E18] lg:hidden">
                    <path fill-rule="evenodd" d="M3 6.75A.75.75 0 0 1 3.75 6h16.5a.75.75 0 0 1 0 1.5H3.75A.75.75 0 0 1 3 6.75ZM3 12a.75.75 0 0 1 .75-.75h16.5a.75.75 0 0 1 0 1.5H3.75A.75.75 0 0 1 3 12Zm0 5.25a.75.75 0 0 1 .75-.75h16.5a.75.75 0 0 1 0 1.5H3.75a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd" />
                </svg>
                <svg @click="toggleMenu" v-if="isMenuOpen" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-[38px] h-[36px] text-[#1D1E18] lg:hidden">
                    <path fill-rule="evenodd" d="M5.47 5.47a.75.75 0 0 1 1.06 0L12 10.94l5.47-5.47a.75.75 0 1 1 1.06 1.06L13.06 12l5.47 5.47a.75.75 0 1 1-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 0 1-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                </svg>
            </div>
        </div>
        <div v-if="isMenuOpen" id="nav" class="mobile-menu absolute flex flex-col items-center bg-[#6B8F71] w-full lg:top-[99px] top-[60px] right-0 z-1000">
            <div v-if="$page.props.auth.user">
                <hr class="h-px my-5 bg-white border-0">
                <Link class="text-white" :href="route('quran-quest.home')">QuranQuest</Link>
                <div class="font-bold text-white">
                    <hr class="h-px my-5 bg-white border-0 w-[321.14px]">
                    {{ $page.props.auth.user.username }} (Points: {{$page.props.auth.user.points}})
                </div>
                <hr class="h-px my-5 bg-white border-0">
                <Link class="text-white" :href="route('achievements.create')">Achievement</Link>
                <hr class="h-px my-5 bg-white border-0">
                <Link class="text-white" :href="route('leaderboard.create')">Leaderboard</Link>
                <hr class="h-px my-5 bg-white border-0">
                <Link class="bg-[#AAD2BA] w-[82.83px] h-[42px] rounded-[10px] py-2 px-5" :href="route('logout')" method="post">Log Out</Link>
                <hr class="h-px my-5 bg-white border-0">
            </div>
            <template v-else>
                <div class="flex gap-7">
                    <Link class="bg-[#AAD2BA] w-[82.83px] h-[42px] rounded-[10px] py-2 px-5" :href="route('login')">Login</Link>
                    <Link class="bg-[#AAD2BA] w-[82.83px] h-[42px] rounded-[10px] py-2 px-3" :href="route('register')">Register</Link>
                </div>
                <div>
                    <hr class="h-px my-5 bg-white border-0 w-[321.14px]">
                    <Link class="text-white" :href="route('quran-quest.home')">QuranQuest</Link>
                    <hr class="h-px my-5 bg-white border-0 w-[321.14px]">
                </div>
            </template>
        </div>
    </div>
</template>

<style scoped>
.navbar {
    position: fixed; /* Ensures the navbar stays at the top */
    top: 0;
    left: 0;
    right: 0;
    z-index: 1000; /* Ensure this is higher than other elements */
}

.mobile-menu {
    z-index: 1000; /* Same high z-index for mobile menu */
}
</style>



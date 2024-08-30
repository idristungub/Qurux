<script setup lang="ts">

import NavBar from "@/Layouts/NavBar.vue";
import axios from "axios";
import {onMounted} from "vue";
import {usePage} from "@inertiajs/vue3";

const props = defineProps({
    topThree: Array,
    leaderboardUsers: Array,
})

const page = usePage()

const authUser = page.props.auth.user.username
const handleLeaderboardAchievement = () => {
    const topThreeUsernames = props.topThree.map(user => user.username)
    if(topThreeUsernames.includes(authUser)) {
        axios.post('/top3Achievement')
            .then(res => console.log(res.data))
    }
}

onMounted(() => {
    handleLeaderboardAchievement()
})
</script>

<template>
    <NavBar/>
    <div class="mt-[130px]">


        <div class="flex justify-center items-center flex-col space-y-3">
            <p class="text-[25px] font-bold text-[#D2B721] pl-5">Leaderboard</p>

<!--            top 3 container-->
            <div class="flex justify-between items-center lg:gap-[100px]">

                <div class="flex justify-center items-center flex-col">
                    <p class="text-[25px] font-bold">2.</p>
                    <p class="text-[25px] font-bold">{{props.topThree[1].username}}</p>
                    <p class="text-gray-400 text-[20px] font-bold">{{props.topThree[1].points}}</p>
                </div>

                <div class="flex justify-center items-center flex-col mb-[160px]">
                    <img src="/assets/firstPlace.png">
                    <p class="text-[25px] font-bold">{{props.topThree[0].username}}</p>
                    <p class="text-gray-400 text-[20px] font-bold">{{props.topThree[0].points}}</p>
                </div>

                <div class="flex justify-center items-center flex-col">
                    <p class="text-[25px] font-bold">3.</p>
                    <p class="text-[25px] font-bold">{{props.topThree[2].username}}</p>
                    <p class="text-gray-400 text-[20px] font-bold">{{props.topThree[2].points}}</p>
                </div>

            </div>


<!--            top 50 -->

            <div v-for="(l,index) in leaderboardUsers" :key="index" class="flex flex-wrap justify-center items-center flex-col space-y-5">
                <div class="flex justify-center items-center">
                    <hr class="lg:h-px h-1 my-5 bg-[#D2B721] border-0 lg:w-[500px] w-[314px] font-bold">
                </div>

                <div class="flex justify-between items-center lg:gap-[200px] gap-[50px]">
                    <div class="flex flex-wrap gap-4">
                        <p class="text-[25px] font-bold">{{index + 4}}.</p>
                        <p class="text-[25px] font-bold break-words max-w-[200px]">   {{l.username}}</p>
                    </div>
                    <p class="text-[20px] text-gray-400 font-bold">{{l.points}}</p>
                </div>
            </div>

            <div class="flex justify-center items-center">
                <hr class="lg:h-px h-1 my-5 bg-[#D2B721] border-0 lg:w-[500px] w-[314px] font-bold">
            </div>


        </div>



    </div>


</template>

<style scoped>

</style>

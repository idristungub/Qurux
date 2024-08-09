<script setup lang="ts">
import {ref} from "vue";
import {route} from "ziggy-js";
import {Link} from "@inertiajs/vue3";
import { router } from '@inertiajs/vue3'
import axios from "axios";

let props = defineProps( {
    close: {
        type: Function
    },
    arabicName: String,
    englishName: String,
    chapterId: Number
})

const openInfo = ref(true)

const infoBox = () => {
    openInfo.value = !openInfo.value
}

const difficulty = ref('easy')

// get request to get the easy quiz/ advance quiz pages
const startQuiz = async () => {
    if(difficulty.value == 'easy') {
         await axios.post(`/recent/easy/${props.chapterId}/${props.arabicName}/${1}`)
             .catch(err => console.log(err))
         router.get(`/quiz/easy/${props.chapterId}/${1}`)
    } else if (difficulty.value == 'advance') {
        await axios.post(`/recent/advance/${props.chapterId}/${props.arabicName}`)
            .catch(err => console.log(err))
         router.get(`/quiz/advance/${props.chapterId}`)
    }
}

</script>


<template>
    <div class=" flex fixed top-0 left-0 right-0 bottom-0 z-99 backdrop-blur-sm items-center justify-center">
        <div  class="w-[514px] h-[380px] bg-[#6B8F71] pt-5 px-8 space-y-5 ">
            <div class="flex justify-between items-center ">
                <button @click="infoBox" class="underline text-white" >Info</button>


                <div class="flex flex-col justify-center items-center">
                    <span class="text-white font-bold">{{arabicName}}</span>
                    <span class="text-[#1D1E18] font-bold opacity-50">{{englishName}}</span>
                </div>


                <button @click="close()">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-[38px] h-[36px] text-[#1D1E18]">
                        <path fill-rule="evenodd" d="M5.47 5.47a.75.75 0 0 1 1.06 0L12 10.94l5.47-5.47a.75.75 0 1 1 1.06 1.06L13.06 12l5.47 5.47a.75.75 0 1 1-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 0 1-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                    </svg>
                </button>

            </div>

            <div class="flex justify-between">
                <div class="flex gap-3 items-center text-white font-bold">
                    <input class="bg-grey border-black border-2" type="radio" id="easy" value="easy" v-model="difficulty"/>
                    <label for="easy">Easy</label>
                </div>


                <div class="flex gap-3 items-center text-white font-bold">
                    <input class="bg-grey border-black border-2" type="radio" id="advance" value="advance" v-model="difficulty"/>
                    <label for="advance">Advance</label>
                </div>

            </div>

<!--test to know what difficulty it is-->
            <div class="font-bold">Chosen difficulty: {{difficulty}} </div>

<!--            info box-->
            <div v-if="!openInfo" class="bg-[#D9FFF5] w-[462px] h-[115px] text-[13px] space-y-3 px-1 rounded-[10px]">
                <p class="text-[#1D1E18] font-bold">Easy - identify and organize the words of a verse</p>
                <span class="font-bold text-[#D2B721]">Earn 1 point for each question</span>

                <p class="text-[#1D1E18] font-bold">Advance - identify and write the verses for the chapter and chapter name</p>
                <span class="font-bold text-[#D2B721]">Earn 5 point with the verse number but 2 points without the verse number</span>
            </div>
<!--start button-->
            <div class="flex justify-center items-center">
                <button @click="startQuiz" class="bg-[#D9FFF5]  w-[121px]  h-[46px]  rounded-[10px]  py-3  px-10 text-[#1D1E18] text-[16px] font-bold" >
                    Start
                </button>
            </div>





        </div>
    </div>
</template>



<script setup lang="ts">

import {onMounted, ref} from "vue";
import axios from "axios";
import {router} from "@inertiajs/vue3";

let props = defineProps( {
    close: {
        type: Function
    },
    juzId: Number
})

const openInfo = ref(true)

// fetching the first verse of juz and first chapter of juz
const verses = ref<string[]|undefined>([])
const firstVerseId = ref<number>(null)
const firstChapterId = ref<number>(null)
const lastVerseId = ref<number>(null)
const lastChapterId = ref<number>(null)

onMounted( () =>  {
    axios.get(`https://api.alquran.cloud/v1/juz/${props.juzId}/en.asad`, {
        headers: {
            'X-Requested-With': null
        }
    })
        .then(response => {
            const ayahData = response.data.data.ayahs
            verses.value = response.data.data.ayahs
            firstVerseId.value = ayahData[0].numberInSurah
            firstChapterId.value = ayahData[0].surah.number
            lastVerseId.value = ayahData[ayahData.length - 1].numberInSurah
            lastChapterId.value = ayahData[ayahData.length - 1].surah.number


        })
})



const infoBox = () => {
    openInfo.value = !openInfo.value
}

const difficulty = ref('easy')
// get request to get the easy quiz/ advance quiz pages
const startQuiz = async () => {
    if(difficulty.value == 'easy') {
        await axios.post(`/recent/easy/juz/${props.juzId}/${firstChapterId.value}/${firstVerseId.value}`)
            .catch(err => console.log(err))
        router.get(`/quiz/easy/juz/${props.juzId}/${firstChapterId.value}/${firstVerseId.value}`)
    } else if (difficulty.value == 'advance') {
        router.get(`/quiz/advance/juz/${props.juzId}`)
    }
}


</script>

<template>

    <div class=" flex fixed top-0 left-0 right-0 bottom-0 z-99 backdrop-blur-sm items-center justify-center">
        <div  class="w-[514px] h-[380px] bg-[#6B8F71] pt-5 px-8 space-y-5 ">
            <div class="flex justify-between items-center ">
                <button @click="infoBox" class="underline text-white" >Info</button>


                <div class="flex flex-col justify-center items-center">
                    <span class="text-white font-bold">Juz: {{juzId}}</span>

                </div>


                <button @click="close()">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-[38px] h-[36px] text-[#1D1E18]">
                        <path fill-rule="evenodd" d="M5.47 5.47a.75.75 0 0 1 1.06 0L12 10.94l5.47-5.47a.75.75 0 1 1 1.06 1.06L13.06 12l5.47 5.47a.75.75 0 1 1-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 0 1-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                    </svg>
                </button>

            </div>

            <div>
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


                <div v-if="difficulty === 'advance'">
                    <p class="text-red-600">Advance difficulty is currently under maintenance </p>
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

<style scoped>

</style>

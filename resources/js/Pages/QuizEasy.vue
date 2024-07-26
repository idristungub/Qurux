<script setup lang="ts">
import {onMounted, ref} from "vue";
import axios from "axios";
import {router} from "@inertiajs/vue3";
import NavBar from "@/Layouts/NavBar.vue";
import {shuffleArray} from "../../methods/shuffleArray";

let props = defineProps( {
    chapterId: String
})


const healthPoints = ref(3)
const points = ref(0)

// handle the continue button to increment verseId and show "Finish" when verseId reaches TotalAyah


// or fetch verses and audios from that chosen chapter
const verses = ref({})
const verseId = ref(1)

axios.get(`https://quranapi.pages.dev/api/${props.chapterId}/${verseId.value}.json`, {
    headers: {
        'X-Requested-With': null
    }
})
    .then(response => {
        console.log(response.data)
        verses.value = {
            arabicName: response.data.surahName,
            englishName: response.data.surahNameTranslation,
            chapterId: props.chapterId,
            verseId: verseId,
            actual_verse: shuffleArray(response.data.arabic1.split(" ")),
            misharyAudio: response.data.audio[1].url,
            totalVerse: response.data.totalAyah
        }
    })
    .then()
    .catch(err => console.log(err.message))


// check post request (health points changes, points changes,

//post bookmarks function

// audio stuff
const audioPlayed = ref(false)
const playAudio = () => {
    audioPlayed.value = !audioPlayed.value
}

// split words of verse randomly


</script>

<template>
<!--    navbar-->
    <NavBar/>
<!--   main top body -->
    <div class="flex justify-between m-4">

        <div class="font-bold text-[25px] " >
            <div class="flex gap-4 items-center">
                <img src="/assets/healthicon.png" class="w-[40px] h-[31px]">
                <p> {{healthPoints}}</p>
            </div>

            <p>Points: {{points}}</p>
            <p>Easy</p>
            <p>Verse: {{verseId}}</p>
        </div>

        <div class="flex flex-col justify-center items-center ">
            <span class="text-[#1D1E18] text-[25px] font-bold">{{verses.arabicName}}</span>
            <span class="text-[#1D1E18] text-[20px] font-bold opacity-50">{{verses.englishName}}</span>
        </div>

        <div class="flex flex-col">
            <button @click="postBookmarks">
                <img src="/assets/bookmarkicon.png">
            </button>


            <audio v-if="audioPlayed" autoplay :src="verses.misharyAudio"></audio>

            <button @click="playAudio" :class="{}">
                <img src="/assets/speaker.png">
            </button>

        </div>


    </div>


<!--    -->

<!--verse turn it into blocks-->
    <div class="bg-cyan-300 w-[1200px] overflow-y-scroll h-[200px]">

        <p class="text-[#1D1E18] text-[40px] font-bold ">{{verses.actual_verse}}</p>
    </div>

    <div v-for="(v, index) in verses.actual_verse" :key="index" class="bg-[#AAD2BA] w-[50px] flex justify-center items-center break-words ">
        {{v}}
    </div>



</template>

<style scoped>

</style>

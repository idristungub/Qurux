<script setup lang="ts">
import {onMounted, ref} from "vue";
import axios from "axios";
import {router} from "@inertiajs/vue3";
import NavBar from "@/Layouts/NavBar.vue";

let props = defineProps( {
    chapterId: String,
    verseId: String
})


const healthPoints = ref(3)
const points = ref(0)

// handle the continue button to increment verseId and show "Finish" when verseId reaches TotalAyah


// or fetch verses and audios from that chosen chapter
const verses = ref({})

axios.get(`https://quranapi.pages.dev/api/${props.chapterId}/${props.verseId}.json`, {
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
            verseId: props.verseId,
            actual_verse: response.data.arabic1,
            misharyAudio: response.data.audio[1].url,
            totalVerse: response.data.totalAyah

        }
    })
    .catch(err => console.log(err.message))


// check post request (health points changes, points changes,

</script>

<template>
<!--    navbar-->
    <NavBar/>
<!--   main body -->
    <div>
        <div >
            <div class="flex">
                <img src="/assets/healthicon.png" class="w-[40px] h-[31px]">
                <p> {{healthPoints}}</p>
            </div>

            <p>Points: {{points}}</p>
            <p>Easy</p>
            <p>Verse: {{props.verseId}}</p>
        </div>

        <div class="flex flex-col justify-center items-center">
            <span class="text-[#1D1E18] font-bold">{{verses.arabicName}}</span>
            <span class="text-[#1D1E18] font-bold opacity-50">{{verses.englishName}}</span>
        </div>


    </div>


    <div>
        <p>{{verses}}</p>

    </div>
</template>

<style scoped>

</style>

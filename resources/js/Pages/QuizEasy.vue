<script setup lang="ts">
import {computed, onMounted, ref} from "vue";
import axios from "axios";
import {router} from "@inertiajs/vue3";
import NavBar from "@/Layouts/NavBar.vue";
import {shuffleArray} from "../../methods/shuffleArray";
import DropBox from "@/Components/DropBox.vue";
import {removeDuplicates} from "../../methods/removeDuplicates";


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
            actual_verse: shuffleArray(response.data.arabic1.split(' ')),
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

// dragging the words of verse

const answers = ref<string[]>([])
const isDragging = ref(false)

const startDrag = (event: DragEvent, verseWord: string) => {
    isDragging.value = true
    if(event.dataTransfer) {
        event.dataTransfer.dropEffect = 'move'
        event.dataTransfer.effectAllowed = 'move'
        event.dataTransfer.setData('text/plain', verseWord);
        console.log(verseWord)
    }

}

const drop = (event: DragEvent, index: number) => {
    isDragging.value = false

    if(event.dataTransfer) {
        const droppedWord = event.dataTransfer.getData('text/plain');
        answers.value.push(droppedWord)
        verses.value.actual_verse = verses.value.actual_verse.filter(word => word !== droppedWord);
        console.log(answers.value)

    }
}



 const onDeleteWord = (words: string) => {
    answers.value.splice(answers.value.indexOf(words), 1)
     verses.value.actual_verse.push(words)

}






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


<!--  the answer user of the verse - dropzone  -->

   <DropBox :isDragging="isDragging" title="answer"  @drop="drop($event)">
       <template #body>
          <div v-for="(v, index) in answers"
               draggable="true"
             class="bg-[#AAD2BA] w-fit flex flex-row justify-center items-center break-words rounded-[10px] m-5 p-4 cursor-grab"
               @dragstart="startDrag($event, v)"
               @click="onDeleteWord(v)">
            <p class="text-[40px]">{{v}}</p>

          </div>
       </template>
   </DropBox>



<!--verse turn it into blocks - dragzone -->
    <DropBox :isDragging="isDragging" title="words" @drop="drop($event)">

        <template #body>
            <div v-for="(v, index) in verses.actual_verse"
                 :key="index" draggable="true"
                 @dragenter.prevent
                 @dragover.prevent
                 class="bg-[#AAD2BA] w-fit flex flex-row justify-center items-center break-words rounded-[10px] m-5 p-4 cursor-grab"
                 @dragstart="startDrag($event, v)">
                <p class="text-[40px]">{{v}}</p>
            </div>
        </template>


    </DropBox>





</template>

<style scoped>

</style>

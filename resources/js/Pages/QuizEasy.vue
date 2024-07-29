<script setup lang="ts">
import {onMounted, ref} from "vue";
import axios from "axios";
import NavBar from "@/Layouts/NavBar.vue";
import {shuffleArray} from "../../methods/shuffleArray";
import {Icon} from "@iconify/vue";


let props = defineProps( {
    chapterId: String
})


const healthPoints = ref(3)
const points = ref(0)




// or fetch verses and audios from that chosen chapter
const verses = ref({})
const verseId = ref(1)

axios.get(`https://quranapi.pages.dev/api/${props.chapterId}/${verseId.value}.json`, {
    headers: {
        'X-Requested-With': null
    }
})
    .then(response => {
        const verseArabic = response.data.arabic1.trim().split(' ')

        const verseArabicLong = verseArabic.reduce((acc: string[], value: string, index: number, array: string[]) => {
            if (index % 2 === 0) {
                if (index === array.length - 1) {
                    acc.push(value);
                } else {
                    acc.push(value + ' ' + array[index + 1]);
                }
            }
            return acc;
        }, []);

        console.log(verseArabicLong)
        console.log(verseArabic)

        verses.value = {
            arabicName: response.data.surahName,
            englishName: response.data.surahNameTranslation,
            chapterId: props.chapterId,
            verseId: verseId,
            actual_verse: shuffleArray(verseArabicLong),
            misharyAudio: response.data.audio[1].url,
            totalVerse: response.data.totalAyah
        }
    })

    .catch(err => console.log(err.message))




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

        console.log('answers are', answers.value)


    }
}

const reset = ref(false)
 const onDeleteWord = (words: string) => {
    answers.value.splice(answers.value.indexOf(words), 1)
     verses.value.actual_verse.push(words)
     console.log('list of words are', verses.value.actual_verse)
}
// on delete all verses from
const onDeleteAll = () => {
    reset.value = true
    if(reset.value == true) {
        for(let i = 0; i < answers.value.length; i++) {
            verses.value.actual_verse.push(answers.value[i])

        }
        answers.value.splice(0, answers.value.length)


    }
}

// checking the answer of words in the div to match the actual_verse
const showCorrect = ref(false)

const checkAnswer = (answer: string[]) => {
    const actual_answer = answer.join(' ')
    if(actual_answer === verses.value.actual_verse) {
        showCorrect.value = true
    }
}

// check post request (health points changes, points changes,

//post bookmarks function
const isBookmarked = ref(false)
const showAlert1 = ref(false)
const showAlert2 = ref(false)
const bookmarkAlert = ref('')
const deleteBookmarkAlert = ref('')


const toggleBookmarks = async () => {
    isBookmarked.value = !isBookmarked.value
    console.log(isBookmarked.value)
    if(isBookmarked.value == true) {
        await axios.post(`/bookmarks/easy/${props.chapterId}/${verses.value.arabicName}/${verseId.value}`)
            .then(response => {
                bookmarkAlert.value = response.data.message
                showAlert1.value = true

            })
            .catch(err => console.log(err.message))
        setTimeout(() => showAlert1.value = false, 2000)
    } else {
        await axios.delete(`/bookmarks/delete/${props.chapterId}/${verseId.value}`)
            .then(response => {
                deleteBookmarkAlert.value = response.data.message
                showAlert2.value = true

            })

            .catch(err => console.log(err.message))
        setTimeout(() => showAlert2.value = false, 2000)
    }
}

// handle the continue button to increment verseId and show "Finish" when verseId reaches TotalAyah

</script>

<template>
<!--    navbar-->
    <NavBar/>

<!--    alert messages-->
    <div v-if="showAlert1" class="p-4 mb-4 text-sm rounded-lg  bg-[#B9F5D8] text-[#1D1E18] absolute top-[8%] right-[47%] duration-500" role="alert">
        <span class="font-medium">Success alert! {{ bookmarkAlert }}</span>
    </div>

    <div v-if="showAlert2" class="p-4 mb-4 text-sm rounded-lg  bg-red-400 text-[#1D1E18] absolute top-[8%] right-[47%] duration-500" role="alert">
        <span class="font-medium">Delete alert! {{ deleteBookmarkAlert }}</span>
    </div>


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
            <button class="" @click="toggleBookmarks">
                <Icon v-if="!isBookmarked" icon="material-symbols:bookmark-outline" class="w-[85px] h-[100px]" />
                <Icon v-if="isBookmarked" icon="material-symbols:bookmark" class="w-[85px] h-[100px]" />
            </button>


            <audio v-if="audioPlayed" autoplay :src="verses.misharyAudio"></audio>

            <button @click="playAudio" >
                <img src="/assets/speaker.png">
            </button>

        </div>


    </div>


<!--    body to center the dropzone and dragzone-->
    <div class="flex justify-center flex-col items-center space-y-10">
        <!--  the answer user of the verse - dropzone  -->


        <div  title="answer"  @drop="drop($event, index)" class=" w-[1200px] h-[400px] overflow-y-scroll  bg-horizontal-stripes " @dragenter.prevent @dragover.prevent>

            <div dir="rtl" class="flex flex-wrap p-2 bg-amber-300  bg-horizontal-stripes ">


                <div  v-for="(v, index) in answers">
                    <div
                         class="bg-[#AAD2BA] w-fit flex flex-grow justify-center items-center break-words rounded-[10px] m-5 p-4 cursor-grab"
                         @dragstart="startDrag($event, v)"
                         @click="onDeleteWord(v)">
                        <p class="text-[40px] ">{{v}}</p>
                    </div>
                </div>



            </div>

        </div>



        <!--verse turn it into blocks - dragzone -->
        <div title="words" @drop="drop($event, index)" class=" w-[1200px] h-[120px] overflow-y-scroll" @dragenter.prevent @dragover.prevent>


            <div class=" flex flex-wrap justify-end p-2">
                <div v-for="(v, index) in verses.actual_verse"
                     :key="index"
                     draggable="true"
                     @dragenter.prevent
                     @dragover.prevent
                     class="bg-[#AAD2BA] w-fit  justify-center items-center break-words rounded-[10px] m-2 p-4 cursor-grab "
                     @dragstart="startDrag($event, v)">
                    <p class="text-[40px] ">{{v}}</p>
                </div>
            </div>


        </div>

    </div>

<!--    check and skip buttons-->
<!--    new div opens when either skip or check is pressed-->
    <div v-if="showCorrect">
        <img src="">
    </div>

    <div  class="">
        <img src="">
        <button>
            Continue
        </button>
    </div>


    <div class="flex justify-between m-4 ">
        <button class="bg-[#D9D9D9] w-[243px] h-[73px] rounded-[10px] py-2 px-5 border-4 border-[#AAD2BA] text-[25px] text-[#6B8F71] font-bold  ">
            skip
        </button>

        <button class=" w-[243px] h-[73px] rounded-[10px] py-2 px-5 border-4 border-[#AAD2BA] text-[25px] text-[#1D1E18] font-bold" v-if="answers.length" @click="onDeleteAll">
            Reset
        </button>

        <button  @click="checkAnswer(answers.value)" class="bg-[#6B8F71] w-[243px] h-[73px] rounded-[10px] py-2 px-5 border-4 border-[#AAD2BA] text-[25px] text-[#1D1E18] font-bold  ">
            check
        </button>
    </div>







</template>

<style scoped>

</style>

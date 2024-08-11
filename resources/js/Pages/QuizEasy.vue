<script setup lang="ts">
import {onMounted, ref, watch} from "vue";
import axios from "axios";
import NavBar from "@/Layouts/NavBar.vue";
import {shuffleArray} from "../../methods/shuffleArray";
import {Icon} from "@iconify/vue";
import {router, Link} from "@inertiajs/vue3";
import {findDuplicates, getDuplicates} from "../../methods/findDuplicates";


let props = defineProps( {
    chapterId: String,
    points_data: Number,
    health_data: Number,
    verseId:String

})


const healthPoints = ref<number|undefined>(props.health_data)
const points = ref<number|undefined>(props.points_data)





// or fetch verses and audios from that chosen chapter
const verses = ref({})
const verseId = ref<string|undefined>(props.verseId)

const fetchVerseData = async () => {
    axios.get(`https://quranapi.pages.dev/api/${props.chapterId}/${verseId.value}.json`, {
        headers: {
            'X-Requested-With': null
        }
    })
        .then(response => {
            const verseArabic = response.data.arabic1.trim().split(' ')


            const verseArabicLong = verseArabic.reduce((acc: { word: string, index: number }[], value: string, index: number, array: string[]) => {
                if (index % 2 === 0) {
                    if (index === array.length - 1) {
                        acc.push({ word: value, index });
                    } else {
                        acc.push({ word: value + ' ' + array[index + 1], index });
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
                answer: response.data.arabic1,
                misharyAudio: response.data.audio[1].url,
                totalVerse: response.data.totalAyah
            }





        })

        .catch(err => console.log(err.message))

}


onMounted(() => {
    fetchVerseData();
});
watch(verseId,fetchVerseData)
console.log(watch(verseId,fetchVerseData))




// audio stuff
const audioPlayed = ref(false)
const playAudio = () => {
    audioPlayed.value = !audioPlayed.value
}

// dragging the words of verse

const answers = ref<string[]>([])
const isDragging = ref(false)
const startDrag = (event: DragEvent, verseObj: { word: string, index: number }) => {
    isDragging.value = true;

    if (event.dataTransfer) {
        event.dataTransfer.dropEffect = 'move';
        event.dataTransfer.effectAllowed = 'move';
        event.dataTransfer.setData('text/plain', JSON.stringify(verseObj));
        console.log(verseObj);
    }
};

const clickOnVerse = (event: MouseEvent, verseObj: { word: string, index: number }) => {
    isDragging.value = false;

    answers.value.push(verseObj);
    verses.value.actual_verse = verses.value.actual_verse.filter(item => item.index !== verseObj.index);

    console.log('answers are', answers.value);
};


const drop = (event: DragEvent) => {
    isDragging.value = false;

    if (event.dataTransfer) {
        const droppedData = JSON.parse(event.dataTransfer.getData('text/plain'));
        answers.value.push(droppedData);
        verses.value.actual_verse = verses.value.actual_verse.filter(item => item.index !== droppedData.index);

        console.log('answers are', answers.value);
    }
};

const reset = ref(false)
const onDeleteWord = (verseObj: { word: string, index: number }) => {
    if (showCorrect.value || showIncorrect.value) {
        return;
    }
    answers.value = answers.value.filter(item => item.index !== verseObj.index);
    verses.value.actual_verse.push(verseObj);
    console.log('list of words are', verses.value.actual_verse);
};
// on delete all verses from
const onDeleteAll = () => {
    reset.value = true;
    if (reset.value) {
        answers.value.forEach(verseObj => {
            verses.value.actual_verse.push(verseObj);
        });
        answers.value = [];
    }
};



// checking the answer of words in the div to match the actual_verse
const showCorrect = ref(false)
const showIncorrect = ref(false)
const onFinish = ref(false)

const checkAnswer =  (answers: string[]) => {
    const answersWordsJoined = answers.map(verseWord => {
       return verseWord.word
    })

    const answersWordsJoined2 = ' ' + answersWordsJoined.join(' ')
    console.log("users guess: ", answersWordsJoined.join(' '))
    console.log("the answer joined is: ", verses.value.answer)
    if(answersWordsJoined.join(' ')  === verses.value.answer || answersWordsJoined2 === verses.value.answer) {
        showCorrect.value = true
        showIncorrect.value = false
        // audio correct noise will play when showCorrect is true
        points.value++
        axios.post('/checkPointsEasy')



    } else {

        showIncorrect.value = true
        showCorrect.value = false
        // audio incorrect noise will play when showIncorrect is true
        healthPoints.value = Math.max(0, healthPoints.value - 1)
        axios.post('/checkHealth')
            .then(response => console.log(response.data.health_status))
            .catch(err => console.log(err.data.error))
        if(healthPoints.value <= 0) {
            onFinish.value = true
            showIncorrect.value = false
            showCorrect.value = false
        }
        if(verseId.value === verses.value.totalVerse) {
            onFinish.value = true
            showIncorrect.value = false
            showCorrect.value = false


        }


    }




}



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
const handleNextVerse = () => {
    if(verseId.value < verses.value.totalVerse) {
        verseId.value++
        showCorrect.value = false
        showIncorrect.value = false
        answers.value = []
        router.get(`/quiz/easy/${props.chapterId}/${verseId.value}`)
    } else if(healthPoints.value == 0) {
        router.get('/quran-quest')
        onFinish.value =false
        showCorrect.value = false
        showIncorrect.value = false
    } else if(Number(verseId.value) === verses.value.totalVerse) {
        router.get('/quran-quest')
        onFinish.value =false
        showCorrect.value = false
        showIncorrect.value = false

    }




}


</script>

<template>
<!--    navbar-->
    <NavBar/>

<!--    alert messages-->
    <div v-if="showAlert1" class="p-4 mb-4 text-sm rounded-lg  bg-[#B9F5D8] text-[#1D1E18] absolute top-[18%]  lg:right-[60%] right-[20%] duration-500" role="alert">
        <span class="font-medium">Success alert! {{ bookmarkAlert }}</span>
    </div>

    <div v-if="showAlert2" class="p-4 mb-4 text-sm rounded-lg  bg-red-400 text-[#1D1E18] absolute top-[18%] lg:right-[60%] right-[20%] duration-500" role="alert">
        <span class="font-medium">Delete alert! {{ deleteBookmarkAlert }}</span>
    </div>


<!--   main top body -->

    <div class="flex justify-between lg:mt-[102px] mr-2 ml-2 mt-20">

        <div class="font-bold lg:text-[25px]  " >
            <div class="flex gap-4 items-center">
                <img src="/assets/healthicon.png" class="lg:w-[40px] lg:h-[31px] w-[20px] ">
                <div class="flex items-end gap-5">
                    <p> {{healthPoints}}</p>
                    <p v-if="showIncorrect" class="lg:text-[30px] text-red-700" >-1</p>
                </div>

            </div>

            <div class="flex items-end gap-5">
                <p>Points: {{points}}</p>
                <p v-if="showCorrect" class="lg:text-[30px] text-[16px] text-green-600">+1</p>
            </div>

            <p>Easy</p>
            <p>Verse: {{verseId}}</p>
        </div>

        <div class="flex flex-col justify-center items-center lg:pb-[10px] pb-[80px] ">
            <span class="text-[#1D1E18] lg:text-[25px] text-[20px] font-bold">{{verses.arabicName}}</span>
            <span class="lg:text-[20px] text-[16px] font-bold text-gray-500">{{verses.englishName}}</span>
            <span v-if="verseId==1" class="lg:text-[40px] text-[25px] font-bold">بِسْمِ اللهِ الرَّحْمٰنِ الرَّحِيْمِ</span>
        </div>


        <div class="flex flex-col">
            <button class="flex justify-end" @click="toggleBookmarks">
                <Icon v-if="!isBookmarked" icon="material-symbols:bookmark-outline" class="lg:w-[85px] w-[43px] lg:h-[100px] h-[44px]" />
                <Icon v-if="isBookmarked" icon="material-symbols:bookmark" class="lg:w-[85px] w-[43px] lg:h-[100px] h-[44px]" />
            </button>


            <audio v-if="audioPlayed" autoplay :src="verses.misharyAudio"></audio>

            <button class="flex justify-end" @click="playAudio" >
                <img class="lg:w-[90px] w-[50px]" src="/assets/speaker.png">
            </button>

        </div>


    </div>


<!--    body to center the dropzone and dragzone-->
    <div class="flex justify-center flex-col items-center space-y-10 ">
        <!--  the answer user of the verse - dropzone  -->


        <div  title="answer"  @drop="drop($event, index)" class=" lg:w-[1200px] w-[325px] h-[400px] overflow-y-scroll  bg-horizontal-stripes " @dragenter.prevent @dragover.prevent>

            <div dir="rtl" class="flex flex-wrap p-2 bg-amber-300  bg-horizontal-stripes ">


                <div  v-for="(v, index) in answers">
                    <div
                         class="bg-[#AAD2BA] w-fit flex flex-grow justify-center items-center break-words rounded-[10px] m-5 p-4 cursor-grab"
                         @dragstart="startDrag($event, v)"
                         @click="onDeleteWord(v)">
                        <p class="lg:text-[40px] text-[20px] ">{{v.word}}</p>
                    </div>
                </div>



            </div>

        </div>



        <!--verse turn it into blocks - dragzone -->
        <div title="words" @drop="drop($event, index)" class=" lg:w-[1200px] h-[120px] overflow-y-scroll" @dragenter.prevent @dragover.prevent>


            <div class=" flex flex-wrap justify-end p-2">
                <div v-for="(v, index) in verses.actual_verse"
                     :key="index"
                     draggable="true"
                     @dragenter.prevent
                     @dragover.prevent
                     class="bg-[#AAD2BA] w-fit flex  justify-center items-center break-words rounded-[10px] m-2 p-4 cursor-grab "
                     @dragstart="startDrag($event, v)" @click="clickOnVerse($event, v)">

                    <p class="lg:text-[40px] text-[20px]">{{v.word}}</p>
                </div>
            </div>


        </div>

    </div>

<!--    check and skip buttons-->
<!--    new div opens when either skip or check is pressed-->





    <div v-if="onFinish" class="flex  bg-green-500 w-full lg:h-[119px] h-[130px] justify-between items-center pr-[50px] duration-500">

        <audio autoplay src="/assets/incorrect%20answer%20(qurux).mp3"></audio>
        <div class="flex gap-3 lg:text-[40px] text-[16px] items-center pl-10 ">
            <p>Well then up to the next one ayyyy!</p>
        </div>
        <div>
            <Link :href="route('quiz.resetHealth')" class=" w-[243px] h-[73px] rounded-[10px] py-2 px-5 border-4 border-[#AAD2BA] lg:text-[25px] text-[#1D1E18] font-bold">
                Finish
            </Link>
        </div>


    </div>


    <div v-if="showIncorrect" class="flex  bg-red-400 w-full lg:h-[119px] h-[130px] justify-between items-center pr-[50px] duration-500">
        <audio autoplay src="/assets/incorrect%20answer%20(qurux).mp3"></audio>
        <div class="flex gap-3 lg:text-[40px] text-[16px] items-center pl-10 ">
            <Icon icon="raphael:cross" class="text-red-600" />
            <p>Oh No! You got it wrong (watch your health!)</p>
        </div>
        <div>
            <button @click.prevent="handleNextVerse" class=" lg:w-[243px] w-[120px] lg:h-[73px] rounded-[10px] lg:py-2 px-5 border-4 border-[#AAD2BA] lg:text-[25px] text-[#1D1E18] font-bold">
                Continue
            </button>


        </div>


    </div>

    <div v-if="showCorrect" class="flex  bg-[#D9FFF5] w-full lg:h-[119px] h-[130px] justify-between items-center pr-[50px] duration-500">

        <audio  autoplay src="/assets/correct%20answer%20sound%20(qurux).mp3"></audio>
            <div class="flex gap-3 lg:text-[40px] items-center pl-10 ">
                <Icon class="text-green-600" icon="subway:tick" />
                <p>Well Done! You got it correct</p>
            </div>
        <div>
            <button @click.prevent="handleNextVerse" class=" lg:w-[243px] w-[120px] lg:h-[73px]  rounded-[10px] lg:py-2 px-5 border-4 border-[#AAD2BA] lg:text-[25px] text-[#1D1E18] font-bold">
                Continue
            </button>

        </div>

    </div>


    <div v-if="!showCorrect && !showIncorrect && !onFinish" class="flex justify-between m-4 gap-5">
        <button @click="checkAnswer(answers)" class="bg-[#D9D9D9] lg:w-[243px] w-[99px] lg:h-[73px] h-[35px] rounded-[10px] lg:py-2 px-5 border-4 border-[#AAD2BA] lg:text-[25px] text-[#6B8F71] font-bold  ">
            skip
        </button>

        <button class=" lg:w-[243px] w-[99px]  lg:h-[73px]  h-[35px]  rounded-[10px] lg:py-2 px-5 border-4 border-[#AAD2BA] lg:text-[25px] text-[#1D1E18] font-bold" v-if="answers.length" @click="onDeleteAll">
            Reset
        </button>

        <button  @click="checkAnswer(answers)" class="bg-[#6B8F71] lg:w-[243px] w-[99px] lg:h-[73px] h-[35px] rounded-[10px] lg:py-2 px-5 border-4 border-[#AAD2BA] lg:text-[25px] text-[#1D1E18] font-bold  ">
            check
        </button>
    </div>







</template>

<style scoped>

</style>

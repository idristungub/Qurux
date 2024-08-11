<script setup lang="ts">
import {Icon} from "@iconify/vue";
import {onMounted, ref, watch} from "vue";
import axios from "axios";
import NavBar from "@/Layouts/NavBar.vue";
import {Link, router} from "@inertiajs/vue3";
import {shuffleArray} from "../../methods/shuffleArray";
import {arrayMatch} from "../../methods/arrayMatch";

const props = defineProps( {
    health_data: Number,
    points_data: Number,
    chapterId: Number
})

const healthPoints = ref<number|undefined>(props.health_data)
const points = ref<number|undefined>(props.points_data)
// or fetch verses and audios from that chosen chapter
const verses = ref<{}[]>([])
const chapterResponse = ref<any>(null)
const sortedVerseIds = ref<number[]>([])
const iterator = ref(null)




// Helper function to get a random integer between min and max (inclusive)
const getRandomNumber = (min: number, max: number) => {
    return Math.floor(Math.random() * (max - min + 1)) + min;
};

// Function to fetch verses from a random starting point
const fetchVerses = () => {
    if (!chapterResponse.value) return;

    const totalVerses = chapterResponse.value.verses_count;
    const startingPoint = getRandomNumber(1, totalVerses - 4); // Random start point ensuring at least 4 verses can be fetched

    let fetchedVerses = [];

    for (let i = startingPoint; i < startingPoint + 4 && i <= totalVerses; i++) {
        axios.get(`https://quranapi.pages.dev/api/${props.chapterId}/${i}.json`, {
            headers: {
                'X-Requested-With': null
            }
        })
            .then(response => {
                fetchedVerses.push({
                    chapterId: Number(props.chapterId),
                    verseId: i,
                    arabicName: response.data.surahName,
                    englishName: response.data.surahNameTranslation,
                    actual_verse: response.data.arabic1,
                    misharyAudio: response.data.audio[1].url,
                    totalVerses: response.data.totalAyah,
                });

                // Check if all 4 verses have been fetched
                if (fetchedVerses.length === 4) {

                    fetchedVerses.sort((a, b) => a.verseId - b.verseId);

                    fetchedVerses.forEach((verse, index) => {
                        verse.id = index;
                    });
                    const shuffledVerses = shuffleArray([...fetchedVerses]);

                    verses.value = shuffledVerses;

                    sortedVerseIds.value = fetchedVerses.map(verse => verse.verseId);

                    console.log("Shuffled Verse IDs for display: ", verses.value);
                    console.log("Sorted Verse IDs: ", sortedVerseIds.value);
                }
            })
            .catch(err => console.log(err));
    }
};


// Fetch the chapter data on mount
onMounted(() => {
    axios.get(`https://api.quran.com/api/v4/chapters`)
        .then(response => {
            const chapter = response.data.chapters.find(chapter => chapter.id === Number(props.chapterId));
            if (chapter) {
                chapterResponse.value = chapter;
                fetchVerses();
            }
        })
        .catch(err => console.log(err));
});

// audio play verse

// Ref to track the currently playing index
const currentPlayingIndex = ref(null);
// Array to store refs of audio elements
const audioRefs = ref([]);

// Method to play or pause the audio
const playAudio = (index:number) => {
    if (currentPlayingIndex.value !== null && currentPlayingIndex.value !== index) {
        // Pause the currently playing audio
        const currentAudioElement = audioRefs.value[currentPlayingIndex.value];
        if (currentAudioElement) {
            currentAudioElement.pause();
            currentAudioElement.currentTime = 0;
        }
    }

    // Play the selected audio
    const newAudioElement = audioRefs.value[index];
    if (newAudioElement) {
        if (currentPlayingIndex.value === index && !newAudioElement.paused) {
            // Pause the audio if it's already playing
            newAudioElement.pause();
        } else {
            // Play the new audio
            newAudioElement.play();
            currentPlayingIndex.value = index;
        }
    }
};

// Method to handle audio end event
const audioEnded = (index:number) => {
    if (currentPlayingIndex.value === index) {
        currentPlayingIndex.value = null;
    }
};

// On mounted, initialize the audioRefs array to match the length of verses
onMounted(() => {
    audioRefs.value = new Array(verses.value.length).fill(null);
});

// toggle checkboxes

const verseOrder = ref<Array<number|null>>(Array(4).fill(null));
const currentOrder = ref(0);


const toggleOrder = (index: number) => {
    if (verseOrder.value[index] === null) {
        if (currentOrder.value < 4) {
            verseOrder.value[index] = currentOrder.value++;




        }
        console.log("for each click the verseOrder: ", verseOrder.value)
    } else {
        const removedOrder = verseOrder.value[index];
        verseOrder.value[index] = null
        currentOrder.value--;


        for (let i = 0; i < verseOrder.value.length; i++) {
            if (verseOrder.value[i] && verseOrder.value[i]! > removedOrder) {
                verseOrder.value[i]!--;
            }
        }
    }
};



// check answers

const showCorrect = ref(false)
const showIncorrect = ref(false);
const onFinish = ref(false)



const checkAnswer = () => {
    const userOrder = verseOrder.value.map(order => {
        if (order !== null) {
            return verses.value[order].verseId;
        }
        return null;
    }).filter(Boolean); // Filter out null values


    // order that is correct
    const verseIdArray = verses.value.map(verse => {
        return verse.id
    })

    console.log("User's order (verseOrder): ", verseOrder.value);
    console.log("Correct order: ", verseIdArray);


    if(verseOrder.value[0] === verses.value[0].id
        && verseOrder.value[1] === verses.value[1].id
        && verseOrder.value[2] === verses.value[2].id
        && verseOrder.value[3] === verses.value[3].id) {
            showCorrect.value = true
            showIncorrect.value = false
            points.value += 5
            axios.post('/checkPointsAdvance')
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
        }


}


const handleNextQuestion = () => {
    window.location.reload()
}

</script>

<template>
    <NavBar/>

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
                <p v-if="showCorrect" class="lg:text-[30px] text-[16px] text-green-600">+5</p>
            </div>

            <p>Advance</p>

        </div>

        <div>


        </div>

        <div v-if="chapterResponse" class="flex flex-col justify-center items-center lg:pb-[10px] pb-[80px] ">
            <span class="text-[#1D1E18] lg:text-[25px] text-[20px] font-bold">{{chapterResponse.name_simple}}</span>
            <span class="lg:text-[20px] text-[16px] font-bold text-gray-500">{{chapterResponse.translated_name.name}}</span>
        </div>

        <div class="lg:w-[530px] w-20">
            <div>

            </div>

        </div>

    </div>

<!--    centre container-->

    <div class="flex flex-col justify-center items-center space-y-[30px] ">
        <p class="lg:text-[25px] text-[14px]  font-bold">What is the order of theses verses in the surah?</p>

        <div>
            <div  v-for="(v,index) in verses" :key="index" class="flex items-center p-2 gap-3 bg-[#AAD2BA] rounded-md m-4">
<!--                checkboxes-->
                <div
                    class="flex justify-center items-center w-[34px] h-[34px] bg-gray-300 rounded-[10px] cursor-pointer border-4 border-black"
                    @click="toggleOrder(index)"
                >
                    <span v-if="verseOrder[index] !== null" class="text-center text-black">{{ verseOrder[index]! + 1}}</span>
                </div>
<!--main verse-->
                    <p dir="rtl" class=" flex flex-wrap w-full md:w-[677px] lg:text-[25px] font-bold">{{v.actual_verse}}</p>

<!--                audio controls-->
                <div class="flex items-center">
                    <audio ref="audioRefs" :src="v.misharyAudio" @ended="audioEnded(index)"></audio>
                    <button class="flex justify-end" @click="playAudio(index)" >
                        <img class="lg:w-[75px] w-[50px]" src="/assets/speaker.png">
                    </button>
                </div>

            </div>
        </div>
    </div>

<!--buttons baby-->

    <div v-if="onFinish" class="flex  bg-green-500 w-full lg:h-[100px] h-[130px] justify-between items-center pr-[50px] duration-500 lg:mt-[137px]">

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



    <div v-if="showIncorrect" class="flex  bg-red-400 w-full lg:h-[100px] h-[130px] justify-between items-center pr-[50px] duration-500 lg:mt-[130px] ">
        <audio autoplay src="/assets/incorrect%20answer%20(qurux).mp3"></audio>
        <div class="flex gap-3 lg:text-[40px] text-[16px] items-center pl-10 ">
            <Icon icon="raphael:cross" class="text-red-600" />
            <p>Oh No! You got it wrong (watch your health!)</p>
        </div>
        <div>
            <button @click.prevent="handleNextQuestion" class=" lg:w-[243px] w-[120px] lg:h-[73px] rounded-[10px] lg:py-2 px-5 border-4 border-[#AAD2BA] lg:text-[25px] text-[#1D1E18] font-bold">
                Continue
            </button>


        </div>


    </div>

    <div v-if="showCorrect" class="flex  bg-[#D9FFF5] w-full lg:h-[119px] h-[130px] justify-between items-center pr-[50px] duration-500 lg:mt-[130px]">

        <audio  autoplay src="/assets/correct%20answer%20sound%20(qurux).mp3"></audio>
        <div class="flex gap-3 lg:text-[40px] items-center pl-10 ">
            <Icon class="text-green-600" icon="subway:tick" />
            <p>Well Done! You got it correct</p>
        </div>
        <div>
            <button @click.prevent="handleNextQuestion" class=" lg:w-[243px] w-[120px] lg:h-[73px]  rounded-[10px] lg:py-2 px-5 border-4 border-[#AAD2BA] lg:text-[25px] text-[#1D1E18] font-bold">
                Continue
            </button>

        </div>

    </div>


    <div  v-if="!showCorrect && !showIncorrect && !onFinish" class="flex justify-between gap-5 lg:mt-[130px] m-7 lg:mr-[50px] lg:ml-[50px]">
        <button @click.prevent="checkAnswer"  class="bg-[#D9D9D9] lg:w-[243px] w-[99px] lg:h-[73px] h-[35px] rounded-[10px] lg:py-2 px-5 border-4 border-[#AAD2BA] lg:text-[25px] text-[#6B8F71] font-bold  ">
            skip
        </button>


        <button @click.prevent="checkAnswer"   class="bg-[#6B8F71] lg:w-[243px] w-[99px] lg:h-[73px] h-[35px] rounded-[10px] lg:py-2 px-5 border-4 border-[#AAD2BA] lg:text-[25px] text-[#1D1E18] font-bold  ">
            check
        </button>
    </div>





</template>

<style scoped>

</style>

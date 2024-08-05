<script setup lang="ts">
import {Icon} from "@iconify/vue";
import {onMounted, ref, watch} from "vue";
import axios from "axios";
import NavBar from "@/Layouts/NavBar.vue";

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






// Helper function to get a random integer between min and max (inclusive)
const getRandomNumber = (min: number, max: number) => {
    return Math.floor(Math.random() * (max - min + 1)) + min;
};

// Function to fetch verses from a random starting point
const fetchVerses = () => {
    if (!chapterResponse.value) return;

    const totalVerses = chapterResponse.value.verses_count;
    const startingPoint = getRandomNumber(1, totalVerses - 4); // Random start point ensuring at least 4 verses can be fetched

    for (let i = startingPoint; i < startingPoint + 4 && i <= totalVerses; i++) {
        axios.get(`https://quranapi.pages.dev/api/${props.chapterId}/${i}.json`, {
            headers: {
                'X-Requested-With': null
            }
        })
            .then(response => {
                verses.value.push({
                    chapterId: Number(props.chapterId),
                    verseId: i,
                    arabicName: response.data.surahName,
                    englishName: response.data.surahNameTranslation,
                    actual_verse: response.data.arabic1,
                    misharyAudio: response.data.audio[1].url,
                    totalVerses: response.data.totalAyah,


                });
            })
            .catch(err => console.log(err));
    }
};
console.log(verses.value)

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

// checkboxes values

// audio play verse
const audioplayed1 = ref(false)
const audioplayed2 = ref(false)
const audioplayed3 = ref(false)
const audioplayed4 = ref(false)

// Ref to track the currently playing index
const currentPlayingIndex = ref(null);
// Array to store refs of audio elements
const audioRefs = ref([]);

// Method to play or pause the audio
const playAudio = (index) => {
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
const audioEnded = (index) => {
    if (currentPlayingIndex.value === index) {
        currentPlayingIndex.value = null;
    }
};

// On mounted, initialize the audioRefs array to match the length of verses
onMounted(() => {
    audioRefs.value = new Array(verses.value.length).fill(null);
});
</script>

<template>
    <NavBar/>

    <div class="flex justify-between m-4">

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

            <p>Advance</p>

        </div>

        <div>


        </div>

        <div v-if="chapterResponse" class="flex flex-col justify-center items-center lg:pb-[10px] pb-[80px] ">
            <span class="text-[#1D1E18] lg:text-[25px] text-[20px] font-bold">{{chapterResponse.name_simple}}</span>
            <span class="lg:text-[20px] text-[16px] font-bold text-gray-500">{{chapterResponse.translated_name.name}}</span>
        </div>

        <div class="lg:w-[530px] w-20">

        </div>

    </div>

<!--    centre container-->

    <div class="flex flex-col justify-center items-center bg-green-500 ">
        <p>What is the order of theses verses in the surah</p>
<!--        adding the start verse number-->
        <div class="">
            <label>Start Verse Number</label>
            <input type="text">
        </div>

        <div>
            <div v-for="(v,index) in verses" :key="index">

<!--                checkbox div-->
                <div class="w-[34px] h-[36px] bg-red-500"></div>
                <p>{{v.actual_verse}}</p>

                <audio ref="audioRefs" :src="v.misharyAudio" @ended="audioEnded(index)"></audio>
                <button class="flex justify-end" @click="playAudio(index)" >
                    <img class="lg:w-[90px] w-[50px]" src="/assets/speaker.png">
                </button>

            </div>
        </div>


    </div>






</template>

<style scoped>

</style>

<script setup lang="ts">
import {Link, router, usePage} from '@inertiajs/vue3';
import NavBar from "@/Layouts/NavBar.vue";
import {onMounted, reactive, ref} from "vue";
import {Icon} from '@iconify/vue'
import axios from "axios";

// getting props from the backend

defineProps({
    bookmarks: Array
})




// handling recent and bookmarks
const isRecent = ref(false);
const isBookmark = ref(false);

const handleRecent = () => {
    isRecent.value = !isRecent.value
    if(isRecent.value) {
        isBookmark.value = false;
    }
}
const handleBookmark = () => {
    isBookmark.value = !isBookmark.value;
    if(isBookmark.value) {
        isRecent.value = false;

    }
}

// fetching chapters of the quran
const chapterResponse = ref([])
onMounted( () => {
    axios.get('https://api.quran.com/api/v4/chapters')
        .then(response => {
            chapterResponse.value = response.data.chapters
        })
        .catch(err => console.log(err))
})

const isChapters = ref(true)
const handleChapters = () => {
    isChapters.value = !isChapters.value;
    if(isChapters.value) {
        isJuz.value = false;
    }
}

// delete quizzes from the bookmark


const deleteBookmark =  async (id) => {
    await axios.delete(`/delete-bookmark/${id}`)

}


// fetching juz of the quran



</script>

<template>
    <NavBar />

<!--    recent and bookmarked-->

    <div class="flex gap-8 text-[#D2B721] font-bold pt-[20px] pl-5">
        <button @click="handleRecent" :class="{'text-black': isRecent}">Recent Quizzes</button>
        <button @click="handleBookmark" :class="{'text-black': isBookmark}">Bookmarks</button>
    </div>

    <hr class="w-[1875px] h-1 bg-[#D2B721] mx-5">

    <div class="flex w-[222px]" v-if="isBookmark">
        <div class="font-bold flex items-center" v-for="b in bookmarks" :key="b.id">
            <button class="text-[16px] w-[230px] hover:text-[#AAD2BA] duration-500 ">{{b.chapter_title}} {{b.chapter_number}}:{{b.verse_number}} ({{b.difficulty}})</button>
            <button @click="deleteBookmark(b.id)" class="hover:text-[red]"><Icon class="text-[30px]" icon="basil:cross-solid" /> </button>
        </div>

        <div v-if="bookmarks?.length === 0">
            there is nothing
        </div>

    </div>

<!--    chapter and juz titles-->
    <div class="flex gap-8 text-[#D2B721] font-bold pt-[10px] pl-5">
        <button @click="handleChapters" :class="{'text-black': isChapters}">Chapters</button>
        <button >Juz</button>
    </div>

    <hr class="w-[1875px] h-1 bg-[#D2B721] mx-5">

<!--    selection container of quizzes-->

    <div>
        <div class="flex gap-2">
            <span class="font-bold text-[16px]">SORT BY: ASCENDING</span>
            <Icon icon="ri:arrow-up-s-line" class="text-[25px]" />

        </div>

<!--grid the results of chapters or juz-->
        <div v-if="isChapters" v-for="chapters in chapterResponse">
<!--container-->
            <button class="flex">
<!--                inner container-->
                <div>
                    <span>{{chapters.id}}</span>
                </div>

                <div>
                    <span>{{chapters.name_simple}}</span>
                    <span>{{chapters.translated_name.name}}</span>
                </div>

                <span>{{chapters.verses_count}} ayahs</span>

            </button>

        </div>
    </div>



</template>

<style scoped>

</style>

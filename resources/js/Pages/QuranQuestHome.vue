<script setup lang="ts">
import {Link, router, usePage} from '@inertiajs/vue3';
import NavBar from "@/Layouts/NavBar.vue";
import {onMounted, reactive, ref} from "vue";
import {Icon} from '@iconify/vue'
import axios from "axios";
import ChapterQuizDialogMenu from "@/Components/ChapterQuizDialogMenu.vue";


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

// fetching and deleting bookmarks for current user
const bookmarks = ref([])
onMounted( () => {
    axios.get('/bookmarks')
        .then(bookmarkData => {
            bookmarks.value = bookmarkData.data
        })
})

const deleteBookmarks = (index: number) => {
    const bookmarksToDelete = bookmarks.value[index]
    axios.delete(`/bookmarks/delete/${bookmarksToDelete.chapter_number}/${bookmarksToDelete.verse_number}`)
        .then(() => bookmarks.value.splice(index,1))
        .catch(err => console.log(err))
}


// toggle arrow icon

const toggleArrow = ref(false)
const orderName = ref('ASCENDING')
const isToggle = () => {
    toggleArrow.value = !toggleArrow.value
    if(toggleArrow.value) {
        orderName.value = 'DESCENDING'
        chapterResponse.value = chapterResponse.value.slice().reverse()
    } else {
        orderName.value = 'ASCENDING'
        chapterResponse.value = chapterResponse.value.slice().reverse()
    }
}

// opening dialog menu of chapter or juz

const isOpen = ref(false)
const selectedChapter = ref(null)
const openChapterDialogModal = (chapters) => {
    isOpen.value = !isOpen.value
    selectedChapter.value = chapters
    console.log('is open is: ', isOpen.value)
}





</script>

<template>
    <NavBar />

<!--    recent and bookmarked-->

    <div class="flex gap-8 text-[#D2B721] font-bold pt-[20px] pl-5">
        <button @click="handleRecent" :class="{'text-black': isRecent}">Recent Quizzes</button>
        <button @click="handleBookmark" :class="{'text-black': isBookmark}">Bookmarks</button>
    </div>

    <hr class="w-[1875px] h-1 bg-[#D2B721] mx-5">

    <div class="flex w-[400px]" v-if="isBookmark">
        <div class="font-bold flex items-center" v-for="(b, index) in bookmarks" :key="index">
            <button @click="router.get(`/quiz/easy/${b.chapter_number}/${b.verse_number}`)" class="text-[16px] w-[230px] hover:text-[#AAD2BA] duration-500 ">{{b.chapter_title}} {{b.chapter_number}}:{{b.verse_number}} ({{b.difficulty}})</button>
            <button @click="deleteBookmarks(index)" class="hover:text-[red]"><Icon class="text-[30px]" icon="basil:cross-solid" /> </button>
        </div>

        <div class="mx-5" v-if="bookmarks?.length === 0">
            You do not have any bookmarks yet
        </div>

    </div>

<!--    chapter and juz titles-->
    <div class="flex gap-8 text-[#D2B721] font-bold pt-[10px] pl-5">
        <button @click="handleChapters" :class="{'text-black': isChapters}">Chapters</button>
        <button >Juz</button>
    </div>

    <hr class="w-[1875px] h-1 bg-[#D2B721] mx-5">

<!--    selection container of quizzes-->

    <div class="pl-5">
        <div class="flex gap-2 float-right">
            <span class="font-bold text-[16px]">SORT BY: {{orderName}} </span>
            <button :class="{'rotate-180': toggleArrow}" @click="isToggle">
                <Icon icon="ri:arrow-up-s-line" class="text-[25px]" />
            </button>

        </div>

<!--grid the results of chapters or juz-->
        <div v-if="isChapters" v-for="chapters in chapterResponse">
<!--container-->
            <button @click="openChapterDialogModal(chapters)" class="flex">
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

    <!--    dialog for chapters-->
    <ChapterQuizDialogMenu v-if="isOpen"
                           :close=" () => openChapterDialogModal(selectedChapter)"
                           :arabic-name="selectedChapter.name_simple"
                           :english-name="selectedChapter.translated_name.name"
                           :chapter-id="Number(selectedChapter.id)"


                          />


    <!--    dialog for Juz-->


</template>

<style scoped>

</style>

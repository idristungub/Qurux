<script setup lang="ts">
import {Link, router, usePage} from '@inertiajs/vue3';
import NavBar from "@/Layouts/NavBar.vue";
import {onMounted, reactive, ref} from "vue";
import {Icon} from '@iconify/vue'
import axios from "axios";
import ChapterQuizDialogMenu from "@/Components/ChapterQuizDialogMenu.vue";
import {getDuplicates} from "../../methods/findDuplicates";
import JuzQuizDialogMenu from "@/Components/JuzQuizDialogMenu.vue";


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

const isJuz = ref(false)
const handleJuz = () => {
    isJuz.value = !isJuz.value
    if(isJuz.value) {
        isChapters.value = false
    }
}


// fetching juz

const juzResponse = ref([])
const newJuzResponse = ref([])

axios.get('https://api.quran.com/api/v4/juzs')
    .then(response => {

        const juzs = response.data.juzs
        juzResponse.value = juzs.map(juz => ({
            juz_number: juz.juz_number,
            chapters: juz.verse_mapping,
            totalAyahs: juz.verses_count
        }))


        const uniqueJuzNumbers = juzResponse.value.filter((item, index, self) =>
            index === self.findIndex((t) => t.juz_number === item.juz_number)
        );



        newJuzResponse.value = uniqueJuzNumbers;
        console.log("new array jor juz: ",  uniqueJuzNumbers)

    })

const matchChapterIdOfJuz = (chapterId) => {
    const chapter = chapterResponse.value.find(chap => chap.id === parseInt(chapterId));
    return chapter
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

// fetching and deleting recent for current user
const recents = ref([])
onMounted( () => {
    axios.get('/recent')
        .then(recentData => {
            recents.value = recentData.data
        })
})

const deleteRecents = (index:number) => {
    const recentsToDelete = recents.value[index]
    axios.delete(`/recent/delete/${recentsToDelete.chapter_number}/${recentsToDelete.verse_number}`)
        .then(() => recents.value.splice(index,1))
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
        newJuzResponse.value = newJuzResponse.value.slice().reverse()
    } else {
        orderName.value = 'ASCENDING'
        chapterResponse.value = chapterResponse.value.slice().reverse()
        newJuzResponse.value = newJuzResponse.value.slice().reverse()
    }
}

// opening dialog menu of juz

const isOpen = ref(false)
const selectedChapter = ref(null)
const openChapterDialogModal = (chapters: any) => {
    isOpen.value = !isOpen.value
    selectedChapter.value = chapters
    console.log('is open chapter dialog is: ', isOpen.value)
}

// opening dialog of juz
const isOpenJuz = ref(false)
const selectedJuz = ref(null)
const openJuzDialogModal = (juz: any) => {
    isOpenJuz.value = !isOpenJuz.value
    selectedJuz.value = juz
    console.log(Number(selectedJuz.value.juz_number))

}

// route to juz or chapters
const routeToRecentJuzOrChapter = (recent:string[]) => {
    if(recent.juz_number != null) {
        router.get(`quiz/easy/juz/${recent.juz_number}/${recent.chapter_number}/${recent.verse_number}`)
    } else {
        router.get(`/quiz/easy/${recent.chapter_number}/${recent.verse_number}`)
    }
}

const routeToBookmarkJuzOrChapter = (bookmark:string[]) => {
    if(bookmark.juz_number != null) {
        router.get(`quiz/easy/juz/${bookmark.juz_number}/${bookmark.chapter_number}/${bookmark.verse_number}`)
    } else {
        router.get(`/quiz/easy/${bookmark.chapter_number}/${bookmark.verse_number}`)
    }
}


</script>

<template>
    <NavBar />

<!--    recent and bookmarked-->

        <div class="flex gap-8 text-[#D2B721] font-bold pt-[20px] pl-5 lg:mt-[100px] mt-[50px]">
            <button @click="handleRecent" :class="{'text-black': isRecent}">Recent Quizzes</button>
            <button @click="handleBookmark" :class="{'text-black': isBookmark}">Bookmarks</button>
        </div>

    <hr class="w-[1875px] h-1 bg-[#D2B721] mx-5">


    <div class="flex lg:w-full w-[370px] overflow-x-scroll scrollbar-webkit " v-if="isBookmark">
        <div class="font-bold flex  items-center " v-for="(b, index) in bookmarks" :key="index">
            <button @click="routeToBookmarkJuzOrChapter(b)" class="text-[16px] w-[230px] hover:text-[#AAD2BA] duration-500 ">{{b.chapter_title}} {{b.juz_number}}:{{b.chapter_number}}:{{b.verse_number}} ({{b.difficulty}})</button>
            <button @click="deleteBookmarks(index)" class="hover:text-[red]"><Icon class="text-[30px]" icon="basil:cross-solid" /> </button>
        </div>

        <div class="mx-5" v-if="bookmarks?.length === 0">
            You do not have any bookmarks yet
        </div>
    </div>

    <div class="flex lg:w-full w-[370px] overflow-x-scroll scrollbar-webkit" v-if="isRecent">
        <div class="font-bold flex items-center" v-for="(r, index) in recents" :key="index">
            <button @click="routeToRecentJuzOrChapter(r)" class="text-[16px] w-[230px] hover:text-[#AAD2BA] duration-500 ">{{r.chapter_title}} {{r.juz_number}}:{{r.chapter_number}}:{{r.verse_number}} ({{r.difficulty}})</button>
            <button @click="deleteRecents(index)" class="hover:text-[red]"><Icon class="text-[30px]" icon="basil:cross-solid" /> </button>
        </div>

        <div class="mx-5" v-if="recents?.length === 0">
            You do not have any recents
        </div>

    </div>




<!--    chapter and juz titles-->
    <div class="flex gap-8 text-[#D2B721] font-bold pt-[10px] pl-5">
        <button @click="handleChapters" :class="{'text-black': isChapters}">Chapters</button>
        <button @click="handleJuz"  :class="{'text-black': isJuz}">Juz</button>
    </div>

    <hr class="w-[1875px] h-1 bg-[#D2B721] mx-5">

<!--    selection container of quizzes-->

    <div class="lg:pl-5 pl-3 ">
        <div class="flex gap-2 float-right">
            <span class="font-bold text-[16px]">SORT BY: {{orderName}} </span>
            <button class="relative z-10" :class="{'rotate-180': toggleArrow}" @click="isToggle">
                <Icon icon="ri:arrow-up-s-line" class="text-[25px]" />
            </button>

        </div>

<!--grid the results of chapters-->
        <div class="grid lg:grid-cols-4 gap-4 m-5 grid-cols-1 lg:pt-1 pt-10">
            <div  v-if="isChapters" v-for="chapters in chapterResponse">
                <!--container-->
                <button  @click="openChapterDialogModal(chapters)" class="flex bg-[#D9FFF5] lg:w-[385px] w-[319px] h-[82px] justify-between p-4 rounded-[10px] ">
                    <!--                inner container-->
                    <div class="w-[50px] h-[50px] z-60 transform rotate-45 relative z-10 rounded-[10px] bg-[#1D1E18] flex justify-center items-center">
                        <div class="transform  -rotate-45">
                            <span class="text-white font-bold ">{{chapters.id}}</span>
                        </div>

                    </div>


                    <div class="flex flex-col items-start">
                        <span class="text-[20px] font-bold">{{chapters.name_simple}}</span>
                        <span class="text-[16px] font-bold text-gray-500">{{chapters.translated_name.name}}</span>
                    </div>

                    <div class="pt-8">
                        <span class=" text-[16px] font-bold">{{chapters.verses_count}} ayahs</span>
                    </div>


                </button>



            </div>
        </div>

        <!--    grid the results of juz-->

        <div class="grid lg:grid-cols-4 gap-4 m-5 grid-cols-1 lg:pt-1 ">
            <div v-if="isJuz" v-for="(j, index) in newJuzResponse" :key="index">

                <button @click="openJuzDialogModal(j)" class="bg-[#6B8F71] lg:w-[385px] w-[319px] h-min rounded-[10px] space-y-2 p-4 " >
                    <div class="flex justify-between">
                        <span class="text-[16px] font-bold">Juz: {{j.juz_number}}</span>
                        <span  class="text-[16px] font-bold">{{j.totalAyahs}} ayahs</span>
                    </div>

                    <div class="bg-[#D9FFF5] h-[100px] flex items-center p-4 rounded-[10px] " v-for="c in Object.keys(j.chapters)">


                        <div class="flex flex-row gap-9"  v-if="matchChapterIdOfJuz(c)">
                            <div class="w-[50px] h-[50px] transform rotate-45 rounded-[10px] bg-[#1D1E18] flex justify-center items-center">
                                <div class="transform -rotate-45">
                                   <span class="text-white font-bold ">{{c}}</span>
                                </div>
                            </div>


                            <div class="flex flex-col items-start">
                                <span class="text-[20px] font-bold">{{matchChapterIdOfJuz(c).name_simple}}</span>
                                <span class="text-[16px] font-bold text-gray-500">{{matchChapterIdOfJuz(c).translated_name.name}}</span>
                            </div>


                        </div>




                    </div>


                </button>

            </div>

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

    <JuzQuizDialogMenu v-if="isOpenJuz"
                       :close="() => openJuzDialogModal(selectedJuz)"
                       :juz-id="selectedJuz.juz_number"



    />


</template>

<style scoped>

</style>

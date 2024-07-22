<script setup lang="ts">
import {Link} from '@inertiajs/inertia-vue3'
import {route} from "ziggy-js";
import NavBar from "@/Layouts/NavBar.vue";



</script>

<template>
    <NavBar />
    <img src="/assets/leaf.png" alt="leaf">

    <div class="flex justify-center my-[100px] mx-9 flex-col">
        <p class="lg:text-[50px] text-[16px] lg:w-[677px] w-[251px] font-bold">Converse and Convene to gain knowledge of this <span class="text-[#D2B721] font-bold underline">faith</span> </p>

        <p class="lg:my-6 my-4 lg:w-[650px] lg:text-[16px] w-[150px] text-[11px]">Learn and improve your understanding of islam</p>
        <Link class="bg-[#AAD2BA] lg:w-[200px] w-[120px] lg:h-[50px] h-[32px]  rounded-[10px] lg:py-3 py-1.5 lg:px-10 px-5 text-white font-bold lg:text-[20px] text-[14px]" href="/register" method="post">
            Get Started
        </Link>
    </div>

    <img src="" alt="leaf">

<!--    gap line-->
    <div class="flex justify-center items-center">
        <hr class="  lg:h-px h-1 my-5 bg-[#D2B721] border-0 lg:w-[1800px] w-[314px] ">
    </div>

<!--    info box-->

    <div class="flex justify-center items-center px-10  lg:my-[100px] my-[50px] mx-9">
        <div class=" flex lg:w-[500px] w-[314px] lg:h-[761px] h-[574px] bg-[#6B8F71] lg:px-20 px-5 py-12 items-start rounded-[20px] ">



<!--            quran quest info-->

            <div class="space-y-10">

                <div class="flex justify-center items-center">
                    <p class="bg-[#AAD2BA] w-[200px] h-[50px] rounded-[10px] py-2.5 px-9 text-white font-bold text-[20px] "> Quran Quest</p>
                </div>

                <div class="lg:w-[340px] w-[255px] lg:text-[30px] text-[20px] font-bold">
                    <li class="text-white my-4">
                        Take Quizzes of your favourite or practising surahs/juz to gain points and achievements
                    </li>

                    <li class="text-white my-4">
                        What is the missing word (Surah Fatiha)
                    </li>
                    <div class=" flex justify-center">
                        <p class="text-[30px] text-white"> ١</p>
                        <hr class="h-px my-8 bg-[#D2B721] border-0 w-[100px]">
                        <p class=" text-white">ٱلْحَمْدُ لِلَّهِ رَبِّ</p>

                    </div>


                    <Link class="bg-[#AAD2BA] lg:w-[200px] w-[120px] lg:h-[50px] h-[32px]  rounded-[10px] lg:py-3 py-1.5 lg:px-10 px-5 text-white font-bold lg:text-[20px] text-[14px] float-right" href="/quran-quest" method="get">
                        Start Quest
                    </Link>

                </div>

            </div>


        </div>




    </div>

    <!--    gap line-->
    <div class="flex justify-center items-center">
        <hr class="  lg:h-px h-1  my-5 bg-[#D2B721] border-0 lg:w-[1800px] w-[314px] ">
    </div>

    <!--            testimonies-->

    <div class="flex justify-center items-center lg:my-[100px] my-[10px] mx-9 flex-col">

        <div class="bg-[#6B8F71] lg:w-[1200px] w-[355px] my-10 py-4 px-4 rounded-[20px] ">
            <p class="font-bold text-white">“Before I started learning the Quran, I felt lost and disconnected from my faith. This website has been a beacon of light, guiding me through the teachings of Islam. I feel more spiritually connected and at peace”
            </p>
            <p class="text-[#D2B721] font-bold">Hafsa Abdi, <br/>
                Student</p>
        </div>

        <div class="bg-[#6B8F71] lg:w-[1200px] w-[355px] my-10 py-4 px-4 rounded-[20px] ">
            <p class="font-bold text-white">“Im finding a reliable source to learn the Faith was crucial in Quran Quest. This website provided me with clear, step-by-step guidance that made my learning journey both enlightening and enjoyable”
            </p>
            <p class="text-[#D2B721] font-bold">Mikhael Edwin, <br/>
                Employee
            </p>
        </div>

        <div class="bg-[#6B8F71] lg:w-[1200px] w-[355px] my-10 py-4 px-4 rounded-[20px] ">
            <p class="font-bold text-white">“The variety of resources available here is astounding. From video lectures to interactive quizzes and downloadable materials from Knowledge Post, there's something for every type of learner”</p>
            <p class="text-[#D2B721] font-bold">Daud Jarif, <br/>
                Employee</p>
        </div>



    </div>








</template>

<style scoped>

</style>

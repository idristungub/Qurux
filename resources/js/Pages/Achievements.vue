<script setup lang="ts">
import NavBar from "@/Layouts/NavBar.vue";
import {onMounted, ref} from "vue";
import {Icon} from "@iconify/vue";
import axios from "axios";

const props = defineProps( {
    achievementOneTitle: String,
    achievementTwoTitle: String,
    achievedOne: Array,
    achievedTwo: Array

})


const achievedOneTick = ref(false)
const achievedTwoTick = ref(false)


// check if response is true then change achieved to true
// maybe so switch case for this later
const handleAchievments = () => {
    if(Array.isArray(props.achievedOne) && props.achievedOne.length > 0) {
        achievedOneTick.value = true
        console.log(props.achievedOne)
    }
    if(Array.isArray(props.achievedTwo) && props.achievedTwo.length > 0) {
        achievedTwoTick.value = true
        console.log(props.achievedTwo)
    }

}


onMounted(() => {
    handleAchievments()
})





</script>



<template>

    <NavBar/>

    <div class="lg:mt-[130px] mt-[100px]  flex  flex-col lg:m-[550px] m-4">

        <p class="text-[#D2B721] text-[25px] font-bold mb-5">All achievements</p>
        <div class="space-y-2">

<!--            first achievement-->

            <div class="flex justify-between gap-[100px] bg-[#6B8F71] lg:w-[681px] h-[90px] rounded-[10px] p-8 border-2 border-[#D2B721] ">
                <div>
                    <p class="lg:text-[20px] font-bold text-white">{{achievementOneTitle[0]}}</p>
                </div>

                <div
                    class="flex justify-center items-center lg:w-[34px] w-[40px] h-[34px] bg-gray-300 rounded-[10px] border-2 border-[#D2B721]"
                >
<!--                    green tick if completed-->
                    <Icon v-if="achievedOneTick" class="text-green-600"  icon="subway:tick" />


                </div>

            </div>

<!--            second achievement-->

            <div class="flex justify-between gap-[100px] bg-[#6B8F71] lg:w-[681px] h-[90px] rounded-[10px] p-8 border-2 border-[#D2B721]">
                <div>
                    <p class="lg:text-[20px] font-bold text-white">{{achievementTwoTitle[0]}}</p>
                </div>

                <div
                    class="flex justify-center items-center lg:w-[34px] w-[40px] h-[34px] bg-gray-300 rounded-[10px] border-2 border-[#D2B721]"
                >
                    <!--                    green tick if completed-->
                    <Icon v-if="achievedTwoTick" class="text-green-600"  icon="subway:tick" />


                </div>

            </div>


        </div>

    </div>



</template>

<style scoped>

</style>

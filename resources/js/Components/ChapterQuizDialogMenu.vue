<script >
import { ref } from 'vue'
import {Dialog} from '@headlessui/vue'

export default {
    props: {
        close: Boolean
    },

    setup() {
        const difficulty = defineModel()
        const openInfo = ref(false)
        const infoBox = () => {
            openInfo.value = !openInfo.value
        }

    }
}




</script>


<template>
    <div class=" flex fixed top-0 left-0 right-0 bottom-0 z-99 backdrop-blur-sm items-center justify-center">
        <div  class="w-[514px] h-[380px] bg-[#6B8F71] pt-5 px-4">
            <div class="flex justify-between items-center ">
                <button class="underline text-white" >Info</button>
                <slot/>
                <button @click="close()">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-[38px] h-[36px] text-[#1D1E18]">
                        <path fill-rule="evenodd" d="M5.47 5.47a.75.75 0 0 1 1.06 0L12 10.94l5.47-5.47a.75.75 0 1 1 1.06 1.06L13.06 12l5.47 5.47a.75.75 0 1 1-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 0 1-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                    </svg>
                </button>

            </div>



        </div>
    </div>
</template>



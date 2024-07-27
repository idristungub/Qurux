<script setup lang="ts">
const props = defineProps( {
    title: String,
    isDragging: Boolean
})

</script>

<template>
    <div class="bg-red-500 w-[1200px] h-[200px] overflow-y-scroll" @dragenter.prevent @dragover.prevent>
        <div>
            {{props.title}}
        </div>

        <div>
            <slot name="body"></slot>
        </div>

    </div>
</template>

<style scoped>

</style>

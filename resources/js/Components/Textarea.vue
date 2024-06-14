<script setup>
import {defineModel, defineExpose, onMounted, ref} from 'vue';

const model = defineModel({
    type: String,
    required: true,
});

const textarea = ref(null);

onMounted(() => {
    if (textarea.value && textarea.value.hasAttribute('autofocus')) {
        textarea.value.focus();
    }
});

defineExpose({focus: () => textarea.value && textarea.value.focus()});
</script>
<template>
    <div>
    <textarea
        class="border-gray-300 border-2 focus:border-blue-200 focus:ring-blue-200 rounded-md shadow-sm h-full transition duration-300"
        v-model="model"
        ref="textarea"
    ></textarea>
    </div>
</template>

<style scoped>
textarea {
    width: 100%;
    height: 100%;
    resize: none;
}
</style>

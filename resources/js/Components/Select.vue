<script setup>
import { onMounted, ref, defineProps } from 'vue';

const props = defineProps({
    options: {
        type: Array,
        required: true,
    },
});

const model = ref('');

const select = ref(null);

onMounted(() => {
    if (select.value.hasAttribute('autofocus')) {
        select.value.focus();
    }
});

defineExpose({ focus: () => select.value.focus() });
</script>

<template>
    <select
        class="bg-gray-500 border-2 border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-200 focus:border-blue-200 block w-full p-2.5 dark:bg-gray-300 border 2 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
        ref="select"
        options="">
    </select>
</template>

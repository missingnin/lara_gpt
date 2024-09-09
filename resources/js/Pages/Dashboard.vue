<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, useForm} from '@inertiajs/vue3';
import InputLabel from "@/Components/InputLabel.vue";
import Textarea from "@/Components/Textarea.vue";
import TextInput from "@/Components/TextInput.vue";
import {computed, onMounted, ref, watch} from "vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const availableModels = [...ref(import.meta.env.VITE_GPT_MODELS.split(',')).value];

let form = useForm({
  instruction: '',
  model: availableModels[0],
  maxTokens: 0,
  context: {
    1: {
      title: '',
      value: ''
    },
  }
});

onMounted(() => {
  axios.get(route('getWidgetSettings'))
      .then((response) => {
        const formData = JSON.parse(response.data.value);
        Object.assign(form, formData);
      })
      .catch((error) => {
        console.error(error);
      });
});

const selectedContextTitle = ref(Object.values(form.context)[0].title);
const selectedContextIndex = computed(() => {
  const keys = Object.keys(form.context);
  for (const index of keys) {
    if (selectedContextTitle) {
      if (form.context[index].title === selectedContextTitle.value) {
        return Number(index);
      }
    }
  }

  return 1;
});

const selectedContextValue = ref(Object.values(form.context)[0].value);

watch(selectedContextIndex, (newIndex) => {
  if (newIndex !== null && newIndex in form.context) {
    selectedContextTitle.value = form.context[newIndex].title;
    selectedContextValue.value = form.context[newIndex].value;
  } else {
    selectedContextTitle.value = '';
    selectedContextValue.value = '';
  }
});

watch(selectedContextValue, (newValue) => {
  const index = selectedContextIndex.value;
  if (index in form.context) {
    form.context[index].value = newValue;
  }
});
const submit = () => {
  form.post(route('updateWidgetSettings'), {
    onFinish: () => console.log(form),
  });
};
</script>

<template>
  <Head title="Конфигурация виджета"/>

  <AuthenticatedLayout>
    <form @submit.prevent="submit">
      <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
          <div class="flex items-center justify-end">
            <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Изменения сохранены</p>
            <PrimaryButton class="py-2 ms-4" :class="{ 'opacity-25': form.processing }"
                           :disabled="form.processing">
              Сохранить изменения
            </PrimaryButton>
          </div>
        </div>
      </div>
      <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 min-h-dvh">
          <div
              class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8 flex-1 flex flex-row justify-between h-[750px]">
            <div class="w-full mr-5">
              <div class="h-full">
                <InputLabel class="mx-2" for="instructions" value="Инструкции"/>

                <Textarea
                    id="instructions"
                    type="instructions"
                    class="mt-2 block w-full h-[95%]"
                    v-model="form.instruction"
                    autofocus
                    autocomplete="username"
                />
              </div>
            </div>
            <div class="w-full ml-5">
              <div>
                <div class="flex-1 flex flex-row justify-between">
                  <div class="w-6/12">
                    <InputLabel for="prompt.select" value="Контекст"/>
                    <select
                        v-model="selectedContextTitle"
                        id="context"
                        :value="selectedContextTitle"
                        class="mt-2 mb-2 border-2 rounded-lg border-gray-300 w-full block text-sm font-medium text-gray-900 dark:text-gray focus:ring-blue-200 focus:border-blue-200">
                      <option disabled value="">Выберите контекст</option>
                      <option
                          v-for="(context, index) in form.context"
                          :key="index"
                          :value="context.title"
                          :selected="index === 1"
                      >{{ context.title }}
                      </option>
                    </select>
                  </div>
                  <div class="w-4/12 mx-3">
                    <InputLabel for="model" value="Модель"/>

                    <select
                        id="model"
                        class="mt-2 mb-2 border-2 rounded-lg border-gray-300 w-full block text-sm font-medium text-gray-900 focus:ring-blue-200 focus:border-blue-200"
                        v-model="form.model"
                    >
                      <option disabled>Выберите модель</option>
                      <option :value="availableModel" :key="availableModel" v-for="availableModel in availableModels">{{ availableModel }}</option>
                    </select>
                  </div>
                  <div class="w-2/12">
                    <InputLabel class="ml-4" for="max_tokens" value="Токены"/>

                    <TextInput
                        id="max_tokens"
                        type="max_tokens"
                        class="mt-2 block h-[2.45rem] w-full text-center"
                        v-model="form.maxTokens"
                    />
                  </div>
                </div>
              </div>
              <div class="h-full">
                <InputLabel for="instructions" :value="selectedContextTitle"/>

                <Textarea
                    id="instructions"
                    type="instructions"
                    class="mt-1 block w-full h-[84.3%]"
                    v-model="selectedContextValue"
                    autofocus
                    autocomplete="username"/>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </AuthenticatedLayout>
</template>

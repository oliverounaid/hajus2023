<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { useForm, Head } from '@inertiajs/vue3';
import Navbar from '@/Components/Navbar.vue';
import Chirp from '@/Components/Chirp.vue';

defineProps(['chirps', 'comments']);
const form = useForm({
  title: '',
  description: '',
});

</script>

<template>
  <Head title="Blog" />
  <Navbar />
  <AuthenticatedLayout>
    <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
      <h1 class="text-xl text-gray-800 indigo-700">Create new blog post</h1>
      <form class="pt-2" @submit.prevent="form.post(route('chirps.store'), { onSuccess: () => form.reset() })">
        <input type="text" placeholder="Blog title" v-model="form.title"
          class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
        <textarea v-model="form.description" placeholder="Blog content"
          class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"></textarea>
        <InputError :message="form.errors.title" class="mt-2" />
        <PrimaryButton class="mt-4">Submit</PrimaryButton>
      </form>
      <div class="mt-4 bg-white shadow-sm rounded-lg divide-y">
        <Chirp v-for="chirp in chirps" :key="chirp.id" :chirp="chirp" :comments="comments" />
      </div>
    </div>
  </AuthenticatedLayout>
</template>
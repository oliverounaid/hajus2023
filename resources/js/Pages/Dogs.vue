<script setup>
import Navbar from '@/Components/Navbar.vue';
import { ref, onMounted } from 'vue';
import { Link, Head } from '@inertiajs/vue3';

const dogs = ref([]);

const fetchData = async () => {
  try {
    const response = await fetch('/api/dogs');

    const data = await response.json();
    return data.data;
  } catch (error) {
    console.error(error);
    return [];
  }
};

onMounted(async () => {
  dogs.value = await fetchData();
});
</script>

<template>
  <Head title="Doggos" />
  <Navbar />
  <div class="mt-16 max-w-screen-lg w-full mx-auto flex-wrap px-6 mb-10">
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-12 lg:grid-cols-3">
      <div v-for="dog in dogs" :key="dog._id" class="w-72 flex flex-col shadow hover:shadow-lg rounded mx-auto">
        <img :src="dog.image" :alt="dog.title" class="h-64 object-cover w-full rounded-t">
        <div class="p-4">
          <h2 class="text-lg font-medium">{{ dog.title }}</h2>
          <p class="text-gray-600 text-sm mt-2">{{ dog.description }}</p>
          <div class="flex items-center mt-3">
            <span>Cuteness: {{ dog.cuteness }}</span>
          </div>
          <div class="mt-4">
            <span class="text-gray-600 text-sm">Weight: {{ dog.weight }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>



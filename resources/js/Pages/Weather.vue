<template>
<Head title="Weather" />
  <Navbar />
    <div class="flex p-6 items-center font-semibold gap-2 flex-col center" v-if="data">
      <img :src="weatherIcon" alt="" class="h-44" />
      <p class="text-xl font-bold">{{ data.name }}</p>
      <p><span class="font-light">Temperature: </span>{{Math.round(data.main.temp)}}℃</p>
      <p><span class="font-light">Feels like: </span>{{Math.round(data.main.feels_like)}}℃</p>
      <p><span class="font-light">Description: </span>{{data.weather[0].description}}</p>
    </div>

</template>

<script setup>
import { computed } from "@vue/runtime-core";
import Navbar from '../Components/Navbar.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
  data: Object,
  default: null,
});

const weatherIcon = computed(
  () => `https://openweathermap.org/img/w/${props.data?.weather[0].icon}.png`
);
console.log(props.data)
</script>
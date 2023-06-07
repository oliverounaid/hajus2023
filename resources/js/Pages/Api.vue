<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
  <Link href="/" class="text-xl">
     [&lt;--]
  </Link>
  <div>
    <pre>{{ jsonData }}</pre>
  </div>
</template>

<script>
export default {
  props: {
    jsonData: {
      type: Object,
      required: true,
    },
  },
};
</script>
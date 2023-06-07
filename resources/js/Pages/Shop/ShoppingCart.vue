<template>
  <Navbar />
  <div>
    <h2>Shopping Cart</h2>
    <div v-if="cart && cart.length > 0">
        <div class="border text-center">
          <div v-for="(item, index) in cart" :key="index">
            <div class="w-20"><img :src="item.image" alt="Product Image"></div>
            <div class="border p-2">{{ item.name }}</div>
            <div classs="border p-2">{{ item.price.formatted }}</div>
            <div class="border p-2">{{ item.qty }}</div>
          </div>
        </div>
    </div>
    <div v-else>
      Cart is empty.
    </div>
  </div>
</template>

<script setup>
import Navbar from '@/Components/Navbar.vue';

const props = defineProps({
  cart: {
  type: Object,
  default: null,
  },
});
</script>
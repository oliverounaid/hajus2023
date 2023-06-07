<script setup>
import Button from '@/Components/Button.vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
  product: {
    type: Object,
    default: null,
  }
})

const form = useForm({
  product: props.product,
  qty: 1
})

const submit = () => {
  form.post(route('cart.add'))
}

</script>
<template>
  <form @submit.prevent="submit">
    <div class="w-72 flex flex-col shadow hover:shadow-lg rounded">
      <img class="max-h-44 rounded-t" :src="product.image" alt="" />
      <div class="flex-1 mt-2 px-4">
        <p class="font-bold">{{ product.name }}</p>
      </div>
      <div class="px-4 pb-2 flex justify-between">
        <div class="flex-gap-2 item-center">
          <span>qty:</span>
          <input type="number" class="border border-gray-200 rounded focus:outline-none w-20" />
        </div>
        <p>{{ product.price.formatted }}</p>
      </div>
      <div class="px-4 pb-2 mt-4">
        <Button class="w-full items-center flex justify-center">Add to cart</Button>
      </div>
    </div>
  </form>
</template>
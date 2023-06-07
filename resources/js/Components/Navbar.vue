<template>
    <nav class="border-3 bg-gray-800">
        <div class="px-6 py-1 text-xl ">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center ">
                    <Link href="/weather"
                        class="inline-block text-xl px-4 py-2 leading-none border rounded text-white border-white hover:border-transparent hover:text-blue-500 hover:bg-white sm:mt-0 ml-4">
                    Weather
                    </Link>
                    <Link href="/map"
                        class="inline-block text-xl px-4 py-2 leading-none border rounded text-white border-white hover:border-transparent hover:text-blue-500 hover:bg-white sm:mt-0 ml-4">
                    Map
                    </Link>
                    <Link href="/chirps"
                        class="inline-block text-xl px-4 py-2 leading-none border rounded text-white border-white hover:border-transparent hover:text-blue-500 hover:bg-white sm:mt-0 ml-4">
                    Blog
                    </Link>
                    <Link href="/shop"
                        class="inline-block text-xl px-4 py-2 leading-none border rounded text-white border-white hover:border-transparent hover:text-blue-500 hover:bg-white sm:mt-0 ml-4">
                    Shop
                    </Link>
                    <Link href="/shopping-cart"
                        class="inline-block text-xl px-4 py-2 leading-none border rounded text-white border-white hover:border-transparent hover:text-blue-500 hover:bg-white sm:mt-0 ml-4">
                    Cart
                    </Link>
                    <Link href="/dogs"
                        class="inline-block text-xl px-4 py-2 leading-none border rounded text-white border-white hover:border-transparent hover:text-blue-500 hover:bg-white sm:mt-0 ml-4">
                    Dogs
                    </Link>
                    <Link href="/api"
                        class="inline-block text-xl px-4 py-2 leading-none border rounded text-white border-white hover:border-transparent hover:text-blue-500 hover:bg-white sm:mt-0 ml-4">
                    API
                    </Link>
                </div>
                <div>
                    <Link v-if="$page.props.auth.user" :href="route('dashboard')"
                        class="inline-block text-sm px-4 py-2 leading-none border rounded text-white border-white hover:border-transparent hover:text-blue-500 hover:bg-white sm:mt-0 ml-4">
                    Dashboard
                    </Link>
                    <template v-else>
                        <Link :href="route('login')"
                            class="inline-block text-sm px-4 py-2 leading-none border rounded text-white border-white hover:border-transparent hover:text-blue-500 hover:bg-white sm:mt-0 ml-4">
                        Log in
                        </Link>
                        <Link :href="route('register')"
                            class="inline-block text-sm px-4 py-2 leading-none border rounded text-white border-white hover:border-transparent hover:text-blue-500 hover:bg-white mt-4 sm:mt-0 ml-4">
                        Register
                        </Link>
                    </template>
                </div>
            </div>
        </div>
    </nav>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';


defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});
</script>
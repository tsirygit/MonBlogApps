<template>
    <div class="text-end">
        <Link :href="route('password.request')"> <p class="text-white">mot de passe oublié?</p></Link>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
</script>

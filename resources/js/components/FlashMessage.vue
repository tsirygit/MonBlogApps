<template>
    <div v-if="successMessage && showSuccess" class="mx-auto my-4 max-w-xl rounded bg-green-100 p-4 text-center text-green-800 shadow">
        {{ successMessage }}
    </div>
</template>

<script setup>
/**
 * Importation des outils Vue et Inertia
 */
import { usePage } from '@inertiajs/vue3';
import { computed, onMounted, ref } from 'vue';

/**
 * Accès aux props globales de la page via Inertia
 */
const page = usePage();

/**
 * Récupère dynamiquement le message de succès depuis les props flash
 */
const successMessage = computed(() => page.props.flash?.success);

/**
 * Contrôle l'affichage du message
 */
const showSuccess = ref(true);

/**
 * Cache le message après 3 secondes
 */
onMounted(() => {
    setTimeout(() => {
        showSuccess.value = false;
    }, 3000);
});
</script>

<template>
    <transition name="fade">
        <div v-if="showNotification" class="fixed right-4 top-1/2 z-50 rounded bg-red-600 p-4 shadow-lg">
            <p class="text-normal text-center text-sm text-black">{{ comment.title }}</p>
            <p class="text-center text-xl font-bold text-white">{{ comment.name }}:</p>
            <p class="text-center text-lg font-semibold text-white">{{ comment.content }}</p>
        </div>
    </transition>
</template>

<script setup>
import { ref } from 'vue';

/**
 * Contrôle l'affichage du toast
 */
const showNotification = ref(false);

/**
 * Contenu du toast (objet avec title, name, content)
 */
const comment = ref('');

/**
 * Affiche le toast avec le contenu donné et le masque après 5 secondes
 * @param {Object|string} content - Contenu à afficher (objet ou texte brut)
 */
const toggleNotification = (content = 'Notification') => {
    comment.value = content;
    showNotification.value = true;
    setTimeout(() => (showNotification.value = false), 5000);
};

/**
 * Expose la méthode toggleToast pour être appelée depuis le parent
 */
defineExpose({ toggleNotification });
</script>

<style scoped>
/* Animation de fondu pour l'apparition/disparition du toast */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s ease;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>

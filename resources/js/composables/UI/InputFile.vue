<template>
    <div class="mb-3">
        <label for="image" class="mb-2 block text-sm font-medium text-white">image: </label>
        <input
            type="file"
            @input="$emit('update:modelValue', $event.target.files[0])"
            class="w-full rounded-md border-solid border-white bg-white p-2 outline-0 outline-offset-0"
        />
        <div v-if="error" class="text-red-500">{{ error }}</div>
    </div>
</template>

<script setup>
/**
 * Props du composant ImageInput
 *
 * @prop {File} modelValue - Fichier image sélectionné, lié via v-model
 * @prop {String} error - Message d'erreur à afficher sous le champ
 */
const props = defineProps({
    modelValue: File,
    error: String,
});

/**
 * Événement émis à chaque sélection de fichier
 *
 * @event update:modelValue - Envoie le fichier sélectionné au parent
 */
const emit = defineEmits(['update:modelValue']);
</script>

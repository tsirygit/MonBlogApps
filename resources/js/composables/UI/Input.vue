<template>
    <div class="relative z-10">
        <label :for="id" class="mb-2 block text-sm font-medium text-white">
            {{ label }}
        </label>

        <component
            :is="type === 'textarea' ? 'textarea' : 'input'"
            :id="id"
            :rows="type === 'textarea' ? rows : undefined"
            :placeholder="placeholder"
            :type="type !== 'textarea' ? type : undefined"
            :value="modelValue"
            @input="$emit('update:modelValue', $event.target.value)"
            class="w-full rounded-md border-solid border-white bg-white border-2 p-2 outline-0 outline-offset-0"
        >
        </component>

        <div v-if="error" class="mt-1 text-start text-lg text-red-500">
            {{ Array.isArray(error) ? error[0] : error }}
        </div>
    </div>
</template>

<script setup>
/**
 * Props du composant FormInput
 *
 * @prop {String} id - Identifiant unique du champ (utilisé pour le label et l'accessibilité)
 * @prop {String} label - Texte affiché au-dessus du champ
 * @prop {String} type - Type du champ ('text', 'email', 'textarea', etc.)
 * @prop {String} [placeholder] - Texte d'exemple dans le champ (optionnel)
 * @prop {Number} [rows=4] - Nombre de lignes pour les textarea (optionnel)
 * @prop {String|Number} [modelValue] - Valeur liée au champ via v-model
 * @prop {String|Array} [error] - Message(s) d'erreur à afficher sous le champ
 */
defineProps({
  id: { type: String, required: true },
  label: { type: String, required: true },
  type: { type: String, required: true },
  placeholder: { type: String, default: '' },
  rows: { type: Number, default: 4 },
  modelValue: [String, Number],
  error: [String, Array],
});

/**
 * Événement émis à chaque saisie pour synchroniser la valeur avec le parent
 *
 * @event update:modelValue
 */
defineEmits(['update:modelValue']);
</script>

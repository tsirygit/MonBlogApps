<template>
    <Head title="inserez votre commentaire..." />
    <NavbarLayout />
    <div class="flex items-center justify-center">
        <div class="w-full max-w-sm rounded-lg bg-slate-900 p-4 shadow-lg lg:max-w-lg">
            <h1 class="mb-2 text-center text-xl font-bold text-white md:text-2xl">commenté le post</h1>
            <div class="mt-8 justify-items-center">
                <form @submit.prevent="submit" class="w-full">
                    <div v-for="(item, index) in inputs" :key="index.id">
                        <Input
                            v-model="form[item.id]"
                            :id="item.id"
                            :label="item.label"
                            :type="item.type"
                            :placeholder="item.placeholder"
                            :rows="item.rows"
                            :error="form.errors[item.id]"
                        />
                    </div>
                    <div class="mt-4 justify-items-center">
                        <div>
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="w-48 cursor-pointer bg-green-600 p-2 text-center font-bold text-white md:w-80 lg:w-80"
                            >
                                <span v-if="form.processing" class="flex animate-spin justify-center text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                        <g fill="white">
                                            <path
                                                fill-rule="evenodd"
                                                d="M12 19a7 7 0 1 0 0-14a7 7 0 0 0 0 14m0 3c5.523 0 10-4.477 10-10S17.523 2 12 2S2 6.477 2 12s4.477 10 10 10"
                                                clip-rule="evenodd"
                                                opacity="0.2"
                                            />
                                            <path d="M2 12C2 6.477 6.477 2 12 2v3a7 7 0 0 0-7 7z" />
                                        </g>
                                    </svg>
                                </span>
                                <span v-else>commenter</span>
                            </button>
                        </div>
                    </div>
                    <div class="mx-6 mt-4 text-center text-xl">
                        <Link :href="route('homepage')" class="text-white hover:underline">Annuler la commentaire</Link>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
/**
 * Composant : CreateComment.vue
 *
 * Permet à l'utilisateur d'ajouter un commentaire à un post.
 * Utilise Inertia.js pour envoyer une requête POST à la route 'comment.store'.
 */

import Input from '@/composables/UI/Input.vue'; // Champ de texte réutilisable
import NavbarLayout from '@/layouts/NavbarLayout.vue'; // Barre de navigation
import { Head, Link, useForm } from '@inertiajs/vue3'; // Outils Inertia pour navigation et formulaire
import { ref } from 'vue';

/**
 * Définition des champs du formulaire
 * Chaque champ contient :
 * - id : identifiant du champ
 * - label : texte du label affiché
 * - type : type HTML du champ ('textarea' ici)
 * - placeholder : texte d'exemple
 * - rows : nombre de lignes (optionnel)
 * - error : message d'erreur (lié dynamiquement via form.errors)
 */
const inputs = ref([
    {
        id: 'content',
        label: 'votre commentaire ici',
        type: 'textarea',
        placeholder: 'Entrez votre commentaire....',
        rows: undefined,
        error: undefined,
    },
]);

/**
 * Props reçues du backend via Inertia
 *
 * @prop {Object} post - Données du post auquel le commentaire est lié
 */
const props = defineProps({
    post: Object,
});

/**
 * Initialisation du formulaire avec les champs requis
 * - post_id : identifiant du post
 * - content : contenu du commentaire
 */
const form = useForm({
    post_id: props.post.id,
    content: '',
});

/**
 * Soumission du formulaire
 * Envoie une requête POST à la route 'comment.store'
 * Réinitialise le formulaire en cas de succès
 */
const submit = () => {
    form.post(route('comment.store'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
        },
    });
};
</script>

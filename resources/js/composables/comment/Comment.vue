<template>
    <div class="px-10">
        <h3 class="mt-6 text-center text-2xl font-bold text-gray-800">Commentaires du post:</h3>
        <div class="mt-10 space-y-4">
            <div v-if="post.comments.length">
                <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
                    <div v-for="comment in post.comments" :key="comment.id">
                        <div class="max-h-48 w-64 overflow-y-auto rounded-md border bg-white p-2 shadow">
                            <span class="font-semibold text-gray-800">{{ comment.user.name }}:</span>
                            <p>le {{ comment.created_at_formatted }}</p>
                            <p class="break-words text-center text-lg text-gray-700">{{ comment.content }}</p>
                            <div class="mt-4 flex justify-center gap-4">
                                <Link
                                    :href="route('comment.edit', comment.id)"
                                    class="inline-flex items-center rounded-lg bg-blue-700 px-3 py-2 text-center text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 2048 2048">
                                        <path
                                            fill="currentColor"
                                            d="M256 1408V512h1536v232q-55 8-107 32t-91 63l-569 569zm641 128l-51 52l-19 76H0V256h2048v540q-29-19-61-31t-67-19V384H128v1152zm951-640q42 0 78 15t64 41t42 63t16 79q0 39-15 76t-43 65l-717 717l-377 94l94-377l717-716q29-29 65-43t76-14m51 249q21-21 21-51q0-31-20-50t-52-20q-14 0-27 4t-23 15l-692 692l-34 135l135-34z"
                                        />
                                    </svg>
                                </Link>

                                <button
                                    @click.prevent="submit(comment.id)"
                                    class="inline-flex items-center rounded-lg bg-blue-600 px-3 py-2 text-center text-sm font-medium text-white hover:bg-red-600 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                        <path
                                            fill="white"
                                            d="M7 21q-.825 0-1.412-.587T5 19V6H4V4h5V3h6v1h5v2h-1v13q0 .825-.587 1.413T17 21zm2-4h2V8H9zm4 0h2V8h-2z"
                                        />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-else class="text-sm text-gray-400">Aucun commentaire pour ce post.</div>
        </div>
    </div>
</template>

<script setup>
/**
 * Composant : Comment.vue
 *
 * Permet de supprimer un commentaire spécifique lié à un post.
 * Utilise Inertia.js pour envoyer une requête DELETE à la route 'comment.destroy'.
 */

import { Link, useForm } from '@inertiajs/vue3'; // Outils Inertia pour navigation et formulaire
import route from 'ziggy-js';
/**
 * Props reçues du parent
 *
 * @prop {Object} post - L'objet post auquel les commentaires sont liés
 */
const props = defineProps({
    post: Object,
});

/**
 * Initialisation du formulaire Inertia
 * - form.processing : indique si une requête est en cours
 * - form.errors : contient les erreurs de validation (non utilisées ici)
 */
const form = useForm({});

/**
 * Fonction de suppression d'un commentaire
 *
 * @param {Number} commentId - L'identifiant du commentaire à supprimer
 */
const submit = (commentId) => {
    form.delete(route('comment.destroy', commentId), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset(); // Réinitialise le formulaire après suppression
        },
    });
};
</script>

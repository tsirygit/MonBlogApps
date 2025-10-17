<template>
     <Head title="information sur le post" />
    <NavbarLayout />
    <div class="flex justify-center">
        <div class="mt-5 px-20 lg:mt-10">
            <div
                class="flex flex-col items-center gap-10 rounded-lg border border-gray-200 bg-white p-4 shadow-sm hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700 md:max-w-xl md:flex-row"
            >
                <div v-if="post">
                    <p v-if="post.user" class="text-sm text-gray-500 dark:text-gray-400">
                        Publié par <span class="font-semibold">{{ post.user.name }}</span
                        ><br />
                        <strong>le {{ post.created_at_formatted }}</strong>
                    </p>
                    <p v-else class="text-sm text-red-500">Auteur inconnu</p>

                    <img class="mt-6 hover:scale-105 md:mt-8" :src="getImageUrl(post.image)" alt="" />
                    <div class="flex flex-col justify-between p-4 leading-normal">
                        <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                            {{ post.title }}
                        </h5>
                        <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">
                            {{ post.content }}
                        </p>
                        <div class="flex flex-row justify-center gap-4">
                            <button
                                @click.prevent="like(post.id, true)"
                                :class="[
                                    'inline-flex items-center rounded-lg px-3 py-2 text-center text-sm font-medium text-white focus:outline-none focus:ring-4',
                                    likedPosts[post.id] ? 'bg-red-600' : 'bg-blue-600 hover:bg-blue-700',
                                    'focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800',
                                ]"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                    <path
                                        fill="#fff"
                                        d="M23 10a2 2 0 0 0-2-2h-6.32l.96-4.57c.02-.1.03-.21.03-.32c0-.41-.17-.79-.44-1.06L14.17 1L7.59 7.58C7.22 7.95 7 8.45 7 9v10a2 2 0 0 0 2 2h9c.83 0 1.54-.5 1.84-1.22l3.02-7.05c.09-.23.14-.47.14-.73zM1 21h4V9H1z"
                                    />
                                </svg>
                                <span class="ml-2 text-sm text-white">
                                    {{ post.likes_count }}
                                </span>
                            </button>

                            <div
                                class="inline-flex items-center rounded-lg bg-blue-700 px-3 py-2 text-center text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                    <path
                                        fill="#fff"
                                        d="M6 14h12v-2H6zm0-3h12V9H6zm0-3h12V6H6zM4 18q-.825 0-1.412-.587T2 16V4q0-.825.588-1.412T4 2h16q.825 0 1.413.588T22 4v18l-4-4z"
                                    />
                                </svg>
                                <span class="ml-2 text-sm text-white">
                                    {{ post.comments_count }}
                                </span>
                            </div>

                            <Link
                                :href="route('post.edit', post.id)"
                                class="inline-flex items-center rounded-lg bg-blue-700 px-3 py-2 text-center text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 2048 2048">
                                    <path
                                        fill="currentColor"
                                        d="M256 1408V512h1536v232q-55 8-107 32t-91 63l-569 569zm641 128l-51 52l-19 76H0V256h2048v540q-29-19-61-31t-67-19V384H128v1152zm951-640q42 0 78 15t64 41t42 63t16 79q0 39-15 76t-43 65l-717 717l-377 94l94-377l717-716q29-29 65-43t76-14m51 249q21-21 21-51q0-31-20-50t-52-20q-14 0-27 4t-23 15l-692 692l-34 135l135-34z"
                                    />
                                </svg>
                            </Link>
                            <button
                                @click.prevent="submit(post.id)"
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
    </div>
    <div>
        <div>
            <FlashMessage />
        </div>
    </div>
    <div>
        <Comment :post="post" />
    </div>
</template>

<script setup>
/**
 * Composant : PostDetail.vue
 *
 * Gère l'affichage d'un post individuel avec ses interactions :
 * - Affichage des commentaires
 * - Like / Dislike du post
 * - Suppression du post
 */

import FlashMessage from '@/components/FlashMessage.vue'; // Composant pour afficher les messages de succès
import Comment from '@/composables/comment/Comment.vue'; // Composant pour afficher les commentaires
import NavbarLayout from '@/layouts/NavbarLayout.vue'; // Barre de navigation

import { Link, useForm, Head } from '@inertiajs/vue3'; // Outils Inertia pour navigation et formulaire
import { onMounted, reactive } from 'vue';
import route from 'ziggy-js';

/**
 * Props reçues du backend via Inertia
 *
 * @prop {Object} post - Données du post à afficher
 */
const props = defineProps({
    post: Object,
});

/**
 * Formulaire Inertia utilisé pour les actions like/dislike et suppression
 */
const form = useForm({
    post_id: null,
    like: false,
});

/**
 * Génère l'URL complète de l'image du post
 *
 * @param {string|null} Path - Chemin relatif de l'image
 * @returns {string|null}
 */
const getImageUrl = (Path) => {
    return Path ? `/storage/${Path}` : null;
};

/**
 * Objet réactif pour suivre l'état du like du post
 */
const likedPosts = reactive({});

/**
 * Fonction pour liker ou disliker un post
 *
 * @param {number} postId - ID du post
 * @param {boolean} like - true pour liker, false pour disliker
 */
const like = (postId, like) => {
    form.post_id = postId;
    form.like = like;

    form.post(route('likedislike'), {
        preserveScroll: true,
        onSuccess: () => {
            likedPosts[postId] = !likedPosts[postId];
        },
        onError: (errors) => {
            console.error('Erreur:', errors);
        },
    });
};

/**
 * Initialisation du statut de like au montage du composant
 */
onMounted(() => {
    if (props.post) {
        likedPosts[props.post] = props.post.isLiked;
    }
});

/**
 * Supprime le post
 *
 * @param {number} postId - ID du post à supprimer
 */
const submit = (postId) => {
    form.delete(route('post.destroy', postId));
};
</script>

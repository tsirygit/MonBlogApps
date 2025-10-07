<template>
    <Head title="S'inscrire..." />
    <div class="flex min-h-screen items-center justify-center">
        <div class="w-full max-w-sm rounded-lg bg-slate-900 p-8 shadow-lg">
            <h1 class="mb-2 text-center text-xl font-bold text-white md:text-2xl">creer de nouveau compte</h1>
            <div class="mt-8 justify-items-center">
                <form @submit.prevent="login" class="w-full">
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
                                <span v-else>S'inscrire</span>
                            </button>
                        </div>
                    </div>
                    <div class="mx-6 mt-4 text-center text-sm">
                        <p class="text-green-400">Vous déja un compte ???</p>
                        <Link :href="route('login')" class="text-white hover:underline">connectez maintenant</Link>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
/**
 * Composant : RegisterForm.vue
 *
 * Permet à un nouvel utilisateur de créer un compte.
 * Utilise Inertia.js pour envoyer une requête POST à la route 'register'.
 */

import Input from '@/composables/UI/Input.vue'; // Champ de formulaire réutilisable
import { Head, Link, useForm } from '@inertiajs/vue3'; // Outils Inertia pour navigation et formulaire
import { ref } from 'vue';

/**
 * Définition des champs du formulaire
 * Chaque champ contient :
 * - id : identifiant du champ
 * - label : texte du label affiché
 * - type : type HTML du champ
 * - placeholder : texte d'exemple
 * - rows : utilisé uniquement pour les textarea (non utilisé ici)
 * - error : message d'erreur (lié dynamiquement via form.errors)
 */
const inputs = ref([
    {
        id: 'name',
        label: 'nom',
        type: 'text',
        placeholder: 'Entrez votre nom....',
        rows: undefined,
        error: undefined,
    },
    {
        id: 'email',
        label: 'email:',
        type: 'email',
        placeholder: 'Entrez votre adresse email....',
        rows: undefined,
        error: undefined,
    },
    {
        id: 'password',
        label: 'mot de passe:',
        type: 'password',
        placeholder: 'Entrez votre mot de passe....',
        rows: undefined,
        error: undefined,
    },
    {
        id: 'password_confirmation',
        label: 'confirmation de mot de passe:',
        type: 'password',
        placeholder: 'Confirmez votre mot de passe....',
        rows: undefined,
        error: undefined,
    },
]);

/**
 * Initialisation du formulaire avec les champs requis
 * - name : nom de l'utilisateur
 * - email : adresse email
 * - password : mot de passe
 * - password_confirmation : confirmation du mot de passe
 */
const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

/**
 * Soumission du formulaire
 * Envoie une requête POST à la route 'register'
 * Réinitialise le formulaire en cas de succès
 */
const login = () => {
    form.post(route('register'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
        },
    });
};
</script>

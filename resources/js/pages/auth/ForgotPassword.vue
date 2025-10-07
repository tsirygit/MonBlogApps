<template>
    <Head title="verifié votre address email..." />
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="w-full max-w-sm rounded-lg bg-slate-900 p-8 shadow-lg">
            <h1 class="mb-4 text-center text-xl font-bold text-white md:text-2xl">verifié votre email</h1>
            <div class="my-4 mt-8 flex justify-center">
                <form @submit.prevent="submit" class="w-full">
                    <div v-for="(item, index) in inputs" :key="index.id" class="mb-3">
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
                    <div class="mt-4">
                        <div>
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="w-full cursor-pointer bg-green-600 p-2 text-center font-bold text-white"
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
                                <span v-else>verifié votre email</span>
                            </button>
                        </div>

                        <div class="mt-4 text-end">
                            <Link :href="route('login')" class="text-sm text-white hover:underline">retour au page de connexion</Link>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
/**
 * Composant : ForgotPasswordForm.vue
 *
 * Permet à l'utilisateur de demander un lien de réinitialisation de mot de passe.
 * Utilise Inertia.js pour envoyer une requête POST à la route 'password.email'.
 */

import Input from '@/composables/UI/Input.vue'; // Champ de formulaire réutilisable
import { Head, Link, useForm } from '@inertiajs/vue3'; // Outils Inertia pour navigation et formulaire
import { ref } from 'vue';

/**
 * Définition du champ du formulaire
 * - id : identifiant du champ
 * - label : texte du label affiché
 * - type : type HTML du champ
 * - placeholder : texte d'exemple
 * - rows : non utilisé ici
 * - error : message d'erreur (lié dynamiquement via form.errors)
 */
const inputs = ref([
    {
        id: 'email',
        label: 'email',
        type: 'email',
        placeholder: 'Entrez votre adresse email...',
        rows: undefined,
        error: undefined,
    },
]);

/**
 * Initialisation du formulaire avec les champs requis
 * - email : adresse email de l'utilisateur
 * - password : champ inutile ici (peut être retiré)
 */
const form = useForm({
    email: '',
});

/**
 * Soumission du formulaire
 * Envoie une requête POST à la route 'password.email'
 * Réinitialise le champ email en cas de succès
 */
const submit = () => {
    form.post(route('password.email'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
        },
    });
};
</script>

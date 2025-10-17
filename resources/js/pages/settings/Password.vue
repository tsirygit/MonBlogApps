<template>
    <Head title="mot de passe..." />
    <NavbarLayout />
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="w-full max-w-sm rounded-lg bg-slate-900 p-8 shadow-lg">
            <h1 class="mb-4 text-center text-xl font-bold text-white md:text-2xl">mettre à jour votre mot de passe</h1>
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
                    <div v-if="form.errors.message" class="mt-1 text-center text-lg text-red-500">
                        {{ form.errors.message }}
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
                                <span v-else>changer votre mot de passe</span>
                            </button>
                        </div>
                    </div>
                    <div class="mx-6 mt-4 text-center text-sm">
                        <Link :href="route('homepage')" class="text-white hover:underline">retour au page d'accueill</Link>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
/**
 * Composant : PasswordUpdate.vue
 *
 * Permet à l'utilisateur de modifier son mot de passe via un formulaire sécurisé.
 * Utilise Inertia.js pour envoyer les données au backend via une requête PUT.
 */

import Input from '@/composables/UI/Input.vue'; // Champ de formulaire réutilisable
import NavbarLayout from '@/layouts/NavbarLayout.vue'; // Barre de navigation
import { Head, Link, useForm } from '@inertiajs/vue3'; // Outils Inertia
import { ref } from 'vue';
import route from 'ziggy-js';

/**
 * Définition des champs du formulaire
 * Chaque champ contient :
 * - id : identifiant du champ (clé du formulaire)
 * - label : texte du label affiché
 * - type : type HTML du champ (ici tous en 'password')
 * - placeholder : texte d'exemple
 * - rows : utilisé uniquement pour les textarea (non utilisé ici)
 * - error : message d'erreur (lié dynamiquement via form.errors)
 */
const inputs = ref([
  {
    id: 'current_password',
    label: 'Mot de passe actuel',
    type: 'password',
    placeholder: 'Entrez votre mot de passe actuel...',
    rows: undefined,
    error: undefined,
  },
  {
    id: 'password',
    label: 'Nouveau mot de passe',
    type: 'password',
    placeholder: 'Entrez votre nouveau mot de passe...',
    rows: undefined,
    error: undefined,
  },
  {
    id: 'password_confirmation',
    label: 'Confirmez le nouveau mot de passe',
    type: 'password',
    placeholder: 'Confirmez votre nouveau mot de passe...',
    rows: undefined,
    error: undefined,
  },
]);

/**
 * Initialisation du formulaire avec les champs requis
 * - form.processing : indique si une requête est en cours
 * - form.errors : contient les erreurs de validation
 */
const form = useForm({
  current_password: '',
  password: '',
  password_confirmation: '',
});

/**
 * Soumission du formulaire
 * Envoie une requête PUT à la route 'password.update'
 * Réinitialise le formulaire en cas de succès
 */
const submit = () => {
  form.put(route('password.update'), {
    preserveScroll: true,
    onSuccess: () => {
      form.reset();
    },
  });
};
</script>


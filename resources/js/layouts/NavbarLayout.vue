<template>
    <nav class="border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-900">
        <div class="mx-auto flex max-w-screen-xl flex-wrap items-center justify-between p-4">
            <Link :href="route('homepage')" class="flex items-center space-x-3 rtl:space-x-reverse">
                <span class="self-center whitespace-nowrap text-2xl font-semibold dark:text-white">MonApp</span>
            </Link>

            <button
                @click="toggleMobileMenu"
                class="inline-flex h-10 w-10 items-center justify-center rounded-lg p-2 text-sm text-gray-500 hover:bg-gray-100 focus:outline-none dark:text-gray-400 dark:hover:bg-gray-700 md:hidden"
            >
                <svg class="h-5 w-5" fill="none" viewBox="0 0 17 14" xmlns="http://www.w3.org/2000/svg">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15" />
                </svg>
            </button>

            <div :class="{ hidden: !isMobileMenuOpen, 'w-full': true, 'md:block': true, 'md:w-auto': true }">
                <ul
                    class="d:mt-0 mt-4 flex flex-col rounded-lg border border-gray-100 bg-gray-50 p-4 font-medium dark:bg-gray-800 md:flex-row md:space-x-8 md:border-0 md:bg-white md:p-0 md:dark:bg-gray-900"
                >
                    <li>
                        <Link :href="route('homepage')" class="block rounded px-3 py-2 text-blue-700 md:bg-transparent md:p-0 md:text-blue-700"
                            >Home</Link
                        >
                    </li>

                    <li>
                        <Link :href="route('post.create')" class="block rounded px-3 py-2 text-blue-700 md:bg-transparent md:p-0 md:text-blue-700"
                            >publié un post</Link
                        >
                    </li>

                    <li class="relative">
                        <button
                            @click="toggleDropdown"
                            class="flex w-full items-center justify-between rounded px-3 py-2 text-gray-900 dark:text-white dark:hover:text-blue-500 md:p-0 md:hover:text-blue-700"
                        >
                            <div v-if="$page.props.auth.user" class="mb-2">
                                <h1 class="text-start text-2xl font-bold">{{ $page.props.auth.user.name }}</h1>
                            </div>
                            <svg class="ms-2.5 h-2.5 w-2.5" fill="none" viewBox="0 0 10 6" xmlns="http://www.w3.org/2000/svg">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4" />
                            </svg>
                        </button>
                        <div
                            v-if="isDropdownOpen"
                            class="absolute z-10 mt-2 w-32 divide-y divide-gray-100 rounded-lg bg-white shadow dark:divide-gray-600 dark:bg-gray-700"
                        >
                            <ul class="py-2 text-sm text-gray-700 dark:text-gray-400">
                                <li>
                                    <Link
                                        :href="route('profile.edit')"
                                        class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
                                        >profile</Link
                                    >
                                </li>
                                <li>
                                    <Link
                                        :href="route('password.edit')"
                                        class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
                                        >password info</Link
                                    >
                                </li>
                                <li></li>
                            </ul>
                            <form class="py-1" @submit.prevent="logout">
                                <button>
                                    <span class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Logout</span>
                                </button>
                            </form>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</template>

<script setup>
/**
 * Composant : Navbar.vue
 *
 * Gère l'affichage du menu de navigation, la déconnexion utilisateur,
 * et les notifications en temps réel via Laravel Echo.
 */

import { Link, useForm } from '@inertiajs/vue3'; // Outils Inertia pour navigation et formulaire
import { ref } from 'vue';

/**
 * État du menu mobile (responsive)
 */
const isMobileMenuOpen = ref(false);

/**
 * État du menu déroulant (dropdown)
 */
const isDropdownOpen = ref(false);

/**
 * Toggle du menu mobile
 */
const toggleMobileMenu = () => {
    isMobileMenuOpen.value = !isMobileMenuOpen.value;
};

/**
 * Toggle du menu déroulant
 */
const toggleDropdown = () => {
    isDropdownOpen.value = !isDropdownOpen.value;
};

/**
 * Formulaire vide utilisé pour la déconnexion
 */
const form = useForm({});

/**
 * Déconnexion de l'utilisateur
 * Envoie une requête POST à la route 'logout'
 */
const logout = () => {
    form.post(route('logout'));
};
</script>

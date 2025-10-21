import Edit from '@/Pages/Posts/Edit.vue';
import { mount } from '@vue/test-utils';
import { describe, expect, it, vi } from 'vitest';

// Mock des composants enfants
vi.mock('@/composables/UI/Input.vue', () => ({
    default: {
        name: 'Input',
        template: '<input />',
        props: ['modelValue', 'id', 'label', 'type', 'placeholder', 'rows', 'error'],
    },
}));

vi.mock('@/composables/UI/InputFile.vue', () => ({
    default: {
        name: 'InputFile',
        template: '<input type="file" />',
        props: ['modelValue', 'error'],
    },
}));

vi.mock('@/layouts/NavbarLayout.vue', () => ({
    default: {
        name: 'NavbarLayout',
        template: '<nav />',
    },
}));

vi.mock('@inertiajs/vue3', async () => {
    const actual = await vi.importActual('@inertiajs/vue3');
    return {
        ...actual,
        useForm: () => ({
            title: 'Titre initial',
            content: 'Contenu initial',
            image: null,
            errors: {},
            processing: false,
            post: vi.fn(),
            reset: vi.fn(),
        }),
        Head: {
            name: 'Head',
            template: '<div />',
        },
        Link: {
            name: 'Link',
            template: '<a><slot /></a>',
            props: ['href'],
        },
    };
});

vi.mock('ziggy-js', () => ({
    default: (name, id) => {
        const routes = {
            'post.update': `/post/update/${id}`,
            homepage: '/',
        };
        return routes[name] || '/';
    },
}));

describe('Edit.vue', () => {
    it('affiche le formulaire avec les champs requis', () => {
        const wrapper = mount(Edit, {
            props: {
                post: {
                    id: 1,
                    title: 'Titre initial',
                    content: 'Contenu initial',
                },
                errors: {},
            },
        });

        expect(wrapper.text()).toContain('modification');
        expect(wrapper.find('input[type="file"]').exists()).toBe(true);
        expect(wrapper.find('input').exists()).toBe(true);
        expect(wrapper.find('button[type="submit"]').text()).toContain('modifier');
    });

    it('appelle la fonction submit lors de l’envoi du formulaire', async () => {
        const wrapper = mount(Edit, {
            props: {
                post: {
                    id: 1,
                    title: 'Titre initial',
                    content: 'Contenu initial',
                },
                errors: {},
            },
        });

        await wrapper.find('form').trigger('submit.prevent');
        expect(wrapper.vm.form.post).toHaveBeenCalled();
    });
    
});

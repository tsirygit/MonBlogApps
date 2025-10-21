import Edit from '@/Pages/Comments/Edit.vue'; // adapte le chemin si nécessaire
import { mount } from '@vue/test-utils';
import { beforeEach, describe, expect, it, vi } from 'vitest';

// 🧩 Mock des dépendances
vi.mock('@inertiajs/vue3', () => ({
    useForm: vi.fn(),
    Head: { name: 'Head', template: '<div />' },
    Link: { name: 'Link', props: ['href'], template: '<a :href="href"><slot /></a>' },
}));

vi.mock('ziggy-js', () => ({
    default: (name, id) => {
        const routes = {
            'comment.update': `/comment/update/${id}`,
            homepage: '/',
        };
        return routes[name] || '/';
    },
}));

import { useForm } from '@inertiajs/vue3';

describe('Edit.vue', () => {
    let formMock;

    beforeEach(() => {
        formMock = {
            patch: vi.fn(),
            reset: vi.fn(),
            processing: false,
            errors: {},
            content: '',
        };

        useForm.mockReturnValue(formMock);
    });

    const mountComponent = () => {
        return mount(Edit, {
            props: {
                comment: { id: 42, content: 'Ancien commentaire' },
            },
            global: {
                stubs: {
                    Input: true,
                    NavbarLayout: true,
                },
            },
        });
    };

    it('affiche le titre et le bouton', () => {
        const wrapper = mountComponent();
        expect(wrapper.text()).toContain('modifier le commentaire');
        expect(wrapper.find('button[type="submit"]').exists()).toBe(true);
    });

    it('met à jour le champ content quand l’utilisateur saisit du texte', async () => {
        const wrapper = mountComponent();
        const input = wrapper.findComponent({ name: 'Input' });

        await input.vm.$emit('update:modelValue', 'Nouveau commentaire');
        expect(formMock.content).toBe('Nouveau commentaire');
    });

    it('soumet le formulaire avec les bonnes données', async () => {
        const wrapper = mountComponent();

        await wrapper.find('form').trigger('submit.prevent');

        expect(formMock.patch).toHaveBeenCalledWith('/comment/update/42', {
            preserveScroll: true,
            onSuccess: expect.any(Function),
        });

        formMock.patch.mock.calls[0][1].onSuccess();
        expect(formMock.reset).toHaveBeenCalled();
    });

    it('désactive le bouton si le formulaire est en cours de traitement', () => {
        formMock.processing = true;
        const wrapper = mountComponent();

        const button = wrapper.find('button[type="submit"]');
        expect(button.attributes('disabled')).toBeDefined();
    });

    it('affiche le lien d’annulation vers la page d’accueil', () => {
        const wrapper = mountComponent();
        const link = wrapper.find('a');
        expect(link.exists()).toBe(true);
        expect(link.attributes('href')).toBe('/');
        expect(link.text()).toContain('Annuler la modification du commentaire');
    });
});

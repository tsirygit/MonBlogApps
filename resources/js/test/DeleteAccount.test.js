import DeleteAccount from '@/composables/settings/DeleteAccount.vue'; // adapte le chemin
import Input from '@/composables/UI/Input.vue';
import { mount } from '@vue/test-utils';
import { describe, expect, it, vi } from 'vitest';

vi.mock('@inertiajs/vue3', () => {
    return {
        Link: {
            name: 'Link',
            props: ['href'],
            template: `<a :href="href"><slot /></a>`,
        },
        useForm: () => {
            return {
                password: '',
                processing: false,
                errors: {},
                delete: vi.fn(),
                reset: vi.fn(),
            };
        },
    };
});

vi.mock('ziggy-js', () => ({
    default: (name) => {
        const routes = {
            'profile.edit': '/profile/edit',
            'profile.destroy': '/profile/destroy',
        };
        return routes[name] || '/';
    },
}));

describe('DeleteAccount.vue', () => {
    it('affiche le titre et le texte de confirmation', () => {
        const wrapper = mount(DeleteAccount);
        expect(wrapper.text()).toContain('supprimez votre compte');
        expect(wrapper.text()).toContain('vous-etez sur que vous supprimez votre compte');
    });

    it('affiche le champ mot de passe', () => {
        const wrapper = mount(DeleteAccount);
        const input = wrapper.findComponent(Input);
        expect(input.exists()).toBe(true);
        expect(input.props('id')).toBe('password');
        expect(input.props('label')).toBe('Votre mot de passe actuel');
    });

    it('affiche le lien annuler vers /profile/edit', () => {
        const wrapper = mount(DeleteAccount);
        const link = wrapper.find('a');
        expect(link.exists()).toBe(true);
        expect(link.attributes('href')).toBe('/profile/edit');
        expect(link.text()).toContain('annuler');
    });

    it('affiche le bouton supprimez', () => {
        const wrapper = mount(DeleteAccount);
        const button = wrapper.find('button[type="submit"]');
        expect(button.exists()).toBe(true);
        expect(button.text()).toContain('supprimez');
    });

    it('soumet le formulaire avec la bonne route', async () => {
        const wrapper = mount(DeleteAccount);
        const form = wrapper.vm.form;
        await wrapper.find('form').trigger('submit.prevent');
        expect(form.delete).toHaveBeenCalledWith('/profile/destroy', expect.any(Object));
    });
});

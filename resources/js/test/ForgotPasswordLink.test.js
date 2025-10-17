import ForgotPasswordLink from '@/composables/password/ForgotPasswordLink.vue'; // adapte le chemin selon ton projet
import { mount } from '@vue/test-utils';
import { describe, expect, it, vi } from 'vitest';

vi.mock('@inertiajs/vue3', () => ({
    Link: {
        name: 'Link',
        template: '<a><slot /></a>',
        props: ['href'],
    },
}));
vi.mock('ziggy-js', () => ({
    default: (name) => {
        if (name === 'password.request') return '/forgot-password';
        return '/';
    },
}));

describe('ForgotPasswordLink.vue', () => {
    it('affiche le lien vers la page de mot de passe oublié', () => {
        const wrapper = mount(ForgotPasswordLink);
        const link = wrapper.findComponent({ name: 'Link' });

        expect(link.exists()).toBe(true);
        expect(link.props('href')).toBe('/forgot-password');
        expect(link.text()).toContain('mot de passe oublié?');
    });
});

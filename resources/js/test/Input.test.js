import Input from '@/composables/UI/Input.vue';
import { mount } from '@vue/test-utils';
import { describe, expect, it } from 'vitest';

describe('Input.vue', () => {
    it('afficher si le label affiche et fonctionne correctement', async () => {
        const wrapper = mount(Input, {
            props: {
                id: 'username',
                type: 'text',
                label: 'username',
            },
        });
        expect(wrapper.find('label').text()).toBe('username');
        expect(wrapper.find('label').attributes('for')).toBe('username');
    });

    it('verifier si le type est un textarea', async () => {
        const wrapper = mount(Input, {
            props: {
                id: 'bio',
                label: 'biographie',
                type: 'textarea',
                rows: 4,
            },
        });

        expect(wrapper.find('textarea').exists()).toBe(true);
        expect(wrapper.find('textarea').attributes('rows')).toBe('4');
        expect(wrapper.find('input').exists()).toBe(false);
    });

    it('verifier si le type est un input ', async () => {
        const wrapper = mount(Input, {
            props: {
                id: 'username',
                label: 'votre nom',
                type: 'text',
            },
        });

        expect(wrapper.find('input').exists()).toBe(true);
        expect(wrapper.find('textarea').exists()).toBe(false);
    });

    it('émet update:modelValue lors de la saisie', async () => {
        const wrapper = mount(Input, {
            props: {
                id: 'name',
                label: 'Nom',
                type: 'text',
                modelValue: '',
            },
        });

        const input = wrapper.find('input');
        await input.setValue('Jean');

        expect(wrapper.emitted()['update:modelValue']).toBeTruthy();
        expect(wrapper.emitted()['update:modelValue'][0]).toEqual(['Jean']);
    });

    it('affiche un message d’erreur si error est une string', () => {
        const wrapper = mount(Input, {
            props: {
                id: 'name',
                label: 'Nom',
                type: 'text',
                error: 'Ce champ est requis',
            },
        });

        expect(wrapper.text()).toContain('Ce champ est requis');
        expect(wrapper.find('.text-red-500').exists()).toBe(true);
    });

    it('affiche le premier message si error est un tableau', () => {
        const wrapper = mount(Input, {
            props: {
                id: 'name',
                label: 'Nom',
                type: 'text',
                error: ['Ce champ est requis', 'Doit contenir au moins 3 caractères'],
            },
        });

        expect(wrapper.text()).toContain('Ce champ est requis');
        expect(wrapper.text()).not.toContain('Doit contenir au moins 3 caractères');
    });
});

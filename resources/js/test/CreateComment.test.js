import Create from '@/Pages/Comments/Create.vue';
import { useForm } from '@inertiajs/vue3';
import { mount } from '@vue/test-utils';
import { beforeEach, describe, expect, it, vi } from 'vitest';

// 🧩 Mock des dépendances externes
vi.mock('@inertiajs/vue3', async () => {
    const mocking = await vi.importActual('@inertiajs/vue3');
    return {
        ...mocking,
        useForm: vi.fn(),
        Head: {
            name: 'Head',
            template: '<div />',
        },
        Link: {
            name: 'Link',
            template: '<a><slot /></a>',
        },
    };
});

vi.mock('ziggy-js', () => ({
    default: () => '/comment/store',
}));

describe('Create.vue', () => {
    let formMock;

    beforeEach(() => {
        formMock = {
            post: vi.fn(),
            reset: vi.fn(),
            processing: false,
            errors: {},
            content: '',
        };

        useForm.mockReturnValue(formMock);
    });

    const mountComponent = (props = { post: { id: 1 } }) => {
        return mount(Create, {
            props,
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
        expect(wrapper.text()).toContain('commenté le post');
        expect(wrapper.find('button[type="submit"]').exists()).toBe(true);
    });

    it('soumet le formulaire avec les bonnes données', async () => {
        const wrapper = mountComponent({ post: { id: 42 } });

        await wrapper.find('form').trigger('submit.prevent');

        expect(formMock.post).toHaveBeenCalledWith('/comment/store', {
            preserveScroll: true,
            onSuccess: expect.any(Function),
        });

        formMock.post.mock.calls[0][1].onSuccess();
        expect(formMock.reset).toHaveBeenCalled();
    });

    it('désactive le bouton si le formulaire est en cours de traitement', () => {
        formMock.processing = true;
        const wrapper = mountComponent({ post: { id: 99 } });

        const button = wrapper.find('button[type="submit"]');
        expect(button.attributes('disabled')).toBeDefined();
    });
});

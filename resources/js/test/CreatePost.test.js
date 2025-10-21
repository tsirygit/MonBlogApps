import { describe, it, expect, vi, beforeEach } from 'vitest';
import { mount } from '@vue/test-utils';
import Create from '@/Pages/Posts/Create.vue';
import { useForm } from '@inertiajs/vue3';

// 🧩 Mock des dépendances
vi.mock('@inertiajs/vue3', async () => {
  const mocking = await vi.importActual('@inertiajs/vue3');
  return {
    ...mocking,
    useForm: vi.fn(),
    Head: {
      name: 'Head',
      template: '<div />', // évite l'erreur de contexte Inertia
    },
    Link: {
      name: 'Link',
      template: '<a><slot /></a>', // stub simple
    },
  };
});

vi.mock('ziggy-js', () => ({
  default: () => '/post/store',
}));

describe('Create.vue', () => {
  let formMock;

  beforeEach(() => {
    formMock = {
      post: vi.fn(),
      reset: vi.fn(),
      processing: false,
      errors: {},
      title: '',
      content: '',
      image: null,
    };

    useForm.mockReturnValue(formMock);
  });

  const mountComponent = () => {
    return mount(Create, {
      global: {
        stubs: {
          Input: true,
          InputFile: true,
          NavbarLayout: true,
        },
      },
    });
  };

  it('affiche le titre et le bouton', () => {
    const wrapper = mountComponent();
    expect(wrapper.text()).toContain('publié de post');
    expect(wrapper.find('button[type="submit"]').exists()).toBe(true);
  });

  it('soumet le formulaire avec les bonnes données', async () => {
    const wrapper = mountComponent();

    await wrapper.find('form').trigger('submit.prevent');

    expect(formMock.post).toHaveBeenCalledWith('/post/store', {
      preserveScroll: true,
      onSuccess: expect.any(Function),
    });

    formMock.post.mock.calls[0][1].onSuccess();
    expect(formMock.reset).toHaveBeenCalled();
  });

  it('désactive le bouton si le formulaire est en cours de traitement', () => {
    formMock.processing = true;
    const wrapper = mountComponent();

    const button = wrapper.find('button[type="submit"]');
    expect(button.attributes('disabled')).toBeDefined();
  });
});

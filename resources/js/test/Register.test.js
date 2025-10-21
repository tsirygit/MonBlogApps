import { describe, it, expect, vi, beforeEach } from 'vitest';
import { mount } from '@vue/test-utils';
import { reactive } from 'vue';
import Register from '@/Pages/auth/Register.vue';

// Formulaire simulé réactif
const formMock = reactive({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  errors: {},
  processing: false,
  post: vi.fn(),
  reset: vi.fn(),
});

// Mocks des composants enfants
vi.mock('@/composables/UI/Input.vue', () => ({
  default: {
    name: 'Input',
    props: ['modelValue', 'id', 'label', 'type', 'placeholder', 'rows', 'error'],
    template: '<input :id="id" :type="type" />',
  },
}));

vi.mock('@inertiajs/vue3', async () => {
  const actual = await vi.importActual('@inertiajs/vue3');
  return {
    ...actual,
    useForm: () => formMock,
    Head: {
      name: 'Head',
      template: '<div />',
    },
    Link: {
      name: 'Link',
      props: ['href'],
      template: '<a><slot /></a>',
    },
  };
});

vi.mock('ziggy-js', () => ({
  default: (name) => {
    if (name === 'register') return '/register';
    if (name === 'login') return '/login';
    return '/unknown';
  },
}));

describe('Register.vue', () => {
  let wrapper;

  beforeEach(() => {
    formMock.name = '';
    formMock.email = '';
    formMock.password = '';
    formMock.password_confirmation = '';
    formMock.processing = false;
    formMock.post.mockClear();
    formMock.reset.mockClear();

    wrapper = mount(Register);
  });

  it('affiche les champs du formulaire', () => {
    const inputs = wrapper.findAll('input');
    expect(inputs.length).toBe(4);
    expect(inputs[0].attributes('type')).toBe('text');
    expect(inputs[1].attributes('type')).toBe('email');
    expect(inputs[2].attributes('type')).toBe('password');
    expect(inputs[3].attributes('type')).toBe('password');
  });

  it('désactive le bouton de soumission quand form.processing est true', async () => {
    formMock.processing = true;
    await wrapper.vm.$nextTick();
    const button = wrapper.find('button[type="submit"]');
    expect(button.attributes('disabled')).toBeDefined();
  });

  it('appelle form.post avec la bonne route lors de la soumission', async () => {
    await wrapper.find('form').trigger('submit.prevent');
    expect(formMock.post).toHaveBeenCalledWith('/register', expect.any(Object));
  });

  it('affiche le lien vers la page de connexion', () => {
    const link = wrapper.findAllComponents({ name: 'Link' }).find(l => l.text().includes('connectez maintenant'));
    expect(link).toBeDefined();
    expect(link.props('href')).toBe('/login');
  });
});

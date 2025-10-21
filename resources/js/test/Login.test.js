import { describe, it, expect, vi, beforeEach } from 'vitest';
import { mount } from '@vue/test-utils';
import { reactive } from 'vue';
import Login from '@/Pages/auth/Login.vue';

// Référence réactive du formulaire simulé
const formMock = reactive({
  email: '',
  password: '',
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

vi.mock('@/composables/password/ForgotPasswordLink.vue', () => ({
  default: {
    name: 'ForgotPasswordLink',
    template: '<a>Mot de passe oublié ?</a>',
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
    if (name === 'login') return '/login';
    if (name === 'register') return '/register';
    return '/unknown';
  },
}));

describe('Login.vue', () => {
  let wrapper;

  beforeEach(() => {
    formMock.email = '';
    formMock.password = '';
    formMock.processing = false;
    formMock.post.mockClear();
    formMock.reset.mockClear();

    wrapper = mount(Login);
  });

  it('affiche les champs email et mot de passe', () => {
    const inputs = wrapper.findAll('input');
    expect(inputs.length).toBe(2);
    expect(inputs[0].attributes('type')).toBe('email');
    expect(inputs[1].attributes('type')).toBe('password');
  });

  it('désactive le bouton de soumission quand form.processing est true', async () => {
    formMock.processing = true;
    await wrapper.vm.$nextTick();
    const button = wrapper.find('button[type="submit"]');
    expect(button.attributes('disabled')).toBeDefined();
  });

  it('appelle form.post avec la bonne route lors de la soumission', async () => {
    await wrapper.find('form').trigger('submit.prevent');
    expect(formMock.post).toHaveBeenCalledWith('/login', expect.any(Object));
  });

  it('affiche le lien vers la page d’inscription', () => {
    const link = wrapper.findAllComponents({ name: 'Link' }).find(l => l.text().includes('creér un compte'));
    expect(link).toBeDefined();
    expect(link.props('href')).toBe('/register');
  });

  it('affiche le lien mot de passe oublié', () => {
    const forgot = wrapper.findComponent({ name: 'ForgotPasswordLink' });
    expect(forgot.exists()).toBe(true);
    expect(forgot.text()).toContain('Mot de passe oublié');
  });
});

import { describe, it, expect, vi } from 'vitest';
import { mount } from '@vue/test-utils';
import FlashMessage from '@/components/FlashMessage.vue';

// Mock du composable usePage d'Inertia
vi.mock('@inertiajs/vue3', () => ({
  usePage: () => ({
    props: {
      flash: {
        success: 'Opération réussie !',
      },
    },
  }),
}));

describe('FlashMessage.vue', () => {
  it('affiche le message de succès et le cache après 3 secondes', async () => {
    vi.useFakeTimers(); // contrôle du temps

    const wrapper = mount(FlashMessage);

    // Vérifie que le message est affiché
    expect(wrapper.text()).toContain('Opération réussie !');
    expect(wrapper.find('div').exists()).toBe(true);

    // Avance le temps de 3 secondes
    vi.advanceTimersByTime(3000);
    await wrapper.vm.$nextTick();

    // Vérifie que le message est caché
    expect(wrapper.find('div').exists()).toBe(false);

    vi.useRealTimers();
  });
});

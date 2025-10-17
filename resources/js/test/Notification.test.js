import { describe, it, expect, vi, beforeEach } from 'vitest';
import { mount } from '@vue/test-utils';

import Notification  from '@/components/Notification.vue'

describe('Notification.vue', () => {
  let wrapper;

  beforeEach(() => {
    wrapper = mount(Notification);
  });

  it('n\'affiche pas le notification par défaut', () => {
    expect(wrapper.find('div').exists()).toBe(false);
  });

  it('affiche le notification avec un objet comment', async () => {
    const commentData = {
      title: 'Erreur',
      name: 'Serveur',
      content: 'Impossible de charger les données'
    };

    wrapper.vm.toggleNotification(commentData);
    await wrapper.vm.$nextTick();

    expect(wrapper.find('div').exists()).toBe(true);
    expect(wrapper.text()).toContain(commentData.title);
    expect(wrapper.text()).toContain(commentData.name);
    expect(wrapper.text()).toContain(commentData.content);
  });

  it('masque le notification après 5 secondes', async () => {
    vi.useFakeTimers();

    const commentData = {
      title: 'Info',
      name: 'Système',
      content: 'Opération réussie'
    };

    wrapper.vm.toggleNotification(commentData);
    await wrapper.vm.$nextTick();

    expect(wrapper.find('div').exists()).toBe(true);

    vi.advanceTimersByTime(5000);
    await wrapper.vm.$nextTick();

    expect(wrapper.find('div').exists()).toBe(false);

    vi.useRealTimers();
  });
});

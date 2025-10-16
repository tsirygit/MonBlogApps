import { describe, it, expect } from 'vitest';
import { mount } from '@vue/test-utils';
import InputFile from '../composables/UI/InputFile.vue';

describe('InputFile.vue', () => {
  it('émet le fichier sélectionné via update:modelValue', async () => {
    const wrapper = mount(InputFile);

    const file = new File(['dummy content'], 'test.png', { type: 'image/png' });
    const input = wrapper.find('input[type="file"]');

    // Injecte manuellement le fichier dans l’élément DOM
    Object.defineProperty(input.element, 'files', {
      value: [file],
      writable: false,
    });

    // Déclenche l’événement input
    await input.trigger('input');

    // Vérifie que l’événement est bien émis avec le bon fichier
    expect(wrapper.emitted()['update:modelValue']).toBeTruthy();
    expect(wrapper.emitted()['update:modelValue'][0]).toEqual([file]);
  });

  it('affiche le message d’erreur quand la prop error est définie', () => {
    const errorMessage = 'Fichier invalide';
    const wrapper = mount(InputFile, {
      props: {
        error: errorMessage,
      },
    });

    expect(wrapper.text()).toContain(errorMessage);
    expect(wrapper.find('.text-red-500').exists()).toBe(true);
  });
});

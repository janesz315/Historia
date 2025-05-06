import { describe, it, expect } from 'vitest';
import { mount } from '@vue/test-utils';
import UserTestForm from '@/components/Forms/UserTestForm.vue';

describe('UserTestForm', () => {
  it('should render the input field with the correct label', () => {
    const wrapper = mount(UserTestForm, {
      props: {
        itemForm: {} // Adj meg egy üres objektumot vagy egy kezdeti itemForm-ot
      }
    });
    expect(wrapper.find('label[for="testName"]').text()).toBe('Mi legyen a neve?');
    expect(wrapper.find('#testName').exists()).toBe(true);
  });

  // A többi teszteset valószínűleg helyes, ha az itemForm propot átadod
  it('should bind input value to itemForm.testName', async () => {
    const itemForm = { testName: '' };
    const wrapper = mount(UserTestForm, { props: { itemForm } });
    const input = wrapper.find('#testName');
    await input.setValue('Új teszt neve');
    expect(wrapper.vm.itemForm.testName).toBe('Új teszt neve');
  });

  it('should emit saveItem event with itemForm on submit', async () => {
    const itemForm = { testName: 'Teszt neve' };
    const wrapper = mount(UserTestForm, { props: { itemForm } });
    await wrapper.find('form').trigger('submit.prevent');
    expect(wrapper.emitted('saveItem')).toBeTruthy();
    expect(wrapper.emitted('saveItem')[0]).toEqual([itemForm]);
  });
});
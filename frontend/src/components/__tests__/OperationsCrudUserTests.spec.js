import { describe, it, expect, vi } from 'vitest';
import { mount } from '@vue/test-utils';
import OperationsCrudUserTests from '@/components/Modals/OperationsCrudUserTests.vue';

describe('OperationsCrudUserTests', () => {
  it('should render create button when userTest prop is not provided', () => {
    const wrapper = mount(OperationsCrudUserTests);
    expect(wrapper.find('button').text()).toContain('Új teszt készítése');
  });

  it('should emit onClickCreateButton event when create button is clicked', async () => {
    const wrapper = mount(OperationsCrudUserTests);
    const createButton = wrapper.find('button');
    await createButton.trigger('click');
    expect(wrapper.emitted('onClickCreateButton')).toBeTruthy();
  });

  it('should render delete and update buttons when userTest prop is provided', () => {
    const userTest = { id: 1, testName: 'Teszt 1' };
    const wrapper = mount(OperationsCrudUserTests, {
      props: { userTest }
    });
    expect(wrapper.find('.btn-outline-danger').exists()).toBe(true);
    expect(wrapper.find('.btn-outline-primary').exists()).toBe(true);
  });

  it('should emit onClickDeleteButton event with userTest object when delete button is clicked', async () => {
    const userTest = { id: 1, testName: 'Teszt 1' };
    const wrapper = mount(OperationsCrudUserTests, {
      props: { userTest }
    });
    const deleteButton = wrapper.find('.btn-outline-danger');
    await deleteButton.trigger('click');
    expect(wrapper.emitted('onClickDeleteButton')).toBeTruthy();
    expect(wrapper.emitted('onClickDeleteButton')[0]).toEqual([userTest]);
  });

  it('should emit onClickUpdateButton event with userTest object when update button is clicked', async () => {
    const userTest = { id: 1, testName: 'Teszt 1' };
    const wrapper = mount(OperationsCrudUserTests, {
      props: { userTest }
    });
    const updateButton = wrapper.find('.btn-outline-primary');
    await updateButton.trigger('click');
    expect(wrapper.emitted('onClickUpdateButton')).toBeTruthy();
    expect(wrapper.emitted('onClickUpdateButton')[0]).toEqual([userTest]);
  });
});
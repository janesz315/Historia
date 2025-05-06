import { describe, it, expect, vi } from 'vitest';
import { mount } from '@vue/test-utils';
import Modal from '@/components/Modals/Modal.vue';

describe('Modal', () => {
  it('should render title correctly', () => {
    const title = 'Modal Title';
    const wrapper = mount(Modal, {
      props: { title }
    });
    expect(wrapper.find('.modal-title').text()).toBe(title);
  });

  it('should render yes button and emit yesEvent on click', async () => {
    const yesText = 'Yes';
    const wrapper = mount(Modal, {
      props: { yes: yesText }
    });
    const yesButton = wrapper.find('.btn-primary');
    expect(yesButton.text()).toBe(yesText);
    await yesButton.trigger('click');
    expect(wrapper.emitted('yesEvent')).toBeTruthy();
  });

  it('should render no button when no prop is provided', () => {
    const noText = 'No';
    const wrapper = mount(Modal, {
      props: { no: noText }
    });
    expect(wrapper.find('.btn-secondary').text()).toBe(noText);
  });

  it('should apply size class based on size prop', () => {
    const size = 'lg';
    const wrapper = mount(Modal, {
      props: { size }
    });
    expect(wrapper.find('.modal-dialog').classes()).toContain(`modal-${size}`);
  });

  it('should render slot content', () => {
    const slotContent = '<p>Slot Content</p>';
    const wrapper = mount(Modal, {
      slots: {
        default: slotContent
      }
    });
    expect(wrapper.find('.modal-body').html()).toContain(slotContent);
  });
});
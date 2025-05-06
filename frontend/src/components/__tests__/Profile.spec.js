import { describe, it, expect, vi } from 'vitest';
import { mount } from '@vue/test-utils';
import Profile from '@/components/Auth/Profile.vue';
import { useAuthStore } from '@/stores/useAuthStore';
import { BASE_URL } from "@/helpers/baseUrls.js";
import axios from 'axios';
import { createRouter, createWebHistory } from 'vue-router';

vi.mock('axios');
vi.mock('@/stores/useAuthStore', () => ({
  useAuthStore: vi.fn(() => ({
    id: 1,
    token: 'test-token',
    clearStoredData: vi.fn(),
  })),
}));

// Mock router
const router = createRouter({
  history: createWebHistory(),
  routes: [],
});

describe('Profile', () => {
  it('should initialize with an empty user object', () => {
    const wrapper = mount(Profile);
    expect(wrapper.vm.user).toEqual({});
  });

  it('should fetch user data on created hook and update user object', async () => {
    const mockUser = { row: { id: 1, name: 'Test User', email: 'test@example.com' } };
    axios.get.mockResolvedValue({ data: mockUser });

    const wrapper = mount(Profile);
    await wrapper.vm.$nextTick(); // Várjuk meg az aszinkron hívás befejezését

    expect(axios.get).toHaveBeenCalledWith(`${BASE_URL}/users/1`, {
      headers: { Authorization: 'Bearer test-token' },
    });
    expect(wrapper.vm.user).toEqual(mockUser.row);
  });

  it('should log error if fetching user data fails', async () => {
    const mockError = new Error('Failed to fetch user');
    axios.get.mockRejectedValue(mockError);
    const consoleErrorSpy = vi.spyOn(console, 'error');

    mount(Profile);
    await new Promise(resolve => setTimeout(resolve, 0)); // Kis várakozás az aszinkron hiba kezelésére

    expect(consoleErrorSpy).toHaveBeenCalledWith('Error fetching user profile:', mockError);
    consoleErrorSpy.mockRestore();
  });

  it('should start editing a field when "Módosítás" button is clicked', async () => {
    const mockUser = { row: { id: 1, name: 'Test User', email: 'test@example.com' } };
    axios.get.mockResolvedValue({ data: mockUser });
    const wrapper = mount(Profile, {
      global: {
        plugins: [router],
      },
    });
    await wrapper.vm.$nextTick();

    const editButton = wrapper.findAll('button').find(button => button.text().includes('Módosítás'));
    if (editButton) {
      await editButton.trigger('click');
      expect(wrapper.vm.isEditingField).toBe('name');
      expect(wrapper.vm.updatedField.name).toBe('Test User');
      expect(wrapper.find('input[type="text"]').exists()).toBe(true);
      expect(wrapper.findAll('button').find(button => button.text().includes('Mentés'))).toBeTruthy();
      expect(wrapper.findAll('button').find(button => button.text().includes('Mégse'))).toBeTruthy();
      const userParagraph = wrapper.find('.card-body p');
      expect(userParagraph.text()).not.toContain('Test User');
    } else {
      expect(false).toBe(true); // Ha nem találjuk a gombot, a teszt fail-el
    }
  });

  it('should cancel editing and reset state when "Mégse" button is clicked', async () => {
    const mockUser = { row: { id: 1, name: 'Test User', email: 'test@example.com' } };
    axios.get.mockResolvedValue({ data: mockUser });
    const wrapper = mount(Profile, {
      global: {
        plugins: [router],
      },
    });
    await wrapper.vm.$nextTick();

    const editButton = wrapper.findAll('button').find(button => button.text().includes('Módosítás'));
    if (editButton) {
      await editButton.trigger('click');
      const cancelButton = wrapper.findAll('button').find(button => button.text().includes('Mégse'));
      if (cancelButton) {
        await cancelButton.trigger('click');
        expect(wrapper.vm.isEditingField).toBeNull();
        expect(wrapper.vm.updatedField).toEqual({});
        expect(wrapper.find('input[type="text"]').exists()).toBe(false);
        expect(wrapper.findAll('button').find(button => button.text().includes('Mentés'))).toBeFalsy();
        expect(wrapper.findAll('button').find(button => button.text().includes('Mégse'))).toBeFalsy();
        expect(wrapper.findAll('button').find(button => button.text().includes('Módosítás'))).toBeTruthy();
        const userParagraph = wrapper.find('.card-body p');
        expect(userParagraph.text()).toContain('Test User');
      } else {
        expect(false).toBe(true); // Ha nem találjuk a "Mégse" gombot
      }
    } else {
      expect(false).toBe(true); // Ha nem találjuk a "Módosítás" gombot
    }
  });
});
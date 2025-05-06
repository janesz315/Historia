import { describe, it, expect, vi } from 'vitest';
import { mount } from '@vue/test-utils';
import Login from '@/components/Auth/Login.vue';
import { useAuthStore } from '@/stores/useAuthStore';
import axios from 'axios';
import { createRouter, createWebHistory } from 'vue-router';
import { BASE_URL } from "@/helpers/baseUrls.js";

const mockRouter = {
    push: vi.fn(),
  };
vi.mock('axios');
vi.mock('@/stores/useAuthStore', () => ({
  useAuthStore: vi.fn(() => ({
    setId: vi.fn(),
    setUser: vi.fn(),
    setToken: vi.fn(),
    setRoleId: vi.fn(),
  })),
}));
vi.mock('vue-router');

const router = createRouter({
  history: createWebHistory(),
  routes: [],
});

const wrapper = mount(Login, {
    global: {
      plugins: [mockRouter],
    },
  });

describe('Login', () => {
  it('should render the component', () => {
    const wrapper = mount(Login, {
      global: {
        plugins: [router],
      },
    });
    expect(wrapper.exists()).toBe(true);
    expect(wrapper.find('.login-title').text()).toBe('Bejelentkezés');
  });

  it('should initialize with empty email and password', () => {
    const wrapper = mount(Login, {
      global: {
        plugins: [router],
      },
    });
    expect(wrapper.vm.user.email).toBe('');
    expect(wrapper.vm.user.password).toBe('');
    expect(wrapper.vm.errorMessage).toBeNull();
    expect(wrapper.vm.loading).toBe(false);
  });

  it('should display error message if email or password is empty on submit', async () => {
    const wrapper = mount(Login, {
      global: {
        plugins: [router],
      },
    });
    await wrapper.find('form').trigger('submit.prevent');
    expect(wrapper.vm.errorMessage).toBe('Kérlek, add meg az email címed és a jelszavad!');
    expect(wrapper.vm.loading).toBe(false);
    expect(axios.post).not.toHaveBeenCalled();
  });

  it('should display "Helytelen bejelentkezési adatok!" message for specific API error', async () => {
    const mockResponse = {
      data: null, // Vagy egy olyan struktúra, ami nem tartalmaz user adatot
    };
    axios.post.mockResolvedValue(mockResponse);
    const wrapper = mount(Login, {
      global: {
        plugins: [router],
      },
    });
    await wrapper.find('#email').setValue('test@example.com');
    await wrapper.find('#password').setValue('wrong-password');
    await wrapper.find('form').trigger('submit.prevent');

    await wrapper.vm.$nextTick();
    expect(wrapper.vm.errorMessage).toBe('Helytelen bejelentkezési adatok!');
    expect(wrapper.vm.loading).toBe(false);
  });

  // Itt lehet tesztelni a mounted hook-ot, bár a DOM manipulációt érdemesebb integrációs tesztekben (pl. Playwright) ellenőrizni.
  // Ha a logika jelentős, akkor mockolhatod a window objektumot.
});
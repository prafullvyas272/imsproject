import { defineStore } from 'pinia';

export const useAuthStore = defineStore('AuthStore', {
  state: () => ({
    user: null,
    role: null,
    isDarkThemeEnabled: null,
  }),
  actions: {
    setUser(user) {
      this.user = user;
      this.role = user?.role_id ?? null;
    },
    clearUser() {
      this.user = null;
      this.role = null;
    },
    toggleDarkTheme(value) {
        this.isDarkThemeEnabled = value;
    }
  },
});

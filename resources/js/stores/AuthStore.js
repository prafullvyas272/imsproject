import { defineStore } from 'pinia';

export const useAuthStore = defineStore('AuthStore', {
  state: () => ({
    user: null,
    role: null,
    formTheme: null,
    bgTheme: null,
    isDarkThemeEnabled: null,
  }),
  actions: {
    setUser(user) {
      this.user = user;
      this.role = user?.role_id ?? null;
      this.bgTheme = user?.role?.bg_theme ?? null;
      this.formTheme = user?.role?.form_theme ?? null;
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

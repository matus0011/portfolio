// @ts-check
import { defineConfig } from 'astro/config';
import svelte from '@astrojs/svelte';
import tailwindcss from '@tailwindcss/vite';
import glsl from 'vite-plugin-glsl';

// https://astro.build/config
export default defineConfig({
  integrations: [svelte()],
  i18n: {
    defaultLocale: 'pl',
    locales: ['pl', 'en'],
  },
  devToolbar: {
    enabled: false,
  },
  vite: {
    plugins: [tailwindcss(), glsl()],
  },
});

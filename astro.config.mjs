// @ts-check
import { defineConfig } from 'astro/config';
import svelte from '@astrojs/svelte';
import icon from 'astro-icon';
import tailwindcss from '@tailwindcss/vite';

import partytown from '@astrojs/partytown';

// https://astro.build/config
export default defineConfig({
  integrations: [
    svelte(),
    icon(),
    partytown({
      config: {
        forward: ["dataLayer.push"],
      },
    }),
  ],
  i18n: {
    defaultLocale: 'pl',
    locales: ['pl', 'en'],
  },
  devToolbar: {
    enabled: false,
  },
  vite: {
    plugins: [tailwindcss()],
  },
});
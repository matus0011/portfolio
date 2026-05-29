export const technologies = [
  "VUE",
  "NUXT",
  "REACT",
  "REACT NATIVE",
  "EXPO",
  "SVELTE",
  "ASTRO",
  "NODE.JS",
  "TYPESCRIPT",
  "JAVASCRIPT",
  "HTML5",
  "CSS3",
  "SCSS",
  "TAILWIND",
  "VITE",
  "MOTION",
  "PINIA",
  "REST API",
  "WEBSOCKETS",
  "GIT",
  "FIGMA",
];

export const skills = [
  "SSR",
  "SPA",
  "PWA",
  "SEO",
  "RWD",
  "FULLSTACK",
  "ARCHITECTURE",
  "END-TO-END",
  "REFACTORING",
  "OPTIMIZATION",
  "CODE REVIEW",
  "DESIGN SYSTEMS",
  "WEB ANIMATIONS",
  "AI ENGINEERING",
  "LLM APPS",
  "CHATBOTS",
  "ADMIN PANELS",
  "THREE.JS",
  "GSAP",
  "GLSL",
  "WEBGL",
];

export type TechStackItem = {
  name: string;
  descPl: string;
  descEn: string;
  ascii: string;
};

const ascii = (lines: string[]) => lines.join("\n");

export const techStack: TechStackItem[] = [
  {
    name: "Vue & Nuxt",
    descPl:
      "Komponentowe aplikacje SPA i SSR z Vue 3 oraz Nuxt — od prostych widoków po rozbudowane panele i sklepy.",
    descEn:
      "Component-based SPA and SSR apps with Vue 3 and Nuxt — from simple views to complex dashboards and storefronts.",
    ascii: ascii([
      "                    ",
      "    +----------+    ",
      "    | VUE  2/3 |    ",
      "    |    +     |    ",
      "    |   NUXT   |    ",
      "    | 2/3/4/5  |    ",
      "    +----------+    ",
      "  SSR / SSG / SPA   ",
    ]),
  },
  {
    name: "React & React Native",
    descPl:
      "Webowe i mobilne aplikacje w React oraz React Native / Expo — wspólny język po stronie web i mobile.",
    descEn:
      "Web and mobile apps in React and React Native / Expo — one shared language across web and mobile.",
    ascii: ascii([
      "                    ",
      "    +----------+    ",
      "    | REACT 19+|    ",
      "    |    +     |    ",
      "    |  RN +    |    ",
      "    |  EXPO    |    ",
      "    +----------+    ",
      "    WEB / MOBILE    ",
    ]),
  },
  {
    name: "Svelte & Astro",
    descPl:
      "Lekkie, szybkie strony i landing page'e w Svelte 5 oraz Astro, z naciskiem na wydajność i czysty HTML.",
    descEn:
      "Lightweight, fast sites and landing pages in Svelte 5 and Astro, with focus on performance and clean HTML.",
    ascii: ascii([
      "                    ",
      "    +----------+    ",
      "    | SVELTE 5 |    ",
      "    |    +     |    ",
      "    |  ASTRO   |    ",
      "    +----------+    ",
      "    RUNES / SSG     ",
      "                    ",
    ]),
  },
  {
    name: "Node.js & TypeScript",
    descPl:
      "Backend API, integracje i narzędzia w Node.js z TypeScriptem — od REST i WebSockets po długoterminowe utrzymanie.",
    descEn:
      "Backend APIs, integrations and tooling in Node.js with TypeScript — from REST and WebSockets to long-term maintenance.",
    ascii: ascii([
      "                    ",
      "    +----------+    ",
      "    | NODE.JS  |    ",
      "    |  + TS    |    ",
      "    |  + HONO  |    ",
      "    +----------+    ",
      "  REST / WS / API   ",
      "                    ",
    ]),
  },
  {
    name: "Animations & 3D",
    descPl:
      "Animacje webowe z GSAP, ScrollTriggerem oraz grafika 3D w Three.js, WebGL i GLSL — interaktywne doświadczenia.",
    descEn:
      "Web animations with GSAP, ScrollTrigger, plus 3D graphics in Three.js, WebGL and GLSL — interactive experiences.",
    ascii: ascii([
      "                    ",
      "    +----------+    ",
      "    |  GSAP +  |    ",
      "    |  THREE   |    ",
      "    |  WEBGL   |    ",
      "    +----------+    ",
      "   SCROLLTRIGGER    ",
      "                    ",
    ]),
  },
  {
    name: "AI & LLM",
    descPl:
      "Integracje LLM, chatboty, narzędzia AI oraz przepływy oparte o modele językowe wpięte w realne produkty.",
    descEn:
      "LLM integrations, chatbots, AI tooling and language-model workflows wired into real-world products.",
    ascii: ascii([
      "                    ",
      "    +----------+    ",
      "    |    AI    |    ",
      "    |    +     |    ",
      "    |   LLMs   |    ",
      "    +----------+    ",
      "  CHATBOTS / RAG    ",
      "                    ",
    ]),
  },
];

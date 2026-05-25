export const pl = {
  nav: {
    home: "Home",
    projects: "Projekty",
    about: "O mnie",
    contact: "Kontakt",
  },
  labels: {
    linkedin: "LinkedIn",
    close: "Zamknij menu",
    scroll: "Przewiń",
  },
  hero: {
    prefix: "Web",
    roles: ["Creative", "Full Stack", "Frontend", "Mobile"] as const,
    roleLabel: "Developer",
    availableLabel: "Dostępny",
    statuses: ["Pełny etat", "Freelance", "Zdalnie", "Kontrakt"] as const,
    location: "Polska",
    tagline: ["BUILDING", "DIGITAL", "EXPERIENCES"] as const,
  },
  about: {
    label: "ABOUT",
    paragraph:
      "Frontend / Fullstack Developer z 7-letnim doświadczeniem komercyjnym. Specjalizuję się w nowoczesnych technologiach JavaScript (Vue, Nuxt, React, React Native) oraz pracy fullstackowej z Node.js i Laravel. Buduję projekty end-to-end — od analizy wymagań i architektury, przez implementację, aż po długoterminowe utrzymanie i rozwój.",
    stats: [
      { value: "7+", label: "lat doświadczenia komercyjnego" },
      { value: "6", label: "stacków: Vue · Nuxt · React · RN · Node · Laravel" },
      { value: "AI", label: "integracje: chatboty, search, panele" },
      { value: "✓", label: "dostępny freelance" },
    ] as const,
  },
} as const;

export type Translations = typeof pl;

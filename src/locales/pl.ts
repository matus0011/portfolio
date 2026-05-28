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
    tagline: ["THINK_CODE", "DESIGN"] as const,
  },
  aboutLines: [
    "FRONTEND / FULLSTACK",
    "DEVELOPER Z 7-LETNIM",
    "DOŚWIADCZENIEM W BUDOWIE",
    "APLIKACJI WEB, MOBILE, SSR",
    "SSG, SPA ORAZ BACKEND",
    "OD ANALIZY PO UTRZYMANIE",
  ] as const,
  about: {
    label: "O mnie",
    heading: ["Pasja do", "budowania", "rzeczy."],
    bio: "Frontend / Fullstack Developer z 7-letnim doświadczeniem komercyjnym. Specjalizuję się w Vue, Nuxt, React i React Native, a po stronie backendu w Node.js i Laravel. Pracuję end-to-end — od analizy wymagań i architektury, przez implementację i integracje z API, po długoterminowe utrzymanie aplikacji produkcyjnych.",
    stats: [
      { value: "4+", label: "lata doświadczenia" },
      { value: "30+", label: "projektów" },
      { value: "10+", label: "technologii" },
    ] as const,
    cta: "Skontaktuj się",
  },
} as const;

export type Translations = typeof pl;

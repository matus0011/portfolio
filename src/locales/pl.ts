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
    tagline: ["CODE &", "DESIGN"] as const,
  },
  about: {
    label: "O mnie",
    heading: ["Pasja do", "budowania", "rzeczy."],
    bio: "Jestem web developerem z kilkuletnim doświadczeniem w tworzeniu nowoczesnych aplikacji. Specjalizuję się w Frontend oraz Full Stack — łączę dbałość o szczegóły wizualne z solidnym kodem po stronie serwera.",
    stats: [
      { value: "4+", label: "lata doświadczenia" },
      { value: "30+", label: "projektów" },
      { value: "10+", label: "technologii" },
    ] as const,
    cta: "Skontaktuj się",
  },
} as const;

export type Translations = typeof pl;

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
    tagline: ["TWORZĘ", "CYFROWE", "DOŚWIADCZENIA"] as const,
  },
} as const;

export type Translations = typeof pl;

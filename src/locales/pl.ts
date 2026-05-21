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
    availableLabel: "Available",
    statuses: ["Full Time", "Freelance", "Remote", "Contract"] as const,
    location: "Poland",
  },
} as const;

export type Translations = typeof pl;

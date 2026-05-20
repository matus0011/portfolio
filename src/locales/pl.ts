export const pl = {
  nav: {
    projects: "Projekty",
    about: "O mnie",
    contact: "Kontakt",
  },
  meta: {
    collection: "First Collection",
    date: "19.8.19",
    studio: "X/Labs",
    description1:
      "For automotation of x/labs unisex, summer collection and protection. Deep unisex.",
    description2:
      "For automotation of x/labs unisex, sp/summer collection a.",
  },
  labels: {
    linkedin: "LinkedIn",
    close: "Zamknij menu",
    scroll: "Przewiń",
    previous: "Poprzedni",
    next: "Następny",
  },
} as const;

export type Translations = typeof pl;

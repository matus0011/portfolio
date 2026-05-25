export const en = {
  nav: {
    home: "Home",
    projects: "Projects",
    about: "About me",
    contact: "Contact",
  },
  labels: {
    linkedin: "LinkedIn",
    close: "Close menu",
    scroll: "Scroll",
  },
  hero: {
    prefix: "Web",
    roles: ["Creative", "Full Stack", "Frontend", "Mobile"] as const,
    roleLabel: "Developer",
    availableLabel: "Available",
    statuses: ["Full Time", "Freelance", "Remote", "Contract"] as const,
    location: "Poland",
    tagline: ["BUILDING", "DIGITAL", "EXPERIENCES"] as const,
  },
  about: {
    label: "ABOUT",
    paragraph:
      "Frontend / Fullstack Developer with 7 years of commercial experience. Specialized in modern JavaScript frameworks (Vue, Nuxt, React, React Native) and fullstack work with Node.js and Laravel. I build projects end-to-end — from requirements analysis and architecture, through implementation, to long-term maintenance and development.",
    stats: [
      { value: "7+", label: "years of commercial experience" },
      { value: "6", label: "stacks: Vue · Nuxt · React · RN · Node · Laravel" },
      { value: "AI", label: "integrations: chatbots, search, panels" },
      { value: "✓", label: "available for freelance" },
    ] as const,
  },
} as const;

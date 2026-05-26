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
    tagline: ["THINK_CODE", "DESIGN"] as const,
  },
  aboutLines: [
    "FRONTEND AND FULLSTACK",
    "DEVELOPER WITH 7 YEARS",
    "OF COMMERCIAL EXPERIENCE",
    "BUILDING WEB AND SSR APPS",
    "END-TO-END IN PRODUCTION",
  ] as const,
  about: {
    label: "About me",
    heading: ["Passion for", "building", "things."],
    bio: "Frontend / Fullstack Developer with 7 years of commercial experience. I specialise in Vue, Nuxt, React and React Native, with Node.js and Laravel on the backend. I work end-to-end — from requirements analysis and architecture, through implementation and API integrations, to long-term maintenance of production applications.",
    stats: [
      { value: "4+", label: "years of experience" },
      { value: "30+", label: "projects" },
      { value: "10+", label: "technologies" },
    ] as const,
    cta: "Get in touch",
  },
} as const;

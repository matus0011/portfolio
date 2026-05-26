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
    label: "About me",
    heading: ["Passion for", "building", "things."],
    bio: "I'm a web developer with several years of experience building modern applications. I specialise in Frontend and Full Stack — combining attention to visual detail with solid server-side code.",
    stats: [
      { value: "4+", label: "years of experience" },
      { value: "30+", label: "projects" },
      { value: "10+", label: "technologies" },
    ] as const,
    cta: "Get in touch",
  },
} as const;

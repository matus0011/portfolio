import type { Translations } from "./index";

export const en = {
  nav: {
    home: "Home",
    about: "About me",
    tech: "Tech stack",
    contact: "Contact",
  },
  labels: {
    linkedin: "LinkedIn",
    close: "Close menu",
    scroll: "Scroll",
    techStack: "TECH STACK",
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
    "FRONTEND / FULLSTACK",
    "DEVELOPER WITH 7 YEARS",
    "OF COMMERCIAL EXPERIENCE",
    "BUILDING WEB, MOBILE, SSR",
    "SSG, SPA AND BACKEND APPS",
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
  footer: {
    name: "MATEUSZ",
    phone: "+48 600 123 456",
    email: "hello@example.com",
    addressLabel: "Address",
    address: ["12 Example Street", "00-001 Warsaw, Poland"],
    coords: "52.2297 / 21.0122",
    role: "Frontend / Fullstack Developer",
    rights: "All rights reserved.",
  },
  experience: {
    heading: "Experience",
    columns: { company: "Company", role: "Role", year: "Year" },
    rows: [
      { company: "Creative Studio", role: "Senior Frontend Developer", year: "2023" },
      { company: "Interactive Agency", role: "Frontend Developer", year: "2021" },
      { company: "Freelance", role: "Fullstack Developer", year: "2020" },
      { company: "Software House", role: "Web Developer", year: "2019" },
      { company: "SaaS Startup", role: "Junior Developer", year: "2018" },
      { company: "First Company", role: "Intern", year: "2017" },
    ],
  },
  cta: {
    desc: "Digital architectures for an ever-shifting world.",
    button: "Let's talk",
  },
} as const satisfies Translations;

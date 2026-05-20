import { pl } from "./pl";
import { en } from "./en";

export const locales = { pl, en } as const;

export type Lang = keyof typeof locales;
export type Translations = typeof pl;

export function t(lang: Lang): Translations {
  return locales[lang];
}

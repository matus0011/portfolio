import { pl } from "./pl";
import { en } from "./en";

export const locales = { pl, en } as const;

export type Lang = keyof typeof locales;

// Widened shape of the `pl` source so other locales can be checked
// structurally (same keys/shape) without forcing identical string literals.
type DeepWiden<T> = T extends readonly (infer U)[]
  ? readonly DeepWiden<U>[]
  : T extends string
    ? string
    : T extends number
      ? number
      : T extends boolean
        ? boolean
        : { [K in keyof T]: DeepWiden<T[K]> };

export type Translations = DeepWiden<typeof pl>;

export function t(lang: Lang): Translations {
  return locales[lang];
}

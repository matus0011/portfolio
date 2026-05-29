import type { ScrollSmoother } from "gsap/ScrollSmoother";

declare global {
  interface Window {
    smoother?: ScrollSmoother;
    loaderDone?: boolean;
  }
}

export {};

<script lang="ts">
  import { onMount } from "svelte";
  import { animate } from "motion";
  import { gsap } from "gsap";
  import { ScrollTrigger } from "gsap/ScrollTrigger";
  import MagneticDots from "./MagneticDots.svelte";
  import HeroTitle from "./HeroTitle.svelte";
  import MouseInfo from "./MouseInfo.svelte";
  import { t, type Lang } from "../locales";
  import { scrambleTo, letterRise, letterDrop, DIGIT_CHARS } from "../utils/scramble";
  import { ui } from "../state/ui.svelte";

  let heroSectionEl: HTMLElement;

  let { initialLang = "pl" as Lang } = $props();

  const lang = $derived(initialLang as Lang);
  const tr = $derived(t(lang));

  const navRight = $derived([
    { label: tr.nav.projects, href: "#" },
    { label: tr.nav.about, href: "#" },
    { label: tr.nav.contact, href: "#" },
  ]);

  const navGens = [{ v: 0 }, { v: 0 }, { v: 0 }];
  const linkedinGen = { v: 0 };
  let linkedinTextEl: HTMLSpanElement;
  let navTextEls: HTMLSpanElement[] = [];

  function getTime() {
    return new Date().toLocaleTimeString("pl-PL", {
      timeZone: "Europe/Warsaw",
      hour: "2-digit",
      minute: "2-digit",
    });
  }
  function getTimezone() {
    return Intl.DateTimeFormat("en", { timeZone: "Europe/Warsaw", timeZoneName: "short" })
      .formatToParts(new Date())
      .find((p) => p.type === "timeZoneName")?.value ?? "CET";
  }
  let timeEl: HTMLSpanElement;
  const tGen = { v: 0 };
  let timezone = $state(getTimezone());

  let overlayTitleLine1El: HTMLSpanElement;
  let overlayTitleLine2El: HTMLSpanElement;
  let overlayTitleLine3El: HTMLSpanElement;
  const overlayTitleLine1Gen = { v: 0 };
  const overlayTitleLine2Gen = { v: 0 };
  const overlayTitleLine3Gen = { v: 0 };

  function initIntro() {
    const elements = document.querySelectorAll(
      ".hero-nav, .hero-left, .hero-mouse-info, .hero-arrow",
    );
    elements.forEach((el) => el.classList.remove("invisible"));

    animate([
      [
        ".hero-nav",
        { y: [-20, 0], opacity: [0, 1] },
        { duration: 1.2, ease: "easeOut" },
      ],
      [
        ".hero-left",
        { y: [25, 0], opacity: [0, 1] },
        { duration: 1.2, ease: "easeOut", at: "-1.0" },
      ],
      [
        ".hero-mouse-info",
        { y: [25, 0], opacity: [0, 1] },
        { duration: 1.2, ease: "easeOut", at: "-0.85" },
      ],
      [
        ".hero-arrow",
        { y: [40, 0], opacity: [0, 1] },
        { duration: 0.8, ease: "easeOut", at: "-0.8" },
      ],
    ]);

    if (overlayTitleLine1El) {
      letterRise(overlayTitleLine1El, tr.hero.tagline[0], overlayTitleLine1Gen);
    }
    if (overlayTitleLine2El) {
      letterRise(overlayTitleLine2El, tr.hero.tagline[1], overlayTitleLine2Gen);
    }
    if (overlayTitleLine3El) {
      letterRise(overlayTitleLine3El, tr.hero.tagline[2], overlayTitleLine3Gen);
    }

  }

  let menuWasOpen = false;

  $effect(() => {
    const overlay = document.querySelector(".hero-title-overlay") as HTMLElement | null;
    if (!overlay) return;

    if (ui.menuOpen) {
      menuWasOpen = true;
      overlay.style.transition = "opacity 0.4s ease-out, scale 0.4s ease-out";
      overlay.style.opacity = "0";
      overlay.style.scale = "0.96";
    } else {
      overlay.style.transition = "opacity 0.5s ease-out, scale 0.5s ease-out";
      overlay.style.opacity = "1";
      overlay.style.scale = "1";
      if (menuWasOpen && (window as any).loaderDone) {
        if (overlayTitleLine1El) letterRise(overlayTitleLine1El, tr.hero.tagline[0], overlayTitleLine1Gen);
        if (overlayTitleLine2El) letterRise(overlayTitleLine2El, tr.hero.tagline[1], overlayTitleLine2Gen);
        if (overlayTitleLine3El) letterRise(overlayTitleLine3El, tr.hero.tagline[2], overlayTitleLine3Gen);
      }
      window.setTimeout(() => {
        overlay.style.transition = "";
        overlay.style.opacity = "";
        overlay.style.scale = "";
        ScrollTrigger.refresh();
      }, 550);
    }
  });

  onMount(() => {
    if ((window as any).loaderDone) {
      initIntro();
    } else {
      window.addEventListener("loaderFinished", initIntro, { once: true });
    }

    let lastTime = getTime();
    if (timeEl) scrambleTo(timeEl, lastTime, tGen, DIGIT_CHARS, ": ");
    const clockInterval = setInterval(() => {
      const now = getTime();
      if (now !== lastTime) {
        lastTime = now;
        if (timeEl) scrambleTo(timeEl, now, tGen, DIGIT_CHARS, ": ");
      }
    }, 1000);

    gsap.registerPlugin(ScrollTrigger);

    const ctx = gsap.context(() => {
      let scrambledOut = false;
      const SCRAMBLE_THRESHOLD = 0.7;
      const SCRAMBLE_BACK_THRESHOLD = 0.65;

      ScrollTrigger.create({
        trigger: heroSectionEl,
        start: "top top",
        end: "bottom top",
        scrub: true,
        onUpdate: (self) => {
          ui.heroScrollProgress = self.progress;

          if (self.progress >= SCRAMBLE_THRESHOLD && !scrambledOut) {
            scrambledOut = true;
            if (overlayTitleLine1El)
              letterDrop(overlayTitleLine1El, overlayTitleLine1Gen);
            if (overlayTitleLine2El)
              letterDrop(overlayTitleLine2El, overlayTitleLine2Gen);
            if (overlayTitleLine3El)
              letterDrop(overlayTitleLine3El, overlayTitleLine3Gen);
          } else if (self.progress <= SCRAMBLE_BACK_THRESHOLD && scrambledOut) {
            scrambledOut = false;
            if (overlayTitleLine1El)
              letterRise(overlayTitleLine1El, tr.hero.tagline[0], overlayTitleLine1Gen);
            if (overlayTitleLine2El)
              letterRise(overlayTitleLine2El, tr.hero.tagline[1], overlayTitleLine2Gen);
            if (overlayTitleLine3El)
              letterRise(overlayTitleLine3El, tr.hero.tagline[2], overlayTitleLine3Gen);
          }
        },
      });
    }, heroSectionEl);

    return () => {
      clearInterval(clockInterval);
      window.removeEventListener("loaderFinished", initIntro);
      ctx.revert();
    };
  });
</script>

<section
  bind:this={heroSectionEl}
  class="relative h-screen w-full overflow-hidden px-8 md:px-12 py-6 md:py-8 flex flex-col"
>
  <!-- TOP NAV -->
  <nav class="hero-nav opacity-0 invisible flex items-center justify-between">
    <HeroTitle {lang} />

    <ul class="flex items-center gap-10">
      {#each navRight as item, i (item.label)}
        <li>
          <a
            href={item.href}
            class="label hover:text-accent transition-colors inline-flex items-center gap-1.5"
            onmouseenter={() =>
              scrambleTo(
                navTextEls[i],
                item.label,
                navGens[i],
                undefined,
                undefined,
                2,
              )}
          >
            <span bind:this={navTextEls[i]}>{item.label}</span>
          </a>
        </li>
      {/each}
      <li>
        <MagneticDots onclick={() => (ui.menuOpen = true)} />
      </li>
    </ul>
  </nav>

  <!-- MAIN GRID -->
  <div class="flex-1 grid grid-cols-12 gap-6 mt-2">
    <!-- LEFT COLUMN -->
    <div
      class="hero-left opacity-0 invisible col-span-3 relative flex flex-col justify-center z-10"
    >
      <!-- LinkedIn link + location -->
      <div class="absolute bottom-0 left-0 flex flex-col gap-1">
      <a
        href="#"
        class="label inline-flex items-center gap-1.5 hover:text-accent transition-colors"
        onmouseenter={() =>
          scrambleTo(
            linkedinTextEl,
            tr.labels.linkedin,
            linkedinGen,
            undefined,
            undefined,
            2,
          )}
      >
        <span bind:this={linkedinTextEl}>{tr.labels.linkedin}</span>
        <svg
          class="w-3.5 h-3.5"
          viewBox="0 0 24 24"
          fill="none"
          stroke="currentColor"
          stroke-width="2"
          stroke-linecap="round"
          stroke-linejoin="round"
          aria-hidden="true"
        >
          <path d="M7 7h10v10" />
          <path d="m7 17 10-10" />
        </svg>
      </a>
      <div class="label flex items-center gap-1.5">
        <span>{tr.hero.location}</span>
        <span class="opacity-40">·</span>
        <span bind:this={timeEl}></span>
        <span>{timezone}</span>
      </div>
      <MouseInfo />
      </div>
    </div>

    <!-- CENTER SPACE HOLDER -->
    <div class="col-span-9 pointer-events-none"></div>
  </div>

  <!-- Center Titles Overlay -->
  <div
    class="hero-title-overlay absolute inset-0 flex flex-col items-center justify-center pointer-events-none z-10 text-center select-none"
  >
    <h1
      class="text-[16vw] md:text-[14vw] lg:text-[12vw] font-black uppercase tracking-wider text-ink select-none flex flex-col items-center justify-center gap-0 leading-[0.92]"
      style="font-family: var(--font-display)"
    >
      <span bind:this={overlayTitleLine1El}></span>
      <span bind:this={overlayTitleLine2El}></span>
      <span bind:this={overlayTitleLine3El}></span>
    </h1>
  </div>

  <button
    aria-label={tr.labels.scroll}
    class="hero-arrow opacity-0 invisible group absolute right-0 bottom-0 w-[50px] h-[100px] bg-accent text-bg flex items-center justify-center overflow-hidden hover:bg-ink transition-colors duration-300"
  >
    <svg
      width="14"
      height="22"
      viewBox="0 0 14 22"
      fill="none"
      aria-hidden="true"
      class="transition-transform duration-300 ease-out group-hover:translate-y-1.5"
    >
      <path
        d="M7 1V21M7 21L1 15M7 21L13 15"
        stroke="currentColor"
        stroke-width="1.4"
        stroke-linecap="square"
      />
    </svg>
  </button>
</section>

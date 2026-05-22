<script lang="ts">
  import { onMount } from "svelte";
  import { animate } from "motion";
  import MagneticDots from "./MagneticDots.svelte";
  import MenuOverlay from "./MenuOverlay.svelte";
  import HeroTitle from "./HeroTitle.svelte";
  import MouseInfo from "./MouseInfo.svelte";
  import { t, type Lang } from "../locales";
  import { scrambleTo } from "../utils/scramble";
  import SphereScene from "./SphereScene.svelte";

  let { initialLang = "pl" as Lang } = $props();

  let menuOpen = $state(false);
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

  let overlayTitleLine1El: HTMLSpanElement;
  let overlayTitleLine2El: HTMLSpanElement;
  let overlayTitleLine3El: HTMLSpanElement;
  let overlayTaglineEl: HTMLSpanElement;
  const overlayTitleLine1Gen = { v: 0 };
  const overlayTitleLine2Gen = { v: 0 };
  const overlayTitleLine3Gen = { v: 0 };
  const overlayTaglineGen = { v: 0 };

  function initIntro() {
    // Usuń klasę invisible z elementów, by ujawnić je przed animacją opacity
    const elements = document.querySelectorAll(
      ".hero-center, .hero-nav, .hero-left, .hero-mouse-info, .hero-arrow",
    );
    elements.forEach((el) => el.classList.remove("invisible"));

    animate([
      [
        ".hero-center",
        { scale: [1.06, 1], opacity: [0, 1] },
        { duration: 1.6, ease: "easeOut" },
      ],
      [
        ".hero-nav",
        { y: [-20, 0], opacity: [0, 1] },
        { duration: 1.2, ease: "easeOut", at: "-1.2" },
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
      scrambleTo(overlayTitleLine1El, "THINK", overlayTitleLine1Gen);
    }
    if (overlayTitleLine2El) {
      scrambleTo(overlayTitleLine2El, "CREATE", overlayTitleLine2Gen);
    }
    if (overlayTitleLine3El) {
      scrambleTo(overlayTitleLine3El, "CODE", overlayTitleLine3Gen);
    }
    if (overlayTaglineEl) {
      scrambleTo(
        overlayTaglineEl,
        "2018—Future",
        overlayTaglineGen,
        undefined,
        undefined,
        1.2,
      );
    }
  }

  $effect(() => {
    const isLoaded = (window as any).loaderDone;
    if (!isLoaded) return;

    if (menuOpen) {
      animate(
        ".hero-title-overlay",
        { opacity: 0, scale: 0.96 },
        { duration: 0.4, ease: "easeOut" },
      );
    } else {
      animate(
        ".hero-title-overlay",
        { opacity: 1, scale: 1 },
        { duration: 0.5, ease: "easeOut" },
      );
    }
  });

  onMount(() => {
    if ((window as any).loaderDone) {
      initIntro();
    } else {
      window.addEventListener("loaderFinished", initIntro, { once: true });
    }

    return () => {
      window.removeEventListener("loaderFinished", initIntro);
    };
  });
</script>

<section
  class="relative h-screen w-full overflow-hidden px-8 md:px-12 py-6 md:py-8 flex flex-col"
>
  <!-- TOP NAV -->
  <nav class="hero-nav opacity-0 invisible flex items-center justify-between">
    <!-- LOGOTYPE -->
    <a
      href="/"
      class="font-black tracking-tight hover:text-accent transition-colors"
      style="font-family: var(--font-display); font-size: 1.15rem;"
    >
      LOGOTYPE
    </a>

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
        <MagneticDots onclick={() => (menuOpen = true)} />
      </li>
    </ul>
  </nav>

  <!-- MAIN GRID -->
  <div class="flex-1 grid grid-cols-12 gap-6 mt-2">
    <!-- LEFT COLUMN -->
    <div
      class="hero-left opacity-0 invisible col-span-3 relative flex flex-col justify-center z-10"
    >
      <div>
        <HeroTitle {lang} />
      </div>

      <!-- LinkedIn link -->
      <a
        href="#"
        class="absolute bottom-0 left-0 label inline-flex items-center gap-1.5 hover:text-accent transition-colors"
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
    </div>

    <!-- CENTER SPACE HOLDER -->
    <div class="col-span-6 pointer-events-none"></div>

    <!-- RIGHT COLUMN -->
    <div class="col-span-3 relative flex flex-col justify-center z-10">
      <MouseInfo />
    </div>
  </div>

  <!-- CENTER MODEL — ABSOLUTE VIEWPORT CENTERED -->
  <div
    class="hero-center opacity-0 invisible absolute inset-0 flex items-center justify-center pointer-events-none z-0"
  >
    <div
      class="relative w-[50vw] h-[62vh] flex items-center justify-center pointer-events-auto"
    >
      <div class="w-full h-full rounded-sm relative">
        <SphereScene />
      </div>
    </div>
  </div>

  <!-- Tagline & Title Bottom Overlay -->
  <div
    class="hero-title-overlay absolute bottom-8 md:bottom-10 left-1/2 -translate-x-1/2 z-10 pointer-events-none select-none text-center flex flex-col items-center gap-2 sm:gap-3"
  >
    <h1
      class="text-xl sm:text-2xl md:text-3xl lg:text-[2.2vw] font-black uppercase tracking-widest text-ink select-none flex items-center justify-center gap-5 sm:gap-8"
      style="font-family: var(--font-display);"
    >
      <span bind:this={overlayTitleLine1El}>THINK</span>
      <span bind:this={overlayTitleLine2El}>CREATE</span>
      <span bind:this={overlayTitleLine3El}>CODE</span>
    </h1>
    <p
      class="text-[12px] md:text-[16px] font-display tracking-widest text-ink uppercase leading-relaxed select-none"
    >
      <span bind:this={overlayTaglineEl}>2018—Future</span>
    </p>
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

<!-- MENU OVERLAY (osobny komponent) -->
<MenuOverlay {lang} bind:menuOpen />

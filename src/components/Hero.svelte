<script lang="ts">
  import { onMount } from "svelte";
  import { animate } from "motion";
  import MagneticDots from "./MagneticDots.svelte";
  import MenuOverlay from "./MenuOverlay.svelte";
  import HeroTitle from "./HeroTitle.svelte";
  import MouseInfo from "./MouseInfo.svelte";
  import { t, type Lang } from "../locales";
  import { scrambleTo } from "../utils/scramble";

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

  function initIntro() {
    // Usuń klasę invisible z elementów, by ujawnić je przed animacją opacity
    const elements = document.querySelectorAll(".hero-center, .hero-nav, .hero-left, .hero-mouse-info, .hero-arrow");
    elements.forEach(el => el.classList.remove("invisible"));

    animate([
      [
        ".hero-center", 
        { scale: [1.06, 1], opacity: [0, 1] }, 
        { duration: 1.6, ease: "easeOut" }
      ],
      [
        ".hero-nav", 
        { y: [-20, 0], opacity: [0, 1] }, 
        { duration: 1.2, ease: "easeOut", at: "-1.2" }
      ],
      [
        ".hero-left", 
        { y: [25, 0], opacity: [0, 1] }, 
        { duration: 1.2, ease: "easeOut", at: "-1.0" }
      ],
      [
        ".hero-mouse-info", 
        { y: [25, 0], opacity: [0, 1] }, 
        { duration: 1.2, ease: "easeOut", at: "-0.85" }
      ],
      [
        ".hero-arrow", 
        { y: [40, 0], opacity: [0, 1] }, 
        { duration: 0.8, ease: "easeOut", at: "-0.8" }
      ]
    ]);
  }

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

<section class="relative h-screen w-full overflow-hidden px-8 md:px-12 py-6 md:py-8 flex flex-col">
  <!-- TOP NAV -->
  <nav class="hero-nav opacity-0 invisible flex items-center justify-between">
    <!-- LOGOTYPE -->
    <a href="/" class="font-black tracking-tight hover:text-accent transition-colors" style="font-family: var(--font-display); font-size: 1.15rem;">
      LOGOTYPE
    </a>

    <ul class="flex items-center gap-10">
      {#each navRight as item, i (item.label)}
        <li>
          <a
            href={item.href}
            class="label hover:text-accent transition-colors inline-flex items-center gap-1.5"
            onmouseenter={() => scrambleTo(navTextEls[i], item.label, navGens[i], undefined, undefined, 2)}
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
  <div class="flex-1 grid grid-cols-12 gap-6 mt-8 md:mt-12">

    <!-- LEFT COLUMN -->
    <div class="hero-left opacity-0 invisible col-span-3 relative flex flex-col justify-center">
      <div>
        <HeroTitle {lang} />


      </div>

      <!-- LinkedIn link -->
      <a
        href="#"
        class="absolute bottom-0 left-0 label inline-flex items-center gap-1.5 hover:text-accent transition-colors"
        onmouseenter={() => scrambleTo(linkedinTextEl, tr.labels.linkedin, linkedinGen, undefined, undefined, 2)}
      >
        <span bind:this={linkedinTextEl}>{tr.labels.linkedin}</span>
        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <path d="M7 7h10v10" />
          <path d="m7 17 10-10" />
        </svg>
      </a>
    </div>

    <!-- CENTER — PLACEHOLDER IMAGE -->
    <div class="hero-center opacity-0 invisible col-span-6 flex items-center justify-center">
      <div class="relative w-full h-full max-h-[78vh] flex items-center justify-center">
        <div
          class="w-full h-full rounded-sm relative overflow-hidden"
          style="background:
            radial-gradient(120% 100% at 50% 30%, #E8E5DE 0%, #D9D6D0 60%, #C8C4BD 100%);"
        >
          <div
            class="absolute inset-0 opacity-[0.06]"
            style="background-image: repeating-linear-gradient(135deg, #0A0A0A 0 1px, transparent 1px 14px);"
          ></div>

          <span class="absolute top-4 left-4 label text-ink/40">
            placeholder · model image
          </span>
        </div>
      </div>
    </div>

    <!-- RIGHT COLUMN -->
    <div class="col-span-3 relative flex flex-col justify-center">
      <MouseInfo />
    </div>

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
<MenuOverlay lang={lang} bind:menuOpen />

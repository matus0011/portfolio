<script lang="ts">
  import { onMount } from "svelte";
  import { animate } from "motion";
  import { gsap } from "gsap";
  import { ScrollTrigger } from "gsap/ScrollTrigger";
  import MagneticDots from "./MagneticDots.svelte";
  import { t, type Lang } from "../locales";
  import { scrambleTo, DIGIT_CHARS } from "../utils/scramble";
  import { ui } from "../state/ui.svelte";

  let heroSectionEl: HTMLElement;

  let { initialLang = "pl" as Lang } = $props();

  const lang = $derived(initialLang as Lang);
  const tr = $derived(t(lang));

  const linkedinGen = { v: 0 };
  let linkedinTextEl: HTMLSpanElement;

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
  let titleChars: HTMLSpanElement[] = [];
  let titleH1El: HTMLHeadingElement;
  let titleSvgDefsEl: SVGDefsElement;
  const titleFilterIdPrefix = `hero-distort-${Math.random().toString(36).slice(2, 9)}`;

  const roles    = $derived([...tr.hero.roles]);
  const statusSymbols = ["=>", "&&", "fn", "||"];

  let roleEl     = $state<HTMLSpanElement | null>(null);

  let roleIdx   = 0;
  let cursor    = $state(true);

  const rGen = { v: 0 };
  const availableGen = { v: 0 };
  const statusGens = [{ v: 0 }, { v: 0 }, { v: 0 }, { v: 0 }];

  // Per-letter filter state: scale grows with proximity to cursor
  // Center (cursor letter) = peak, ±1 = mid, ±2 = light, beyond = clean
  const HOVER_INTENSITY = [28]; // only the letter under cursor
  const HOVER_FREQ = 0.022;
  const HOVER_DURATION = 0.45;

  type LetterFilter = {
    span: HTMLSpanElement;
    turbEl: SVGFETurbulenceElement;
    displEl: SVGFEDisplacementMapElement;
    state: { freq: number; scale: number };
  };
  let letterFilters: LetterFilter[] = [];

  function makeLetterFilters() {
    if (!titleSvgDefsEl) return;
    // Clear any previous filters
    titleSvgDefsEl.innerHTML = "";
    letterFilters = [];

    titleChars.forEach((span, i) => {
      const filterId = `${titleFilterIdPrefix}-${i}`;
      const NS = "http://www.w3.org/2000/svg";
      const filter = document.createElementNS(NS, "filter");
      filter.setAttribute("id", filterId);
      filter.setAttribute("x", "-20%");
      filter.setAttribute("y", "-20%");
      filter.setAttribute("width", "140%");
      filter.setAttribute("height", "140%");

      const turb = document.createElementNS(NS, "feTurbulence");
      turb.setAttribute("type", "fractalNoise");
      turb.setAttribute("baseFrequency", "0.005");
      turb.setAttribute("numOctaves", "2");
      turb.setAttribute("seed", String(7 + i));
      turb.setAttribute("result", "turb");
      filter.appendChild(turb);

      const displ = document.createElementNS(NS, "feDisplacementMap");
      displ.setAttribute("in", "SourceGraphic");
      displ.setAttribute("in2", "turb");
      displ.setAttribute("scale", "0");
      filter.appendChild(displ);

      titleSvgDefsEl.appendChild(filter);

      span.style.filter = `url(#${filterId})`;
      span.style.willChange = "filter";

      letterFilters.push({
        span,
        turbEl: turb as SVGFETurbulenceElement,
        displEl: displ as SVGFEDisplacementMapElement,
        state: { freq: 0.005, scale: 0 },
      });
    });
  }

  function applyLetterFilter(lf: LetterFilter) {
    lf.turbEl.setAttribute("baseFrequency", String(lf.state.freq));
    lf.displEl.setAttribute("scale", String(lf.state.scale));
  }

  function setLetterTargets(centerIdx: number) {
    letterFilters.forEach((lf, i) => {
      const dist = centerIdx < 0 ? Infinity : Math.abs(i - centerIdx);
      const scale = dist < HOVER_INTENSITY.length ? HOVER_INTENSITY[dist] : 0;
      const freq = scale > 0 ? HOVER_FREQ : 0.005;
      gsap.to(lf.state, {
        scale,
        freq,
        duration: HOVER_DURATION,
        ease: "power2.out",
        onUpdate: () => applyLetterFilter(lf),
        overwrite: "auto",
      });
    });
  }

  function findHoveredCharIndex(clientX: number, clientY: number): number {
    // Direct pointer test — each span has its own bounding box
    for (let i = 0; i < letterFilters.length; i++) {
      const r = letterFilters[i].span.getBoundingClientRect();
      if (
        clientX >= r.left &&
        clientX <= r.right &&
        clientY >= r.top &&
        clientY <= r.bottom
      ) {
        return i;
      }
    }
    return -1;
  }

  let lastHoverIdx = -2;
  function onTitleMouseMove(e: MouseEvent) {
    const idx = findHoveredCharIndex(e.clientX, e.clientY);
    if (idx !== lastHoverIdx) {
      lastHoverIdx = idx;
      setLetterTargets(idx);
    }
  }
  function onTitleMouseLeave() {
    if (lastHoverIdx !== -1) {
      lastHoverIdx = -1;
      setLetterTargets(-1);
    }
  }

  function collectTitleCharsFromDOM() {
    titleChars = [];
    const lines = [overlayTitleLine1El, overlayTitleLine2El];
    lines.forEach((line) => {
      if (!line) return;
      line.querySelectorAll(":scope > span").forEach((s) => {
        const span = s as HTMLSpanElement;
        span.style.display = "inline-block";
        span.style.willChange = "filter";
        titleChars.push(span);
      });
    });
  }

  function runTitleIntro() {
    // Make sure h1 is visible
    if (titleH1El) gsap.set(titleH1El, { opacity: 1 });

    let completed = 0;
    const onLineDone = () => {
      completed++;
      if (completed === 2) {
        // All scrambles settled — wire per-letter hover filters
        collectTitleCharsFromDOM();
        makeLetterFilters();
      }
    };

    if (overlayTitleLine1El)
      scrambleTo(overlayTitleLine1El, tr.hero.tagline[0], overlayTitleLine1Gen, undefined, " ", 1, onLineDone);
    if (overlayTitleLine2El)
      scrambleTo(overlayTitleLine2El, tr.hero.tagline[1], overlayTitleLine2Gen, undefined, " ", 1, onLineDone);
  }

  function initIntro() {
    const elements = document.querySelectorAll(
      ".hero-nav, .hero-left",
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
    ]);

    runTitleIntro();
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
        runTitleIntro();
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

    // Blinking cursor
    const cursorInterval = setInterval(() => {
      cursor = !cursor;
    }, 530);

    // Words rotation
    let wordInterval: any;
    const initTitleAnimations = () => {
      if (!roleEl) return;
      scrambleTo(roleEl, roles[0], rGen);

      wordInterval = setInterval(() => {
        roleIdx = (roleIdx + 1) % roles.length;
        if (roleEl) scrambleTo(roleEl, roles[roleIdx], rGen);
      }, 10000);
    };

    if ((window as any).loaderDone) {
      initTitleAnimations();
    } else {
      window.addEventListener("loaderFinished", initTitleAnimations, { once: true });
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
      ScrollTrigger.create({
        trigger: heroSectionEl,
        start: "top top",
        end: "bottom top",
        scrub: true,
        onUpdate: (self) => {
          ui.heroScrollProgress = self.progress;
        },
      });
    }, heroSectionEl);

    // Bind per-letter hover handlers
    const hoverFrame = requestAnimationFrame(() => {
      if (titleH1El) {
        titleH1El.addEventListener("mousemove", onTitleMouseMove);
        titleH1El.addEventListener("mouseleave", onTitleMouseLeave);
      }
    });

    return () => {
      clearInterval(clockInterval);
      clearInterval(cursorInterval);
      if (wordInterval) clearInterval(wordInterval);
      window.removeEventListener("loaderFinished", initIntro);
      window.removeEventListener("loaderFinished", initTitleAnimations);
      cancelAnimationFrame(hoverFrame);
      if (titleH1El) {
        titleH1El.removeEventListener("mousemove", onTitleMouseMove);
        titleH1El.removeEventListener("mouseleave", onTitleMouseLeave);
      }
      ctx.revert();
    };
  });
</script>

<section
  bind:this={heroSectionEl}
  class="relative h-screen w-full overflow-hidden px-8 md:px-12 py-6 md:py-8 flex flex-col"
>
  <!-- TOP NAV -->
  <nav class="hero-nav opacity-0 invisible flex items-start justify-between">
    <div class="text-accent flex flex-col gap-0" style="font-family: var(--font-display)">
      <div
        class="label flex items-center gap-1 cursor-default pointer-events-auto"
        style="font-size: 18px; line-height: 0.95;"
        onmouseenter={(e) => scrambleTo(e.currentTarget.querySelector('.status-text') as HTMLElement, tr.hero.availableLabel, availableGen)}
      >
        <span>[</span>
        <span class="status-text">{tr.hero.availableLabel}</span>
        <span>]</span>
      </div>
      {#each tr.hero.statuses as status, i}
        <div
          class="label flex items-center gap-1.5 opacity-90 cursor-default pointer-events-auto"
          style="font-size: 18px; line-height: 0.95;"
          onmouseenter={(e) => scrambleTo(e.currentTarget.querySelector('.status-text') as HTMLElement, status, statusGens[i])}
        >
          <span class="font-mono">{statusSymbols[i] || "=>"}</span>
          <span class="status-text">{status}</span>
        </div>
      {/each}
    </div>

    <MagneticDots onclick={() => (ui.menuOpen = true)} />
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
        class="label inline-flex items-center gap-1.5 text-accent transition-colors"
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
      <div class="label flex items-center gap-1.5 text-accent">
        <span>{tr.hero.location}</span>
        <span class="opacity-40">·</span>
        <span bind:this={timeEl}></span>
        <span>{timezone}</span>
      </div>
      </div>
    </div>

    <!-- CENTER SPACE HOLDER -->
    <div class="col-span-9 pointer-events-none"></div>
  </div>


  <!-- BOTTOM CENTER — Prefix & Roles -->
  <div
    class="hero-left opacity-0 invisible absolute bottom-2 left-1/2 -translate-x-1/2 md:bottom-3 z-10 text-center flex flex-col items-center gap-0"
    style="font-family: var(--font-display)"
  >
    <div class="label" style="font-size: 42px; font-weight: 600; line-height: 0.95;">{tr.hero.prefix}</div>
    <div class="label text-accent font-mono tracking-normal" style="font-size: 56px; font-weight: 600; line-height: 0.95;" bind:this={roleEl}></div>
    <div class="label flex items-center justify-center gap-1.5" style="font-size: 42px; font-weight: 600; line-height: 0.95;">
      <span>{tr.hero.roleLabel}</span>
    </div>
  </div>

  <!-- BOTTOM RIGHT — Scroll Prompt -->
  <div
    class="hero-left opacity-0 invisible absolute bottom-6 right-8 md:bottom-8 md:right-12 z-10 text-accent"
    style="font-size: 21px;"
  >
    <div class="label flex items-center gap-1">
      <span>[</span>
      <span>SCROLL TO EXPLORE</span>
      <span>]</span>
    </div>
  </div>

  <!-- Center Titles Overlay -->
  <div
    class="hero-title-overlay absolute inset-0 flex flex-col items-stretch justify-center pointer-events-none z-10 select-none px-8 md:px-12"
  >
    <!-- SVG filter defs — populated at runtime, one filter per letter -->
    <svg
      aria-hidden="true"
      width="0"
      height="0"
      style="position:absolute;width:0;height:0;pointer-events:none"
    >
      <defs bind:this={titleSvgDefsEl}></defs>
    </svg>

    <h1
      bind:this={titleH1El}
      class="hero-title text-ink select-none w-full pointer-events-auto cursor-default"
      style="will-change: opacity; transform: translateY(-100px);"
    >
      <span
        bind:this={overlayTitleLine1El}
        class="hero-title-line-1 display-mega text-[15vw] md:text-[12vw] select-none pointer-events-none relative text-center"
      ></span>
      <span
        bind:this={overlayTitleLine2El}
        class="hero-title-line-2 display-mega text-[15vw] md:text-[12vw] select-none pointer-events-none relative text-center"
      ></span>
    </h1>
  </div>

</section>

<style>
  .hero-title {
    display: grid;
    grid-template-columns: max-content;
    justify-content: center;
    line-height: 0.85;
    gap: 3vw;
  }

</style>

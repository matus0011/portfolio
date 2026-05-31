<script lang="ts">
  import { onMount } from "svelte";
  import { fade } from "svelte/transition";
  import { gsap } from "gsap";
  import { ScrollTrigger } from "gsap/ScrollTrigger";
  import { t, type Lang } from "../locales";
  import { scrambleTo } from "../utils/scramble";
  import { ui } from "../state/ui.svelte";

  let { lang = "pl" as Lang }: { lang: Lang } = $props();

  const tr = $derived(t(lang));

  const menuItems = $derived([
    { id: "home", label: tr.nav.home, target: "top" },
    { id: "about", label: tr.nav.about, target: "#about" },
    { id: "tech", label: tr.nav.tech, target: "#tech" },
    { id: "contact", label: tr.nav.contact, target: "#contact" },
  ]);

  const activeIndex = $derived(
    Math.max(0, menuItems.findIndex((m) => m.id === ui.activeSection)),
  );

  function goTo(target: string) {
    ui.menuOpen = false;
    requestAnimationFrame(() => {
      const sm = window.smoother;
      if (sm) {
        if (target === "top") sm.scrollTo(0, true);
        else sm.scrollTo(target, true, "top top");
      } else if (target === "top") {
        window.scrollTo({ top: 0, behavior: "smooth" });
      } else {
        document.querySelector(target)?.scrollIntoView({ behavior: "smooth" });
      }
    });
  }

  let hoveredIndex = $state(0);
  let indicatorY = $state(0);
  let navEl = $state<HTMLElement | null>(null);
  let markerLeft = $state(0);
  let markerRight = $state(0);

  const gens = [{ v: 0 }, { v: 0 }, { v: 0 }, { v: 0 }];

  function getViewportY(el: HTMLElement) {
    const r = el.getBoundingClientRect();
    return r.top + r.height / 2;
  }

  function updateNavBounds() {
    if (!navEl) return;
    const r = navEl.getBoundingClientRect();
    markerLeft = r.left - 72;
    markerRight = window.innerWidth - r.right - 72;
  }

  function handleLinkMouseEnter(i: number, el: HTMLElement) {
    hoveredIndex = i;
    indicatorY = getViewportY(el);
    scrambleTo(el, menuItems[i].label, gens[i], undefined, undefined, 2);
  }

  function pointTo(i: number) {
    const links = navEl?.querySelectorAll("a");
    const el = links?.[i] as HTMLElement | undefined;
    if (el) indicatorY = getViewportY(el);
  }

  function handleNavMouseLeave() {
    hoveredIndex = activeIndex;
    pointTo(activeIndex);
  }

  $effect(() => {
    if (ui.menuOpen) {
      requestAnimationFrame(() => {
        hoveredIndex = activeIndex;
        pointTo(activeIndex);
        updateNavBounds();
      });
    }
  });

  // Scroll-spy: highlight the section currently in view.
  onMount(() => {
    gsap.registerPlugin(ScrollTrigger);
    let triggers: ScrollTrigger[] = [];
    const setup = () => {
      ["home", "about", "tech", "contact"].forEach((id) => {
        const el = document.querySelector("#" + id);
        if (!el) return;
        triggers.push(
          ScrollTrigger.create({
            trigger: el,
            start: "top 45%",
            end: "bottom 45%",
            onToggle: (self) => {
              if (self.isActive) ui.activeSection = id;
            },
          }),
        );
      });
      ScrollTrigger.refresh();
    };
    if (window.smoother) setup();
    else window.addEventListener("smootherReady", setup, { once: true });
    return () => {
      window.removeEventListener("smootherReady", setup);
      triggers.forEach((t) => t.kill());
    };
  });
</script>

{#if ui.menuOpen}
  <div
    transition:fade={{ duration: 250 }}
    class="fixed inset-0 z-50 flex flex-col justify-between px-8 md:px-12 py-6 md:py-8"
    style="background-color: rgba(221, 219, 214, 0.15); backdrop-filter: blur(5px); -webkit-backdrop-filter: blur(5px);"
  >
    <!-- CLOSE BUTTON -->
    <div class="flex justify-end items-center w-full">
      <button
        onclick={() => (ui.menuOpen = false)}
        class="hover:text-accent transition-colors cursor-pointer flex items-center justify-center p-8 -m-8"
        aria-label={tr.labels.close}
      >
        <svg
          class="w-10 h-10"
          viewBox="0 0 24 24"
          fill="none"
          stroke="currentColor"
          stroke-width="2.5"
          stroke-linecap="square"
          stroke-linejoin="miter"
        >
          <line x1="18" y1="6" x2="6" y2="18"></line>
          <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
      </button>
    </div>

    <!-- MAIN LINKS -->
    <div class="flex-1 flex flex-col justify-center items-center my-12">
      <nav
        class="relative flex flex-col gap-[6px] text-center"
        bind:this={navEl}
        onmouseleave={handleNavMouseLeave}
      >
        <!-- Left marker -->
        <span
          class="Menu-nav--marker __left fixed select-none pointer-events-none text-accent"
          style="top: {indicatorY}px; left: {markerLeft}px; transform: translateY(-50%);"
        >
          &#123;
        </span>

        <!-- Right marker -->
        <span
          class="Menu-nav--marker __right fixed select-none pointer-events-none text-accent"
          style="top: {indicatorY}px; right: {markerRight}px; transform: translateY(-50%);"
        >
          &#125;
        </span>

        {#each menuItems as item, i (item.label)}
          <a
            href={item.target === "top" ? "/" : item.target}
            onclick={(e) => {
              e.preventDefault();
              goTo(item.target);
            }}
            onmouseenter={(e) => handleLinkMouseEnter(i, e.currentTarget as HTMLElement)}
            class="inline-block text-6xl sm:text-8xl md:text-9xl font-black uppercase tracking-tight hover:text-accent transition-colors duration-300 py-0 leading-none"
            class:text-accent={item.id === ui.activeSection}
            style="font-family: var(--font-display)"
          >
            {item.label}
          </a>
        {/each}
      </nav>
    </div>

    <!-- BOTTOM: language switcher -->
    <div class="flex justify-between items-center w-full pt-6">
      <div class="flex items-center gap-1 text-sm md:text-base uppercase font-mono font-black" style="font-family: var(--font-display)">
        <a
          href="/"
          class="hover:text-accent transition-colors cursor-pointer {lang === 'pl' ? 'text-accent' : 'text-foreground font-black'}"
        >
          PL
        </a>
        <span class="text-foreground">/</span>
        <a
          href="/en"
          class="hover:text-accent transition-colors cursor-pointer {lang === 'en' ? 'text-accent' : 'text-foreground font-black'}"
        >
          EN
        </a>
      </div>
      <div></div>
    </div>
  </div>
{/if}

<style>
  .Menu-nav--marker {
    font-family: "Mona Sans", sans-serif;
    font-size: 150px;
    font-weight: 700;
    line-height: 0.75;
    letter-spacing: -2px;
    font-stretch: condensed;
    white-space: nowrap;
    transition: top 0.5s cubic-bezier(0.16, 1, 0.3, 1);
  }


  @media (max-width: 768px) {
    .Menu-nav--marker {
      font-size: 100px;
      letter-spacing: -1.6px;
    }
  }
</style>

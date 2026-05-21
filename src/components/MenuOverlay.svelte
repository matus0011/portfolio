<script lang="ts">
  import { fade } from "svelte/transition";
  import { t, type Lang } from "../locales";
  import { scrambleTo } from "../utils/scramble";

  let {
    lang = "pl" as Lang,
    menuOpen = $bindable(false),
  }: { lang: Lang; menuOpen: boolean } = $props();

  const tr = $derived(t(lang));

  const menuItems = $derived([
    { label: tr.nav.projects, href: "#" },
    { label: tr.nav.about, href: "#" },
    { label: tr.nav.contact, href: "#" },
  ]);

  let hoveredIndex = $state(0);
  let indicatorY = $state(0);
  let navEl = $state<HTMLElement | null>(null);

  const gens = [{ v: 0 }, { v: 0 }, { v: 0 }];

  function handleLinkMouseEnter(i: number, el: HTMLElement) {
    hoveredIndex = i;
    indicatorY = el.offsetTop + el.offsetHeight / 2;
    scrambleTo(el, menuItems[i].label, gens[i], undefined, undefined, 2);
  }

  function handleNavMouseLeave() {
    hoveredIndex = 0;
    const first = navEl?.querySelector("a") as HTMLElement | null;
    if (first) indicatorY = first.offsetTop + first.offsetHeight / 2;
  }

  $effect(() => {
    if (menuOpen) {
      requestAnimationFrame(() => {
        const first = navEl?.querySelector("a") as HTMLElement | null;
        if (first) indicatorY = first.offsetTop + first.offsetHeight / 2;
      });
    }
  });
</script>

{#if menuOpen}
  <div
    transition:fade={{ duration: 250 }}
    class="fixed inset-0 z-50 flex flex-col justify-between px-8 md:px-12 py-6 md:py-8"
    style="background-color: rgba(224, 224, 224, 0.15); backdrop-filter: blur(5px); -webkit-backdrop-filter: blur(5px);"
  >
    <!-- CLOSE BUTTON -->
    <div class="flex justify-end items-center w-full">
      <button
        onclick={() => (menuOpen = false)}
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
          class="Menu-nav--marker __left absolute select-none pointer-events-none transition-[top] duration-300 ease-out -translate-y-1/2 text-accent"
          style="top: {indicatorY}px;"
        >
          &#123;
        </span>

        <!-- Right marker -->
        <span
          class="Menu-nav--marker __right absolute select-none pointer-events-none transition-[top] duration-300 ease-out -translate-y-1/2 text-accent"
          style="top: {indicatorY}px;"
        >
          &#125;
        </span>

        {#each menuItems as item, i (item.label)}
          <a
            href={item.href}
            onclick={() => (menuOpen = false)}
            onmouseenter={(e) => handleLinkMouseEnter(i, e.currentTarget as HTMLElement)}
            class="inline-block text-6xl sm:text-8xl md:text-9xl font-black uppercase tracking-tight hover:text-accent transition-colors duration-300 py-0 leading-none"
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
    font-family: "Bricolage Grotesque", sans-serif;
    font-size: 150px;
    font-weight: 700;
    line-height: 0.75;
    letter-spacing: -2px;
    font-stretch: condensed;
    white-space: nowrap;
  }

  .Menu-nav--marker.__left {
    right: calc(100% + 52px);
    left: auto;
  }

  .Menu-nav--marker.__right {
    left: calc(100% + 52px);
    right: auto;
  }

  @media (max-width: 768px) {
    .Menu-nav--marker {
      font-size: 100px;
      letter-spacing: -1.6px;
    }
  }
</style>

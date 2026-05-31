<script lang="ts">
  import { onMount } from "svelte";
  import { gsap } from "gsap";
  import { ui } from "../state/ui.svelte";
  import { t, type Lang } from "../locales";
  import { scrambleTo } from "../utils/scramble";
  import MagneticDots from "./MagneticDots.svelte";

  let { lang = "pl" as Lang }: { lang?: Lang } = $props();
  const tr = $derived(t(lang));

  const statusSymbols = ["=>", "&&", "fn", "||"];
  const availableGen = { v: 0 };
  const statusGens = [{ v: 0 }, { v: 0 }, { v: 0 }, { v: 0 }];

  let headerEl: HTMLElement;
  let onAccent = $state(false);

  // Sample what's behind the bar; invert colours over the (orange) accent
  // section, unless a light overlay (experience / CTA) is covering it.
  onMount(() => {
    let last = false;
    const tick = () => {
      if (!headerEl) return;
      const y = headerEl.getBoundingClientRect().bottom + 10;
      const el = document.elementFromPoint(window.innerWidth / 2, y);
      const acc =
        !!el &&
        !!el.closest(".accent-section") &&
        !el.closest(".tech-outro") &&
        !el.closest(".cta-screen");
      if (acc !== last) {
        last = acc;
        onAccent = acc;
      }
    };
    gsap.ticker.add(tick);
    return () => gsap.ticker.remove(tick);
  });
</script>

<header
  bind:this={headerEl}
  class="tb fixed top-0 left-0 w-full z-[45] flex items-start justify-between px-8 md:px-12 py-6 md:py-8"
  class:tb--hidden={ui.menuOpen}
  class:tb--on-accent={onAccent}
>
  <div
    class="text-accent flex flex-col gap-0"
    style="font-family: var(--font-display)"
  >
    <div
      class="label flex items-center gap-1 cursor-default pointer-events-auto"
      style="font-size: 18px; line-height: 0.95;"
      onmouseenter={(e) =>
        scrambleTo(
          e.currentTarget.querySelector(".status-text") as HTMLElement,
          tr.hero.availableLabel,
          availableGen,
        )}
    >
      <span>[</span>
      <span class="status-text">{tr.hero.availableLabel}</span>
      <span>]</span>
    </div>
    {#each tr.hero.statuses as status, i}
      <div
        class="label flex items-center gap-1.5 opacity-90 cursor-default pointer-events-auto"
        style="font-size: 18px; line-height: 0.95;"
        onmouseenter={(e) =>
          scrambleTo(
            e.currentTarget.querySelector(".status-text") as HTMLElement,
            status,
            statusGens[i],
          )}
      >
        <span class="font-mono">{statusSymbols[i] || "=>"}</span>
        <span class="status-text">{status}</span>
      </div>
    {/each}
  </div>

  <MagneticDots onclick={() => (ui.menuOpen = true)} />
</header>

<style>
  .tb {
    transition:
      opacity 0.35s ease,
      transform 0.35s ease;
  }
  .tb--hidden {
    opacity: 0;
    transform: translateY(-12px);
    pointer-events: none;
  }

  /* smooth colour swap */
  .tb :global(.label),
  .tb :global(button),
  .tb :global(span) {
    transition: color 0.3s ease;
  }

  /* over the orange accent section → switch to dark for contrast */
  .tb--on-accent :global(.text-accent),
  .tb--on-accent :global(button) {
    color: var(--color-ink);
  }
</style>

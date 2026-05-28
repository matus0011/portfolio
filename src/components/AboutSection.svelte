<script lang="ts">
  import { onMount } from "svelte";
  import gsap from "gsap";
  import { ScrollTrigger } from "gsap/ScrollTrigger";
  import { t, type Lang } from "../locales";
  import { scrambleTo, CHARS } from "../utils/scramble";

  let { initialLang = "pl" as Lang } = $props();
  const lang = $derived(initialLang as Lang);
  const tr = $derived(t(lang));
  const LINES = $derived(tr.aboutLines);

  let lineEls: HTMLElement[] = [];
  const gens = LINES.map(() => ({ v: 0 }));

  onMount(() => {
    gsap.registerPlugin(ScrollTrigger);

    lineEls.forEach((el, i) => {
      if (!el) return;

      el.textContent = LINES[i];

      ScrollTrigger.create({
        trigger: el,
        start: "top 85%",
        once: true,
        onEnter: () => {
          scrambleTo(el, LINES[i], gens[i], CHARS, " ", 1.2);
        },
      });
    });

    return () => ScrollTrigger.getAll().forEach((t) => t.kill());
  });
</script>

<section class="about-section relative w-full px-8 md:px-12 overflow-hidden">
  <p data-speed="1.04" class="about-desc">
    {#each LINES as line, i}
      <span class="line-wrap">
        <span class="line-text" bind:this={lineEls[i]}>{line}</span>
      </span>
      {#if i < LINES.length - 1}<br/>{/if}
    {/each}
  </p>
</section>

<style>
  .about-section {
    padding-top: 16.25rem;
    padding-bottom: 16.25rem;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .about-desc {
    font-family: var(--font-display, "Mona Sans"), system-ui, sans-serif;
    font-size: clamp(2rem, 3.5vw, 4rem);
    font-weight: 500;
    line-height: 1.1;
    text-transform: uppercase;
    color: var(--color-ink);
    letter-spacing: -0.02em;
    text-align: center;
  }

  .line-wrap {
    display: inline-block;
    overflow: hidden;
  }

  .line-text {
    display: inline-block;
  }
</style>

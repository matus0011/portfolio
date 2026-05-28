<script lang="ts">
  import { onMount } from "svelte";
  import gsap from "gsap";
  import { ScrollTrigger } from "gsap/ScrollTrigger";

  let { label = "TECH STACK", duration = 30 } = $props();

  let section: HTMLElement;
  let track: HTMLElement;

  onMount(() => {
    gsap.registerPlugin(ScrollTrigger);

    const gap = parseFloat(getComputedStyle(track).gap) || 0;
    const spans = track.querySelectorAll("span");
    const half = spans.length / 2;
    const itemWidth = spans[0].offsetWidth + gap;
    const halfWidth = half * itemWidth;

    const tween = gsap.to(track, {
      x: -halfWidth,
      ease: "none",
      duration,
      repeat: -1,
      paused: true,
    });

    ScrollTrigger.create({
      trigger: section,
      start: "top bottom",
      markers: true,
      onEnter: () => tween.play(),
      onLeaveBack: () => tween.pause(),
    });

    return () => {
      tween.kill();
      ScrollTrigger.getAll().forEach((t) => t.kill());
    };
  });
</script>

<div bind:this={section} class="marquee-wrap">
  <div bind:this={track} class="marquee-row">
    {#each { length: 10 } as _}
      <span>{label}</span>
    {/each}
  </div>
</div>

<style>
  .marquee-wrap {
    width: 100%;
    overflow: hidden;
  }

  .marquee-row {
    display: flex;
    align-items: center;
    gap: 16rem;
    white-space: nowrap;
    will-change: transform;
    font-size: 16.875vw;
    font-weight: 400;
    line-height: 0.8em;
    letter-spacing: -0.02em;
    color: var(--color-ink);
    text-transform: uppercase;
  }
</style>

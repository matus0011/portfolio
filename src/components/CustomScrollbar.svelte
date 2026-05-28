<script lang="ts">
  import { onMount } from "svelte";
  import gsap from "gsap";
  import { ScrollTrigger } from "gsap/ScrollTrigger";
  import { ui } from "../state/ui.svelte";

  const MIN_THUMB = 48;
  const BAR_WIDTH = 6;

  let trackEl = $state<HTMLDivElement | null>(null);
  let thumbHeight = $state(0);
  let thumbTop = $state(0);
  let visible = $state(false);
  let scrollPercent = $state(0);

  function getMetrics() {
    const smoother = (window as Window & { smoother?: { scrollTop: () => number } }).smoother;
    const view = window.innerHeight;
    const max = ScrollTrigger.maxScroll(window) || 0;
    const scrollTop = smoother?.scrollTop() ?? window.scrollY;

    if (max <= 1) {
      return { visible: false, thumbHeight: 0, thumbTop: 0, scrollPercent: 0 };
    }

    const height = Math.max(MIN_THUMB, (view / (max + view)) * view);
    const travel = view - height;
    const top = (scrollTop / max) * travel;

    return {
      visible: true,
      thumbHeight: height,
      thumbTop: top,
      scrollPercent: Math.round((scrollTop / max) * 100),
    };
  }

  function applyMetrics() {
    const m = getMetrics();
    visible = m.visible;
    thumbHeight = m.thumbHeight;
    thumbTop = m.thumbTop;
    scrollPercent = m.scrollPercent;
  }

  function scrollToClientY(clientY: number) {
    const track = trackEl;
    if (!track) return;

    const rect = track.getBoundingClientRect();
    const ratio = Math.min(1, Math.max(0, (clientY - rect.top) / rect.height));
    const max = ScrollTrigger.maxScroll(window) || 0;
    const target = ratio * max;
    const smoother = (window as Window & { smoother?: { scrollTop: (n: number) => void } }).smoother;

    if (smoother) smoother.scrollTop(target);
    else window.scrollTo({ top: target, behavior: "auto" });
  }

  function onTrackPointerDown(e: PointerEvent) {
    if (e.button !== 0) return;
    scrollToClientY(e.clientY);

    const onMove = (move: PointerEvent) => scrollToClientY(move.clientY);
    const onUp = () => {
      window.removeEventListener("pointermove", onMove);
      window.removeEventListener("pointerup", onUp);
    };

    window.addEventListener("pointermove", onMove);
    window.addEventListener("pointerup", onUp);
  }

  onMount(() => {
    gsap.registerPlugin(ScrollTrigger);

    const tick = () => applyMetrics();
    gsap.ticker.add(tick);

    const onResize = () => {
      ScrollTrigger.refresh();
      applyMetrics();
    };
    window.addEventListener("resize", onResize);
    window.addEventListener("loaderFinished", onResize, { once: true });

    const waitSmoother = window.setInterval(() => {
      if ((window as Window & { smoother?: unknown }).smoother) {
        window.clearInterval(waitSmoother);
        ScrollTrigger.refresh();
        applyMetrics();
      }
    }, 50);

    applyMetrics();

    return () => {
      gsap.ticker.remove(tick);
      window.removeEventListener("resize", onResize);
      window.clearInterval(waitSmoother);
    };
  });
</script>

{#if visible && !ui.menuOpen}
  <div
    class="custom-scrollbar"
    style={`--bar-width: ${BAR_WIDTH}px`}
    role="scrollbar"
    aria-orientation="vertical"
    aria-valuemin={0}
    aria-valuemax={100}
    aria-valuenow={scrollPercent}
    aria-label="Scroll"
  >
    <div
      bind:this={trackEl}
      class="custom-scrollbar__track"
      onpointerdown={onTrackPointerDown}
      tabindex="-1"
    ></div>
    <div
      class="custom-scrollbar__thumb"
      style:height="{thumbHeight}px"
      style:transform="translate3d(0, {thumbTop}px, 0)"
    ></div>
  </div>
{/if}

<style>
  .custom-scrollbar {
    position: fixed;
    top: 0;
    right: 0;
    width: var(--bar-width);
    height: 100vh;
    height: 100dvh;
    z-index: 45;
    pointer-events: none;
  }

  .custom-scrollbar__track {
    position: absolute;
    inset: 0;
    background: var(--color-line);
    pointer-events: auto;
    cursor: pointer;
  }

  .custom-scrollbar__thumb {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    background: var(--color-accent);
    border-radius: 0;
    pointer-events: none;
    will-change: transform, height;
  }

  @media (hover: hover) {
    .custom-scrollbar:has(.custom-scrollbar__track:hover) .custom-scrollbar__thumb {
      background: color-mix(in srgb, var(--color-accent) 85%, var(--color-ink));
    }
  }
</style>

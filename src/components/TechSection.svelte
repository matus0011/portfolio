<script lang="ts">
  import { onMount } from "svelte";
  import { gsap } from "gsap";
  import { ScrollTrigger } from "gsap/ScrollTrigger";

  const leftTech = ["ASTRO", "SVELTE", "TYPESCRIPT", "TAILWIND", "VITE"];
  const rightTech = ["THREE.JS", "GSAP", "GLSL", "WEBGL", "MOTION"];

  let sectionEl: HTMLElement;
  let leftColEl: HTMLDivElement;
  let rightColEl: HTMLDivElement;

  onMount(() => {
    gsap.registerPlugin(ScrollTrigger);
    const cleanupFns: Array<() => void> = [];

    const ctx = gsap.context(() => {
      const travel = () => window.innerHeight * 0.6;

      const tl = gsap.timeline({
        scrollTrigger: {
          trigger: sectionEl,
          start: "top top",
          end: "+=200%",
          pin: true,
          scrub: 1,
          invalidateOnRefresh: true,
        },
      });

      tl.fromTo(
        leftColEl,
        { y: travel() },
        { y: -travel(), ease: "none" },
        0,
      );
      tl.fromTo(
        rightColEl,
        { y: -travel() },
        { y: travel(), ease: "none" },
        0,
      );

      const clamp = gsap.utils.clamp(-10, 10);
      const skew = { value: 0 };
      const setSkew = gsap.quickSetter([leftColEl, rightColEl], "skewY", "deg");

      ScrollTrigger.create({
        trigger: sectionEl,
        start: "top bottom",
        end: "bottom top",
        onUpdate: (self) => {
          skew.value = clamp(self.getVelocity() / -300);
        },
      });

      const decay = () => {
        skew.value = gsap.utils.interpolate(skew.value, 0, 0.08);
        setSkew(skew.value);
      };
      gsap.ticker.add(decay);
      cleanupFns.push(() => gsap.ticker.remove(decay));
    }, sectionEl);

    return () => {
      cleanupFns.forEach((fn) => fn());
      ctx.revert();
    };
  });
</script>

<section
  bind:this={sectionEl}
  class="tech-section relative h-screen w-full overflow-hidden"
>
  <div
    class="absolute inset-0 flex items-center justify-between px-8 md:px-12 pointer-events-none"
  >
    <div
      bind:this={leftColEl}
      class="tech-column flex flex-col gap-2 text-left will-change-transform"
    >
      {#each leftTech as label}
        <span class="tech-item">{label}</span>
      {/each}
    </div>

    <div
      bind:this={rightColEl}
      class="tech-column flex flex-col gap-2 text-right will-change-transform"
    >
      {#each rightTech as label}
        <span class="tech-item">{label}</span>
      {/each}
    </div>
  </div>
</section>

<style>
  .tech-column {
    transform-origin: center center;
  }
  .tech-item {
    font-family: var(--font-display);
    font-size: clamp(2.5rem, 6vw, 7rem);
    font-weight: 900;
    line-height: 0.95;
    letter-spacing: -0.02em;
    text-transform: uppercase;
    color: var(--color-ink, #010101);
  }
</style>

<script lang="ts">
  import { onMount } from "svelte";
  import { gsap } from "gsap";
  import { ScrollTrigger } from "gsap/ScrollTrigger";

  const leftTech = [
    "VUE",
    "NUXT",
    "REACT",
    "REACT NATIVE",
    "EXPO",
    "SVELTE",
    "ASTRO",
    "NODE.JS",
    "LARAVEL",
    "TYPESCRIPT",
    "JAVASCRIPT",
    "HTML5",
    "CSS3",
    "SCSS",
    "TAILWIND",
    "VITE",
    "MOTION",
    "PINIA",
    "REST API",
    "GRAPHQL",
    "WEBSOCKETS",
    "GIT",
    "STORYBOOK",
    "FIGMA",
  ];
  const rightTech = [
    "SSR",
    "SPA",
    "PWA",
    "SEO",
    "RWD",
    "A11Y",
    "FULLSTACK",
    "ARCHITECTURE",
    "END-TO-END",
    "REFACTORING",
    "MIGRATIONS",
    "OPTIMIZATION",
    "CODE REVIEW",
    "DESIGN SYSTEMS",
    "WEB ANIMATIONS",
    "INTERACTIVE UI",
    "AI ENGINEERING",
    "LLM APPS",
    "CHATBOTS",
    "ADMIN PANELS",
    "THREE.JS",
    "GSAP",
    "GLSL",
    "WEBGL",
  ];

  const WAVE_NUMBER = 1.2;
  const WAVE_SPEED = 1;

  let sectionEl: HTMLElement;
  let leftColEl: HTMLDivElement;
  let rightColEl: HTMLDivElement;

  onMount(() => {
    gsap.registerPlugin(ScrollTrigger);
    const cleanupFns: Array<() => void> = [];

    const ctx = gsap.context(() => {
      const leftItems = Array.from(
        leftColEl.querySelectorAll<HTMLElement>(".tech-item"),
      );
      const rightItems = Array.from(
        rightColEl.querySelectorAll<HTMLElement>(".tech-item"),
      );

      const leftSet = leftItems.map((el) =>
        gsap.quickTo(el, "x", { duration: 0.6, ease: "power4.out" }),
      );
      const rightSet = rightItems.map((el) =>
        gsap.quickTo(el, "x", { duration: 0.6, ease: "power4.out" }),
      );

      const leftRange = { minX: 0, maxX: 0 };
      const rightRange = { minX: 0, maxX: 0 };

      function recalc() {
        const maxL = Math.max(...leftItems.map((e) => e.offsetWidth));
        const maxR = Math.max(...rightItems.map((e) => e.offsetWidth));
        leftRange.maxX = leftColEl.offsetWidth - maxL;
        rightRange.maxX = rightColEl.offsetWidth - maxR;
      }
      recalc();

      function wavePos(i: number, prog: number, minX: number, size: number) {
        const phase =
          WAVE_NUMBER * i + WAVE_SPEED * prog * Math.PI * 2 - Math.PI / 2;
        const w = Math.sin(phase);
        const cp = (w + 1) / 2;
        return minX + cp * size;
      }

      function setInitial(
        items: HTMLElement[],
        range: { minX: number; maxX: number },
        mul: number,
      ) {
        const size = range.maxX - range.minX;
        items.forEach((el, i) => {
          gsap.set(el, { x: wavePos(i, 0, range.minX, size) * mul });
        });
      }
      setInitial(leftItems, leftRange, 1);
      setInitial(rightItems, rightRange, -1);

      function findClosest(): number {
        const vc = window.innerHeight / 2;
        let mi = 0;
        let md = Infinity;
        leftItems.forEach((el, i) => {
          const r = el.getBoundingClientRect();
          const c = r.top + r.height / 2;
          const d = Math.abs(c - vc);
          if (d < md) {
            md = d;
            mi = i;
          }
        });
        return mi;
      }

      function updateCol(
        items: HTMLElement[],
        setters: Array<(v: number) => void>,
        range: { minX: number; maxX: number },
        prog: number,
        focused: number,
        mul: number,
      ) {
        const size = range.maxX - range.minX;
        items.forEach((el, i) => {
          setters[i](wavePos(i, prog, range.minX, size) * mul);
          el.classList.toggle("focused", i === focused);
        });
      }

      ScrollTrigger.create({
        trigger: sectionEl,
        start: "top bottom",
        end: "bottom top",
        onUpdate: (self) => {
          const f = findClosest();
          updateCol(leftItems, leftSet, leftRange, self.progress, f, 1);
          updateCol(rightItems, rightSet, rightRange, self.progress, f, -1);
        },
      });

      const onResize = () => recalc();
      window.addEventListener("resize", onResize);
      cleanupFns.push(() =>
        window.removeEventListener("resize", onResize),
      );
    }, sectionEl);

    return () => {
      cleanupFns.forEach((fn) => fn());
      ctx.revert();
    };
  });
</script>

<section bind:this={sectionEl} class="tech-section">
  <div class="dual-wave-wrapper">
    <div bind:this={leftColEl} class="wave-column wave-column-left">
      {#each leftTech as label}
        <div class="tech-item">{label}</div>
      {/each}
    </div>
    <div bind:this={rightColEl} class="wave-column wave-column-right">
      {#each rightTech as label}
        <div class="tech-item">{label}</div>
      {/each}
    </div>
  </div>
</section>

<style>
  .tech-section {
    position: relative;
    width: 100%;
    padding: 40vh 0 60vh;
  }
  .dual-wave-wrapper {
    display: flex;
    width: 100%;
    gap: 22vw;
    padding: 0 6vw;
  }
  .wave-column {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 1.4rem;
    font-family: var(--font-display);
    font-size: clamp(1.4rem, 3.2vw, 2.6rem);
    font-weight: 700;
    line-height: 0.95;
    letter-spacing: -0.01em;
    text-transform: uppercase;
  }
  .wave-column-left {
    align-items: flex-start;
  }
  .wave-column-right {
    align-items: flex-end;
  }
  .tech-item {
    width: max-content;
    color: var(--color-mute, #8a8a8a);
    transition: color 300ms ease-out;
    will-change: transform;
  }
  .tech-item.focused {
    color: var(--color-ink, #010101);
  }
</style>

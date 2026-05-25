<script lang="ts">
  import { onMount } from "svelte";
  import { gsap } from "gsap";
  import { ScrollTrigger } from "gsap/ScrollTrigger";
  import { t, type Lang } from "../locales";

  let { lang = "pl" as Lang }: { lang: Lang } = $props();
  const tr = $derived(t(lang));

  let sectionEl: HTMLElement;
  let turbEl: SVGFETurbulenceElement;
  let displEl: SVGFEDisplacementMapElement;
  let bigLabelEl: HTMLHeadingElement;

  // Unique filter id per instance (in case of multiple instances or HMR)
  const filterId = `about-distort-${Math.random().toString(36).slice(2, 9)}`;

  onMount(() => {
    gsap.registerPlugin(ScrollTrigger);

    const state = { freq: 0.005, scale: 0 };

    const apply = () => {
      if (turbEl) turbEl.setAttribute("baseFrequency", String(state.freq));
      if (displEl) displEl.setAttribute("scale", String(state.scale));
    };
    apply();

    // Bell curve: distortion peaks at progress 0.5, near zero at 0 and 1
    const tween = gsap.to(state, {
      freq: 0.022,
      scale: 28,
      ease: "sine.inOut",
      paused: true,
      onUpdate: apply,
    });

    const st = ScrollTrigger.create({
      trigger: sectionEl,
      start: "top bottom",
      end: "bottom top",
      scrub: 0.6,
      onUpdate: (self) => {
        // Map 0..1 → bell: 1 - |2p - 1|  ⇒ 0 at edges, 1 at middle
        const p = self.progress;
        const bell = 1 - Math.abs(2 * p - 1);
        tween.progress(bell);
      },
    });

    // Fade-in stats on enter
    const statItems = sectionEl.querySelectorAll<HTMLElement>(".about-stat");
    gsap.from(statItems, {
      y: 30,
      opacity: 0,
      stagger: 0.08,
      duration: 0.7,
      ease: "power3.out",
      scrollTrigger: {
        trigger: sectionEl,
        start: "top 70%",
        toggleActions: "play none none reverse",
      },
    });

    return () => {
      st.kill();
      tween.kill();
    };
  });
</script>

<section bind:this={sectionEl} class="about-section">
  <!-- inline defs for the SVG distortion filter -->
  <svg
    class="about-svg-defs"
    aria-hidden="true"
    width="0"
    height="0"
    style="position:absolute;width:0;height:0"
  >
    <defs>
      <filter id={filterId} x="-20%" y="-20%" width="140%" height="140%">
        <feTurbulence
          bind:this={turbEl}
          type="fractalNoise"
          baseFrequency="0.005"
          numOctaves="2"
          seed="3"
          result="turb"
        />
        <feDisplacementMap
          bind:this={displEl}
          in="SourceGraphic"
          in2="turb"
          scale="0"
        />
      </filter>
    </defs>
  </svg>

  <div class="about-inner">
    <h2
      bind:this={bigLabelEl}
      class="about-label"
      style="filter: url(#{filterId})"
    >
      {tr.about.label}
    </h2>

    <div class="about-grid">
      <p class="about-paragraph">{tr.about.paragraph}</p>

      <ul class="about-stats">
        {#each tr.about.stats as stat}
          <li class="about-stat">
            <span class="stat-value">{stat.value}</span>
            <span class="stat-label">{stat.label}</span>
          </li>
        {/each}
      </ul>
    </div>
  </div>
</section>

<style>
  .about-section {
    position: relative;
    width: 100%;
    padding: 12vh 6vw 14vh;
    z-index: 5;
  }
  .about-inner {
    max-width: 1400px;
    margin: 0 auto;
  }
  .about-label {
    font-family: var(--font-display);
    font-size: clamp(6rem, 22vw, 22rem);
    font-weight: 900;
    line-height: 0.85;
    letter-spacing: -0.04em;
    color: var(--color-ink, #010101);
    margin: 0 0 6vh;
    user-select: none;
    will-change: filter;
  }
  .about-grid {
    display: grid;
    grid-template-columns: 1.3fr 1fr;
    gap: 4vw;
    align-items: start;
  }
  @media (max-width: 900px) {
    .about-grid {
      grid-template-columns: 1fr;
      gap: 6vh;
    }
  }
  .about-paragraph {
    font-family: var(--font-display);
    font-size: clamp(1.1rem, 1.6vw, 1.5rem);
    line-height: 1.45;
    color: var(--color-ink, #010101);
    margin: 0;
    max-width: 50ch;
  }
  .about-stats {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    gap: 1.6rem;
  }
  .about-stat {
    display: flex;
    align-items: baseline;
    gap: 1.2rem;
    padding-top: 1.2rem;
    border-top: 1px solid var(--color-line, #c9c9c9);
  }
  .stat-value {
    font-family: var(--font-display);
    font-size: clamp(2rem, 3.5vw, 3rem);
    font-weight: 900;
    line-height: 1;
    color: var(--color-accent, #fe5030);
    min-width: 4ch;
  }
  .stat-label {
    font-size: 0.95rem;
    line-height: 1.4;
    color: var(--color-ink, #010101);
    opacity: 0.75;
  }
</style>

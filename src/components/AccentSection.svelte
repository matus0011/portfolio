<script lang="ts">
  import { onMount } from "svelte";
  import gsap from "gsap";
  import { ScrollTrigger } from "gsap/ScrollTrigger";
  import MarqueeText from "./MarqueeText.svelte";
  import { t, type Lang } from "../locales";
  import { techStack } from "../data/technologies";

  let { initialLang = "pl" as Lang } = $props();
  const lang = $derived(initialLang as Lang);
  const tr = $derived(t(lang));

  let section: HTMLElement;
  let pinWrap: HTMLElement;

  onMount(() => {
    gsap.registerPlugin(ScrollTrigger);

    const descEls = Array.from(
      pinWrap.querySelectorAll<HTMLElement>(".tech-desc-wrap"),
    );
    const visualEls = Array.from(
      pinWrap.querySelectorAll<HTMLElement>(".tech-visual"),
    );

    // All rows expanded by default — they collapse one by one during pin.
    descEls.forEach((el) => {
      gsap.set(el, { height: "auto", opacity: 1, marginTop: "1rem" });
    });
    visualEls.forEach((el) => {
      gsap.set(el, { autoAlpha: 1, height: "10rem" });
    });

    let tl: gsap.core.Timeline | null = null;
    let pollId: number | null = null;
    let didSetup = false;

    const setup = () => {
      if (didSetup) return;
      didSetup = true;
      const n = techStack.length;
      const stepPx = 260; // scroll distance per row collapse
      tl = gsap.timeline({
        scrollTrigger: {
          trigger: pinWrap,
          start: "top top",
          end: () => "+=" + n * stepPx,
          pin: true,
          anticipatePin: 1,
          scrub: 0.4,
          invalidateOnRefresh: true,
          markers: true,
        },
      });

      for (let i = 0; i < n; i++) {
        const desc = descEls[i];
        const vis = visualEls[i];
        if (desc) {
          tl.to(
            desc,
            {
              height: 0,
              opacity: 0,
              marginTop: 0,
              duration: 1,
              ease: "power2.in",
            },
            i,
          );
        }
        if (vis) {
          tl.to(
            vis,
            {
              height: 0,
              autoAlpha: 0,
              duration: 1,
              ease: "power2.in",
            },
            i,
          );
        }
      }
      ScrollTrigger.refresh();
    };

    if ((window as any).smoother) {
      setup();
    } else {
      pollId = window.setInterval(() => {
        if ((window as any).smoother) {
          if (pollId !== null) window.clearInterval(pollId);
          pollId = null;
          setup();
        }
      }, 50);
    }

    return () => {
      if (pollId !== null) window.clearInterval(pollId);
      tl?.scrollTrigger?.kill();
      tl?.kill();
    };
  });
</script>

<section bind:this={section} class="accent-section">
  <div class="stripes-clip" aria-hidden="true">
    <div class="stripe stripe-1" data-speed="0.6"></div>
    <div class="stripe stripe-2" data-speed="0.75"></div>
    <div class="stripe stripe-3" data-speed="1.25"></div>
    <div class="stripe stripe-4" data-speed="1.4"></div>
  </div>

  <div class="marquee-slot">
    <MarqueeText label={tr.labels.techStack} />
  </div>

  <div bind:this={pinWrap} class="tech-pin">
    <div class="tech-divider tech-divider--top" aria-hidden="true"></div>

    <div class="tech-accordion">
      {#each techStack as item, i}
        <article class="tech-row">
          <span class="tech-no">T/{String(i + 1).padStart(3, "0")}</span>
          <div class="tech-body">
            <h3 class="tech-name">{item.name}</h3>
            <div class="tech-desc-wrap">
              <p class="tech-desc">{lang === "pl" ? item.descPl : item.descEn}</p>
            </div>
          </div>
          <div class="tech-visual-cell">
            <div class="tech-visual" aria-hidden="true"></div>
          </div>
        </article>
        <div class="tech-divider" aria-hidden="true"></div>
      {/each}
    </div>
  </div>
</section>

<style>
  .accent-section {
    position: relative;
    width: 100%;
    background-color: var(--color-accent);
    padding-top: calc((100vh - 20rem) / 4);
  }

  .stripes-clip {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    overflow: hidden;
    pointer-events: none;
  }

  .stripe {
    position: absolute;
    top: -20%;
    bottom: -20%;
    width: 1px;
    background-color: var(--color-ink);
    opacity: 0.25;
    transform: translateX(-50%);
    pointer-events: none;
  }

  .stripe-1 { left: calc(50% - 450px); }
  .stripe-2 { left: calc(50% - 150px); }
  .stripe-3 { left: calc(50% + 150px); }
  .stripe-4 { left: calc(50% + 450px); }

  .marquee-slot {
    width: 100%;
  }

  .tech-pin {
    position: relative;
    width: 100%;
    height: 100vh;
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    padding-top: 4rem;
    overflow: hidden;
  }

  .tech-divider {
    width: 100%;
    height: 0;
    border-top: 1px dashed var(--color-ink);
    opacity: 0.55;
  }

  .tech-divider--top {
    opacity: 1;
    border-top-width: 1.5px;
  }

  .tech-accordion {
    width: 100%;
    display: flex;
    flex-direction: column;
    will-change: transform;
  }

  .tech-row {
    display: grid;
    grid-template-columns: 6rem 1fr 12rem;
    align-items: start;
    gap: 2rem;
    padding: 1.25rem 2.5rem;
    color: var(--color-ink);
    transition: background-color 0.4s ease;
  }

  .tech-no {
    font-family: var(--font-mono, ui-monospace, SFMono-Regular, Menlo, monospace);
    font-size: 0.8125rem;
    letter-spacing: 0.04em;
    opacity: 0.7;
    padding-top: 0.6rem;
  }

  .tech-body {
    min-width: 0;
  }

  .tech-name {
    font-family: var(--font-display, "Mona Sans"), system-ui, sans-serif;
    font-size: clamp(1.5rem, 2.6vw, 2.5rem);
    font-weight: 500;
    line-height: 1;
    letter-spacing: -0.02em;
    margin: 0;
  }

  .tech-desc-wrap {
    overflow: hidden;
  }

  .tech-desc {
    font-family: var(--font-display, "Mona Sans"), system-ui, sans-serif;
    font-size: clamp(0.9rem, 1vw, 1.0625rem);
    line-height: 1.45;
    max-width: 42rem;
    margin: 0;
  }

  .tech-visual-cell {
    display: flex;
    align-items: flex-start;
    justify-content: flex-end;
    min-height: 0;
  }

  .tech-visual {
    width: 100%;
    max-width: 12rem;
    background-color: rgba(0, 0, 0, 0.08);
    border: 1px dashed rgba(0, 0, 0, 0.35);
    overflow: hidden;
  }

  @media (max-width: 768px) {
    .tech-row {
      grid-template-columns: 3.5rem 1fr;
      gap: 1rem;
      padding: 1rem 1.25rem;
    }
    .tech-visual-cell {
      display: none;
    }
  }
</style>

<script lang="ts">
  import { onMount } from "svelte";
  import gsap from "gsap";
  import { ScrollTrigger } from "gsap/ScrollTrigger";
  import MarqueeText from "./MarqueeText.svelte";
  import Outro from "./Outro.svelte";
  import CtaSection from "./CtaSection.svelte";
  import { t, type Lang } from "../locales";
  import { techStack } from "../data/technologies";
  import { scrambleAsciiOnce } from "../utils/scrambleAscii";

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
      gsap.set(el, { autoAlpha: 1, height: "20rem" });
    });

    // ASCII boxes — scramble runs on hover (per visual cell).
    const visualCells = Array.from(
      pinWrap.querySelectorAll<HTMLElement>(".tech-visual-cell"),
    );
    const removeHover: Array<() => void> = [];
    visualCells.forEach((cell, i) => {
      const asciiEl = cell.querySelector<HTMLElement>(".tech-ascii");
      const text = techStack[i]?.ascii;
      if (!asciiEl || !text) return;
      let cancel: (() => void) | null = null;
      const onEnter = () => {
        cancel?.();
        cancel = scrambleAsciiOnce(asciiEl, text);
      };
      cell.addEventListener("mouseenter", onEnter);
      removeHover.push(() => {
        cell.removeEventListener("mouseenter", onEnter);
        cancel?.();
      });
    });

    let tl: gsap.core.Timeline | null = null;
    let didSetup = false;

    const setup = () => {
      if (didSetup) return;
      didSetup = true;
      const n = techStack.length;
      const stepPx = 300;
      const overlap = 0.55;
      const duration = 1.2;
      const topDivider = pinWrap.querySelector<HTMLElement>(
        ".tech-divider--top",
      );
      // Dividers AFTER each row (skip the top one).
      const dividerEls = Array.from(
        pinWrap.querySelectorAll<HTMLElement>(
          ".tech-divider:not(.tech-divider--top)",
        ),
      );
      const rowEls = Array.from(
        pinWrap.querySelectorAll<HTMLElement>(".tech-row"),
      );
      const outroEl = pinWrap.querySelector<HTMLElement>(".tech-outro");
      const ctaEl = pinWrap.querySelector<HTMLElement>(".cta-screen");
      const fwrdEl = pinWrap.querySelector<HTMLElement>(".cta-fwrd");

      // Timeline phases (in timeline-seconds) — everything plays while the
      // section is pinned (page frozen), so each screen slides up over the
      // previous, frozen one:
      //   0 .. collapseEnd        → rows collapse one by one
      //   collapseEnd ..          → experience screen slides up
      //   .. expEnd               → CTA screen slides up over experience
      //   .. ctaEnd               → "FWRD" drifts to the right
      //   + holdDur               → hold fully covered to the very end
      const collapseEnd = (n - 2) * overlap + duration;
      const wipeDur = 1.8;
      const ctaDur = 1.8;
      const fwrdDur = 1.5;
      // Long hold so the last screen finishes well before the very end of the
      // pin — the last sliver of pin scroll is slightly unreachable, so
      // completing early guarantees full coverage at the real bottom.
      const holdDur = 1.1;
      const expEnd = collapseEnd + wipeDur;
      const ctaEnd = expEnd + ctaDur;
      const total = ctaEnd + fwrdDur + holdDur;
      // Keep the collapse mapped to its original scroll length; the later
      // phases add proportional extra pinned scroll on top.
      const endPx = Math.round((n * stepPx * total) / collapseEnd);

      tl = gsap.timeline({
        scrollTrigger: {
          trigger: topDivider ?? pinWrap,
          start: "top+=2 top",
          end: () => "+=" + endPx,
          pin: pinWrap,
          anticipatePin: 1,
          scrub: 0.8,
          invalidateOnRefresh: true,
        },
      });

      // Each row keeps its full layout height. We:
      //   - clip the row visually from the bottom (clip-path) so only the
      //     top "headerArea" stays visible — heading on the left + the
      //     top part of the visual box on the right
      //   - slide the divider that follows it (and everything below) up by
      //     the same amount, so the next row rises from below and ends up
      //     right under the clip line of the previous row
      // Result: each new tab visually overlaps the previous one from below,
      // dashed divider lines and vertical stripes stay visible, the desc
      // text simply gets covered as the next row rises (no early height
      // collapse).
      const headerArea = 80;
      // Stop before the last row: it never collapses and stays fully expanded.
      for (let i = 0; i < n - 1; i++) {
        const row = rowEls[i];
        const divider = dividerEls[i];
        const startAt = i * overlap;
        if (row) {
          tl.to(
            row,
            {
              clipPath: () =>
                `inset(0 0 ${Math.max(0, row.offsetHeight - headerArea)}px 0)`,
              duration,
              ease: "power1.inOut",
            },
            startAt,
          );
        }
        if (divider && row) {
          tl.to(
            divider,
            {
              marginTop: () =>
                -Math.max(0, row.offsetHeight - headerArea),
              duration,
              ease: "power1.inOut",
            },
            startAt,
          );
        }
      }

      // Experience screen slides up from the bottom right after the last row
      // collapses, while the section is still pinned (page frozen).
      if (outroEl) {
        gsap.set(outroEl, { yPercent: 100 });
        tl.fromTo(
          outroEl,
          { yPercent: 100 },
          { yPercent: 0, ease: "none", duration: wipeDur },
          collapseEnd,
        );
      }

      // CTA screen slides up over the frozen experience screen.
      if (ctaEl) {
        gsap.set(ctaEl, { yPercent: 100 });
        tl.fromTo(
          ctaEl,
          { yPercent: 100 },
          { yPercent: 0, ease: "none", duration: ctaDur },
          expEnd,
        );
      }

      // Once the CTA covers, "FWRD" drifts to the right as scrolling continues.
      if (fwrdEl) {
        gsap.set(fwrdEl, { x: () => -0.1 * window.innerWidth });
        tl.fromTo(
          fwrdEl,
          { x: () => -0.1 * window.innerWidth },
          { x: () => 0.18 * window.innerWidth, ease: "none", duration: fwrdDur },
          ctaEnd,
        );
      }

      // Hold fully covered to the very end of the pin.
      tl.to({}, { duration: holdDur }, total - holdDur);

      ScrollTrigger.refresh();
    };

    if (window.smoother) {
      setup();
    } else {
      window.addEventListener("smootherReady", setup, { once: true });
    }

    return () => {
      window.removeEventListener("smootherReady", setup);
      tl?.scrollTrigger?.kill();
      tl?.kill();
      removeHover.forEach((d) => d());
    };
  });
</script>

<section id="tech" bind:this={section} class="accent-section">
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
            <div class="tech-visual" aria-hidden="true">
              <pre class="tech-ascii">{item.ascii}</pre>
            </div>
          </div>
        </article>
        {#if i < techStack.length - 1}
          <div class="tech-divider" aria-hidden="true"></div>
        {/if}
      {/each}
    </div>

    <Outro {lang} />
    <CtaSection {lang} />
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
    padding-bottom: 100px;
  }

  .tech-pin {
    position: relative;
    width: 100%;
    /* +5rem covers the offset created by pinning on the inner top divider
       (which sits below the 4rem padding), so the outro panel can fill the
       whole viewport with no gap at the bottom. */
    height: calc(100vh + 5rem);
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    padding-top: 4rem;
    overflow: hidden;
  }

  .tech-divider {
    width: 98%;
    height: 0;
    margin: 0 auto;
    border-top: 1px dashed var(--color-bg);
  }

  .tech-divider--top {
    border-top-width: 1.5px;
  }

  .tech-accordion {
    width: 100%;
    display: flex;
    flex-direction: column;
    will-change: transform;
  }

  .tech-row {
    position: relative;
    display: grid;
    grid-template-columns: 6rem 1fr 24rem;
    align-items: start;
    gap: 2rem;
    padding: 1.25rem 2.5rem;
    color: var(--color-ink);
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
    padding-left: 50px;
    padding-top: 50px;
  }

  .tech-visual-cell {
    display: flex;
    align-items: flex-start;
    justify-content: flex-end;
    min-height: 0;
  }

  .tech-visual {
    width: 100%;
    max-width: 24rem;
    background-color: var(--color-bg);
    border-radius: 8px;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    transition:
      background-color 0.25s ease,
      color 0.25s ease;
    cursor: pointer;
  }

  .tech-visual:hover {
    background-color: var(--color-accent);
  }

  .tech-ascii {
    font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;
    font-size: 22px;
    line-height: 1.25;
    white-space: pre;
    color: var(--color-accent);
    margin: 0;
    padding: 12px;
    letter-spacing: 0.02em;
    user-select: none;
    transition: color 0.25s ease;
  }

  .tech-visual:hover .tech-ascii {
    color: var(--color-bg);
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

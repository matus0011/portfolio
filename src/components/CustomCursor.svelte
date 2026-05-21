<script lang="ts">
  import { onMount } from "svelte";
  import gsap from "gsap";

  let dotWrapperEl: HTMLDivElement;
  let dotEl:        HTMLDivElement;
  let svgWrapperEl: HTMLDivElement;
  let pathEl:       SVGPathElement;

  /* ── generowanie gładkiej falistej ścieżki ── */
  function smoothClosedPath(pts: [number, number][]): string {
    const n = pts.length;
    let d = `M ${pts[0][0].toFixed(2)} ${pts[0][1].toFixed(2)}`;
    for (let i = 0; i < n; i++) {
      const p0 = pts[(i - 1 + n) % n];
      const p1 = pts[i];
      const p2 = pts[(i + 1) % n];
      const p3 = pts[(i + 2) % n];
      const cp1x = p1[0] + (p2[0] - p0[0]) / 6;
      const cp1y = p1[1] + (p2[1] - p0[1]) / 6;
      const cp2x = p2[0] - (p3[0] - p1[0]) / 6;
      const cp2y = p2[1] - (p3[1] - p1[1]) / 6;
      d += ` C ${cp1x.toFixed(2)} ${cp1y.toFixed(2)}, ${cp2x.toFixed(2)} ${cp2y.toFixed(2)}, ${p2[0].toFixed(2)} ${p2[1].toFixed(2)}`;
    }
    return d + " Z";
  }

  function getWavyPath(cx: number, cy: number, r: number, amp: number, t: number, n = 14): string {
    const pts: [number, number][] = [];
    for (let i = 0; i < n; i++) {
      const angle = (i / n) * Math.PI * 2 - Math.PI / 2;
      const wave =
        Math.sin(i * 2.3 + t * 1.6) * amp * 0.55 +
        Math.sin(i * 3.9 - t * 1.0) * amp * 0.45;
      const pr = r + wave;
      pts.push([cx + pr * Math.cos(angle), cy + pr * Math.sin(angle)]);
    }
    return smoothClosedPath(pts);
  }

  onMount(() => {
    const SIZE = 160;
    const CX = SIZE / 2;
    const CY = SIZE / 2;

    /* tweenowany stan (GSAP pisze do tego obiektu, rAF go czyta) */
    const state = { r: 18, amp: 0 };

    let mouseX = -200, mouseY = -200;
    let ringX  = -200, ringY  = -200;
    let visible = false;
    let time = 0;
    let raf: number;

    const tick = () => {
      time += 0.025;

      ringX += (mouseX - ringX) * 0.12;
      ringY += (mouseY - ringY) * 0.12;

      dotWrapperEl.style.transform = `translate(${mouseX - 4}px, ${mouseY - 4}px)`;
      svgWrapperEl.style.transform = `translate(${ringX - CX}px, ${ringY - CY}px)`;

      pathEl.setAttribute("d", getWavyPath(CX, CY, state.r, state.amp, time));

      raf = requestAnimationFrame(tick);
    };
    raf = requestAnimationFrame(tick);

    /* ── śledzenie myszy ── */
    const onMove = (e: MouseEvent) => {
      mouseX = e.clientX;
      mouseY = e.clientY;
      if (!visible) {
        ringX = mouseX; ringY = mouseY;
        visible = true;
        gsap.to([dotWrapperEl, svgWrapperEl], { opacity: 1, duration: 0.35 });
      }
    };

    /* ── hover — event delegation na całym dokumencie ── */
    const SELECTORS = "a, button, [role='button'], input, textarea, label";

    const onEnter = () => {
      gsap.to(state, { r: 36, amp: 7, duration: 0.5, ease: "back.out(1.5)" });
      gsap.to(dotEl, { scale: 0.35, duration: 0.25, ease: "power2.out" });
    };

    const onLeave = () => {
      gsap.to(state, { r: 18, amp: 0, duration: 0.45, ease: "power3.out" });
      gsap.to(dotEl, { scale: 1,    duration: 0.25, ease: "power2.out" });
    };

    const onOver = (e: MouseEvent) => {
      if ((e.target as HTMLElement).closest(SELECTORS)) onEnter();
    };
    const onOut = (e: MouseEvent) => {
      const from = e.target as HTMLElement;
      const to   = e.relatedTarget as HTMLElement | null;
      if (from.closest(SELECTORS) && !to?.closest(SELECTORS)) onLeave();
    };

    document.addEventListener("mouseover", onOver);
    document.addEventListener("mouseout",  onOut);

    /* ── kliknięcie ── */
    const onDown = () => {
      gsap.to(state, { r: 12, amp: 3, duration: 0.15, ease: "power2.in" });
      gsap.to(dotEl, { scale: 0.5,   duration: 0.12, ease: "power2.in" });
    };
    const onUp = () => {
      gsap.to(state, { r: 18, amp: 0, duration: 0.5, ease: "elastic.out(1,0.5)" });
      gsap.to(dotEl, { scale: 1,     duration: 0.3,  ease: "back.out(2)" });
    };

    /* ── opuszczenie okna ── */
    const onDocLeave = () => {
      visible = false;
      gsap.to([dotWrapperEl, svgWrapperEl], { opacity: 0, duration: 0.3 });
    };

    window.addEventListener("mousemove",  onMove);
    window.addEventListener("mousedown",  onDown);
    window.addEventListener("mouseup",    onUp);
    document.documentElement.addEventListener("mouseleave", onDocLeave);

    return () => {
      cancelAnimationFrame(raf);
      window.removeEventListener("mousemove",  onMove);
      window.removeEventListener("mousedown",  onDown);
      window.removeEventListener("mouseup",    onUp);
      document.documentElement.removeEventListener("mouseleave", onDocLeave);
      document.removeEventListener("mouseover", onOver);
      document.removeEventListener("mouseout",  onOut);
    };
  });
</script>

<!-- Dot -->
<div class="c-dot-wrapper" bind:this={dotWrapperEl}>
  <div class="c-dot" bind:this={dotEl}></div>
</div>

<!-- Wavy ring -->
<div class="c-svg-wrapper" bind:this={svgWrapperEl}>
  <svg width="160" height="160" xmlns="http://www.w3.org/2000/svg">
    <path
      bind:this={pathEl}
      fill="none"
      stroke="rgba(254,80,48,0.9)"
      stroke-width="1.5"
      stroke-linejoin="round"
    />
  </svg>
</div>

<style>
  .c-dot-wrapper,
  .c-svg-wrapper {
    pointer-events: none;
    position: fixed;
    top: 0;
    left: 0;
    will-change: transform;
    opacity: 0;
  }

  .c-dot-wrapper {
    z-index: 2147483646;
  }

  .c-svg-wrapper {
    z-index: 2147483647;
  }

  .c-dot {
    width: 8px;
    height: 8px;
    background: #fe5030;
    border-radius: 50%;
    pointer-events: none;
  }

  svg {
    overflow: visible;
    display: block;
  }
</style>

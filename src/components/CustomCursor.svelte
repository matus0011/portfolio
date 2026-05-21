<script lang="ts">
  import { onMount } from "svelte";
  import gsap from "gsap";

  let dotWrapperEl: HTMLDivElement;  // obsługuje tylko pozycję
  let dotEl:        HTMLDivElement;  // obsługuje tylko scale / GSAP
  let wrapperEl:    HTMLDivElement;  // obsługuje tylko pozycję
  let ringEl:       HTMLDivElement;  // obsługuje tylko kształt / GSAP

  onMount(() => {
    let mouseX = -200, mouseY = -200;
    let ringX  = -200, ringY  = -200;
    let visible = false;
    let raf: number;

    /* ── pętla pozycji (tylko translate na wrapperze) ── */
    const tick = () => {
      ringX += (mouseX - ringX) * 0.13;
      ringY += (mouseY - ringY) * 0.13;

      dotWrapperEl.style.transform = `translate(${mouseX - 4}px, ${mouseY - 4}px)`;
      wrapperEl.style.transform = `translate(${ringX - 18}px, ${ringY - 18}px)`;

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
        gsap.to([dotWrapperEl, ringEl], { opacity: 1, duration: 0.3 });
      }
    };

    /* ── hover: morfing koła w diament ── */
    const SELECTORS = "a, button, [role='button'], input, textarea, label";

    const onEnter = () => {
      gsap.to(ringEl, {
        width: 52,
        height: 52,
        borderRadius: "6px",
        rotate: 45,
        borderColor: "rgba(254,80,48,1)",
        backgroundColor: "rgba(254,80,48,0.10)",
        duration: 0.35,
        ease: "back.out(1.7)",
      });
      gsap.to(dotEl, { scale: 0.4, duration: 0.25, ease: "power2.out" });
    };

    const onLeave = () => {
      gsap.to(ringEl, {
        width: 36,
        height: 36,
        borderRadius: "50%",
        rotate: 0,
        borderColor: "rgba(254,80,48,0.85)",
        backgroundColor: "rgba(0,0,0,0)",
        duration: 0.35,
        ease: "power3.out",
      });
      gsap.to(dotEl, { scale: 1, duration: 0.25, ease: "power2.out" });
    };

    const attachHover = () => {
      document.querySelectorAll<HTMLElement>(SELECTORS).forEach((el) => {
        if (el.dataset.cursorBound) return;
        el.dataset.cursorBound = "1";
        el.addEventListener("mouseenter", onEnter);
        el.addEventListener("mouseleave", onLeave);
      });
    };
    attachHover();
    const observer = new MutationObserver(attachHover);
    observer.observe(document.body, { childList: true, subtree: true });

    /* ── kliknięcie ── */
    const onDown = () => {
      gsap.to(ringEl, { scale: 0.75, duration: 0.12, ease: "power2.in" });
      gsap.to(dotEl,  { scale: 0.5,  duration: 0.12, ease: "power2.in" });
    };
    const onUp = () => {
      gsap.to(ringEl, { scale: 1, duration: 0.4, ease: "elastic.out(1,0.5)" });
      gsap.to(dotEl,  { scale: 1, duration: 0.3, ease: "back.out(2)" });
    };

    /* ── opuszczenie okna ── */
    const onDocLeave = () => {
      visible = false;
      gsap.to([dotWrapperEl, ringEl], { opacity: 0, duration: 0.3 });
    };

    window.addEventListener("mousemove",  onMove);
    window.addEventListener("mousedown",  onDown);
    window.addEventListener("mouseup",    onUp);
    document.documentElement.addEventListener("mouseleave", onDocLeave);

    return () => {
      cancelAnimationFrame(raf);
      observer.disconnect();
      window.removeEventListener("mousemove",  onMove);
      window.removeEventListener("mousedown",  onDown);
      window.removeEventListener("mouseup",    onUp);
      document.documentElement.removeEventListener("mouseleave", onDocLeave);
    };
  });
</script>

<!-- Dot wrapper (pozycja) + inner dot (GSAP scale) -->
<div class="c-dot-wrapper" bind:this={dotWrapperEl}>
  <div class="c-dot" bind:this={dotEl}></div>
</div>

<!-- Ring wrapper (pozycja) + inner ring (GSAP kształt) -->
<div class="c-ring-wrapper" bind:this={wrapperEl}>
  <div class="c-ring" bind:this={ringEl}></div>
</div>

<style>
  .c-dot-wrapper,
  .c-ring-wrapper,
  .c-dot,
  .c-ring {
    pointer-events: none;
  }

  .c-dot-wrapper,
  .c-ring-wrapper {
    position: fixed;
    top: 0;
    left: 0;
    will-change: transform;
  }

  .c-dot-wrapper {
    z-index: 2147483646;
    opacity: 0;
  }

  .c-dot {
    width: 8px;
    height: 8px;
    background: #fe5030;
    border-radius: 50%;
  }

  .c-ring-wrapper {
    z-index: 2147483647;
  }

  .c-ring {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    border: 1.5px solid rgba(254, 80, 48, 0.85);
    background: transparent;
    opacity: 0;
  }
</style>

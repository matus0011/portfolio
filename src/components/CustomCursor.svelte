<script lang="ts">
  import { onMount } from "svelte";

  let dotEl: HTMLDivElement;
  let ringEl: HTMLDivElement;

  onMount(() => {
    let mouseX = window.innerWidth / 2;
    let mouseY = window.innerHeight / 2;
    let ringX  = mouseX;
    let ringY  = mouseY;

    let isHovering = false;
    let isClicking = false;
    let isVisible  = false;
    let rafId: number;

    /* ── helpers ── */
    const setVar = (el: HTMLElement, x: number, y: number) => {
      el.style.transform = `translate(${x}px, ${y}px)`;
    };

    /* ── animation loop ── */
    const tick = () => {
      const ease = isHovering ? 0.08 : 0.14;
      ringX += (mouseX - ringX) * ease;
      ringY += (mouseY - ringY) * ease;

      setVar(dotEl,  mouseX - 4,  mouseY - 4);
      setVar(ringEl, ringX  - 20, ringY  - 20);

      rafId = requestAnimationFrame(tick);
    };
    rafId = requestAnimationFrame(tick);

    /* ── mouse tracking ── */
    const onMove = (e: MouseEvent) => {
      mouseX = e.clientX;
      mouseY = e.clientY;
      if (!isVisible) {
        ringX = mouseX;
        ringY = mouseY;
        isVisible = true;
        dotEl.style.opacity  = "1";
        ringEl.style.opacity = "1";
      }
    };

    /* ── hover detection ── */
    const SELECTORS = "a, button, [role='button'], input, textarea, select, label, [data-cursor]";

    const attachHover = () => {
      document.querySelectorAll<HTMLElement>(SELECTORS).forEach((el) => {
        if (el.dataset.cursorBound) return;
        el.dataset.cursorBound = "1";
        el.addEventListener("mouseenter", () => {
          isHovering = true;
          ringEl.classList.add("is-hovering");
        });
        el.addEventListener("mouseleave", () => {
          isHovering = false;
          ringEl.classList.remove("is-hovering");
        });
      });
    };
    attachHover();

    const observer = new MutationObserver(attachHover);
    observer.observe(document.body, { childList: true, subtree: true });

    /* ── click ── */
    const onDown = () => {
      isClicking = true;
      dotEl.classList.add("is-clicking");
      ringEl.classList.add("is-clicking");
    };
    const onUp = () => {
      isClicking = false;
      dotEl.classList.remove("is-clicking");
      ringEl.classList.remove("is-clicking");
    };

    /* ── leave / enter window ── */
    const onLeave = () => {
      dotEl.style.opacity  = "0";
      ringEl.style.opacity = "0";
      isVisible = false;
    };
    const onEnterDoc = () => { isVisible = false; };

    window.addEventListener("mousemove",  onMove);
    window.addEventListener("mousedown",  onDown);
    window.addEventListener("mouseup",    onUp);
    document.documentElement.addEventListener("mouseleave", onLeave);
    document.documentElement.addEventListener("mouseenter", onEnterDoc);

    return () => {
      cancelAnimationFrame(rafId);
      observer.disconnect();
      window.removeEventListener("mousemove",  onMove);
      window.removeEventListener("mousedown",  onDown);
      window.removeEventListener("mouseup",    onUp);
      document.documentElement.removeEventListener("mouseleave", onLeave);
      document.documentElement.removeEventListener("mouseenter", onEnterDoc);
    };
  });
</script>

<div class="c-dot"  bind:this={dotEl}></div>
<div class="c-ring" bind:this={ringEl}></div>

<style>
  .c-dot,
  .c-ring {
    pointer-events: none;
    position: fixed;
    top: 0;
    left: 0;
    border-radius: 50%;
    opacity: 0;
    will-change: transform;
  }

  .c-dot {
    z-index: 2147483646;
  }

  .c-ring {
    z-index: 2147483647;
  }

  /* ── dot ── */
  .c-dot {
    width:  8px;
    height: 8px;
    background: #fe5030;
    transition: opacity .25s, width .15s, height .15s, background .2s;
  }
  .c-dot.is-clicking {
    width:  5px;
    height: 5px;
    background: #c93010;
  }

  /* ── ring ── */
  .c-ring {
    width:  40px;
    height: 40px;
    border: 1.5px solid rgba(254, 80, 48, 0.9);
    transition:
      opacity        .25s,
      width          .4s  cubic-bezier(.25, .46, .45, .94),
      height         .4s  cubic-bezier(.25, .46, .45, .94),
      margin         .4s  cubic-bezier(.25, .46, .45, .94),
      border-color   .35s ease,
      background     .35s ease;
  }

  .c-ring.is-hovering {
    width:   64px;
    height:  64px;
    margin: -12px;
    border-color: rgba(254, 80, 48, 1);
    background:   rgba(254, 80, 48, 0.08);
  }

  .c-ring.is-clicking {
    width:   28px;
    height:  28px;
    margin:   6px;
    background: rgba(254, 80, 48, 0.25);
    border-color: rgba(254, 80, 48, 1);
  }
</style>

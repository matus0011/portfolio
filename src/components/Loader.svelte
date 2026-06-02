<script lang="ts">
  import { onMount } from "svelte";
  import gsap from "gsap";
  import { ScrollTrigger } from "gsap/ScrollTrigger";
  import { CHARS } from "../utils/scramble";

  let {
    freeze = false,
    freezeAt = 60,
    minDuration = 0,
  }: { freeze?: boolean; freezeAt?: number; minDuration?: number } = $props();

  let progress = $state(freeze ? freezeAt : 0);
  let displayed = $state("000");
  let hidden = $state(false);

  let canvasEl: HTMLCanvasElement | undefined = $state();
  let loaderEl: HTMLDivElement | undefined = $state();

  const SCRAMBLE_INTERVAL_MS = 65;
  const GLITCH_RATE = 0.22;
  const POST_CHANGE_SCRAMBLE_MS = 220;

  const ROWS = 6;
  const COLS = 36;
  const ROW_ACTIVE_CHANCE = 0.7;
  const CELL_FILL_CHANCE = 0.55;
  const SHUFFLE_INTERVAL_MS = 130;
  const SHUFFLE_RATE = 0.12;
  const SYMBOLS = ["{", "}", "[", "]", "(", ")", "=>", "&&", "||", "!", "?", "=", ";", "+", "-", "*", "/", "<", ">", "$", "_", "fn"];

  type Cell = string | null;

  const activeRows: boolean[] = [];
  const grid: Cell[][] = [];

  function pickSymbol() {
    return SYMBOLS[Math.floor(Math.random() * SYMBOLS.length)];
  }

  function generateGrid() {
    activeRows.length = 0;
    for (let r = 0; r < ROWS; r++) activeRows.push(Math.random() < ROW_ACTIVE_CHANCE);
    while (activeRows.filter(Boolean).length < Math.min(4, ROWS)) {
      activeRows[Math.floor(Math.random() * ROWS)] = true;
    }
    grid.length = 0;
    for (let r = 0; r < ROWS; r++) {
      const row: Cell[] = [];
      for (let c = 0; c < COLS; c++) {
        row.push(activeRows[r] && Math.random() < CELL_FILL_CHANCE ? pickSymbol() : null);
      }
      grid.push(row);
    }
  }

  function shuffleSymbols() {
    for (let r = 0; r < ROWS; r++) {
      if (!activeRows[r]) continue;
      const row = grid[r];
      for (let c = 0; c < COLS; c++) {
        if (row[c] != null && Math.random() < SHUFFLE_RATE) row[c] = pickSymbol();
      }
    }
  }

  onMount(() => {
    document.body.style.overflow = "hidden";
    generateGrid();

    const lastReal = ["0", "0", "0"];
    const scrambleUntil = [0, 0, 0];

    const scramble = setInterval(() => {
      const now = performance.now();
      const real = Math.round(progress).toString().padStart(3, "0");
      let out = "";
      for (let i = 0; i < 3; i++) {
        if (real[i] !== lastReal[i]) {
          scrambleUntil[i] = now + POST_CHANGE_SCRAMBLE_MS;
          lastReal[i] = real[i];
        }
        const glitching = now < scrambleUntil[i] || Math.random() < GLITCH_RATE;
        out += glitching ? CHARS[Math.floor(Math.random() * CHARS.length)] : real[i];
      }
      displayed = out;
    }, SCRAMBLE_INTERVAL_MS);

    const canvas = canvasEl!;
    const ctx = canvas.getContext("2d")!;
    const dpr = window.devicePixelRatio || 1;

    let cssW = 0;
    let cssH = 0;

    function resize() {
      const rect = canvas.getBoundingClientRect();
      cssW = rect.width;
      cssH = rect.height;
      canvas.width = Math.round(cssW * dpr);
      canvas.height = Math.round(cssH * dpr);
      ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
    }

    resize();
    const ro = new ResizeObserver(resize);
    ro.observe(canvas);

    const INK = "#010101";
    const WHITE = "#ffffff";

    function draw() {
      const cellW = cssW / COLS;
      const cellH = cssH / ROWS;
      const visibleCols = Math.max(0, Math.ceil((COLS * progress) / 100));

      ctx.clearRect(0, 0, cssW, cssH);

      ctx.fillStyle = INK;
      for (let r = 0; r < ROWS; r++) {
        if (!activeRows[r]) continue;
        const row = grid[r];
        for (let c = 0; c < visibleCols; c++) {
          if (row[c] != null) ctx.fillRect(c * cellW, r * cellH, cellW + 0.5, cellH + 0.5);
        }
      }

      ctx.fillStyle = WHITE;
      const fontSize = Math.min(cellH * 0.55, cellW * 0.6);
      ctx.font = `500 ${fontSize}px "Mona Sans", sans-serif`;
      ctx.textAlign = "center";
      ctx.textBaseline = "middle";
      for (let r = 0; r < ROWS; r++) {
        if (!activeRows[r]) continue;
        const row = grid[r];
        for (let c = 0; c < visibleCols; c++) {
          const s = row[c];
          if (s != null) ctx.fillText(s, c * cellW + cellW / 2, r * cellH + cellH / 2);
        }
      }
    }

    let rafId = 0;
    let lastShuffle = performance.now();

    function loop() {
      const now = performance.now();
      if (now - lastShuffle >= SHUFFLE_INTERVAL_MS) {
        shuffleSymbols();
        lastShuffle = now;
      }
      draw();
      rafId = requestAnimationFrame(loop);
    }

    rafId = requestAnimationFrame(loop);

    if (document.fonts?.ready) {
      document.fonts.ready.then(() => draw());
    }

    if (freeze) {
      return () => {
        cancelAnimationFrame(rafId);
        clearInterval(scramble);
        ro.disconnect();
      };
    }

    let loaded = false;
    const onLoad = () => (loaded = true);
    if (document.readyState === "complete") loaded = true;
    else window.addEventListener("load", onLoad, { once: true });

    const startedAt = performance.now();
    let phase: "slow" | "snap" | "done" = "slow";
    let snapStart = 0;
    let snapFrom = 0;
    const slowTarget = minDuration > 0 ? 92 : 88;

    const driver = setInterval(() => {
      const n = performance.now();

      if (phase === "slow") {
        const elapsed = n - startedAt;
        const minDone = minDuration === 0 || elapsed >= minDuration;

        if (loaded && minDone) {
          phase = "snap";
          snapStart = n;
          snapFrom = progress;
          return;
        }

        if (minDuration > 0) {
          const target = Math.min(slowTarget, (elapsed / minDuration) * slowTarget);
          progress = Math.max(progress, target);
        } else {
          progress = Math.min(slowTarget, progress + (slowTarget - progress) * 0.025 + 0.15);
        }
        return;
      }

      if (phase === "snap") {
        const t = Math.min(1, (n - snapStart) / 600);
        const eased = 1 - Math.pow(1 - t, 2);
        progress = snapFrom + (100 - snapFrom) * eased;
        if (t >= 1) {
          progress = 100;
          phase = "done";
          clearInterval(driver);
          if (loaderEl) {
            gsap.to(loaderEl, {
              autoAlpha: 0,
              duration: 0.5,
              ease: "power2.out",
              onComplete: () => {
                hidden = true;
                document.body.style.overflow = "";
                ScrollTrigger.refresh();
              },
            });
          }
          window.loaderDone = true;
          window.dispatchEvent(new CustomEvent("loaderFinished"));
          setTimeout(() => {
            hidden = true;
            document.body.style.overflow = "";
            ScrollTrigger.refresh();
          }, 1200);
        }
      }
    }, 16);

    return () => {
      cancelAnimationFrame(rafId);
      clearInterval(driver);
      clearInterval(scramble);
      ro.disconnect();
      window.removeEventListener("load", onLoad);
      document.body.style.overflow = "";
    };
  });
</script>

{#if !hidden}
  <div
    bind:this={loaderEl}
    class="fixed inset-0 z-[100] flex items-center justify-center bg-bg"
    role="progressbar"
    aria-valuemin="0"
    aria-valuemax="100"
    aria-valuenow={Math.round(progress)}
  >
    <div class="stack">
      <div class="board">
        <canvas bind:this={canvasEl} class="board-canvas"></canvas>
        <div class="percent">
          <span class="digits tabular-nums">{displayed}</span><span class="sign">%</span>
        </div>
      </div>
    </div>
  </div>
{/if}

<style>
  .stack {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1.25rem;
  }

  .board {
    position: relative;
    width: 85vw;
    max-width: 600px;
    aspect-ratio: 36 / 6;
  }

  .board-canvas {
    display: block;
    width: 100%;
    height: 100%;
  }

  .percent {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    display: inline-flex;
    align-items: baseline;
    font-family: var(--font-mono);
    font-weight: 700;
    color: var(--color-accent);
    font-size: 11vw;
    letter-spacing: -0.02em;
    line-height: 1;
    pointer-events: none;
  }

  @media (min-width: 768px) {
    .board {
      width: 600px;
    }
    .percent {
      font-size: 5.5rem;
    }
  }

  .digits {
    display: inline-block;
    min-width: 3ch;
    text-align: right;
  }

  .sign {
    margin-left: 0.25rem;
    opacity: 0.85;
  }
</style>

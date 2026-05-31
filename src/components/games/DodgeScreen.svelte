<script lang="ts">
  import { onMount } from "svelte";
  import { ui } from "../../state/ui.svelte";
  import type { Lang } from "../../locales";

  let { lang = "pl" as Lang }: { lang?: Lang } = $props();

  const T = {
    pl: { start: "Start", retry: "Jeszcze raz", score: "Uniki", over: "Koniec!", hint: "Przesuwaj dłoń w lewo / w prawo, aby omijać przeszkody" },
    en: { start: "Start", retry: "Again", score: "Dodges", over: "Game over!", hint: "Move your hand left / right to dodge the obstacles" },
  } as const;
  const tr = $derived(T[lang] ?? T.pl);

  let canvasEl: HTMLCanvasElement;
  let phase = $state<"idle" | "play" | "over">("idle");
  let score = $state(0);

  let raf = 0;
  let ctx: CanvasRenderingContext2D | null = null;
  let W = 0, H = 0;

  const player = { x: 0.5, w: 0.13, h: 0.05, y: 0.9 };
  type Ob = { x: number; y: number; w: number; vy: number };
  let obstacles: Ob[] = [];
  let frame = 0;

  function reset() {
    score = 0;
    obstacles = [];
    frame = 0;
    player.x = 0.5;
  }

  function spawnEvery() {
    return Math.max(28, 70 - Math.floor(score / 4) * 4);
  }
  function fallSpeed() {
    return 0.007 + Math.floor(score / 4) * 0.0012;
  }

  function colors() {
    const cs = getComputedStyle(document.documentElement);
    return {
      ink: cs.getPropertyValue("--color-ink").trim() || "#010101",
      accent: cs.getPropertyValue("--color-accent").trim() || "#fe5030",
      bg: cs.getPropertyValue("--color-bg").trim() || "#ddd",
    };
  }

  function draw() {
    if (!ctx) return;
    const c = colors();
    ctx.fillStyle = c.bg;
    ctx.fillRect(0, 0, W, H);
    // obstacles
    ctx.fillStyle = c.accent;
    for (const o of obstacles) {
      ctx.fillRect((o.x - o.w / 2) * W, o.y * H, o.w * W, o.w * 0.9 * W);
    }
    // player
    ctx.fillStyle = c.ink;
    ctx.fillRect((player.x - player.w / 2) * W, (player.y - player.h / 2) * H, player.w * W, player.h * H);
  }

  function loop() {
    raf = requestAnimationFrame(loop);
    // steer (smoothed)
    player.x += (ui.handX - player.x) * 0.3;
    player.x = Math.max(player.w / 2, Math.min(1 - player.w / 2, player.x));

    if (phase === "play") {
      frame++;
      if (frame % spawnEvery() === 0) {
        const w = 0.08 + Math.random() * 0.08;
        obstacles.push({ x: w / 2 + Math.random() * (1 - w), y: -0.1, w, vy: fallSpeed() });
      }
      const obW = 0.9; // height factor relative to width
      for (const o of obstacles) o.y += o.vy;

      // collision (AABB) with player
      const pl = player.x - player.w / 2, pr = player.x + player.w / 2;
      const pt = player.y - player.h / 2, pb = player.y + player.h / 2;
      for (const o of obstacles) {
        const ol = o.x - o.w / 2, or = o.x + o.w / 2;
        const ot = o.y, ob = o.y + o.w * obW;
        if (pr > ol && pl < or && pb > ot && pt < ob) {
          phase = "over";
        }
      }
      // remove passed → score
      const before = obstacles.length;
      obstacles = obstacles.filter((o) => o.y <= 1.1);
      score += before - obstacles.length;
    }
    draw();
  }

  function start() {
    reset();
    phase = "play";
  }

  onMount(() => {
    ctx = canvasEl.getContext("2d");
    const resize = () => {
      const rect = canvasEl.getBoundingClientRect();
      const dpr = Math.min(window.devicePixelRatio || 1, 2);
      W = rect.width; H = rect.height;
      canvasEl.width = Math.round(W * dpr);
      canvasEl.height = Math.round(H * dpr);
      ctx?.setTransform(dpr, 0, 0, dpr, 0, 0);
    };
    resize();
    const ro = new ResizeObserver(resize);
    ro.observe(canvasEl);
    loop();
    return () => { cancelAnimationFrame(raf); ro.disconnect(); };
  });
</script>

<div class="head">{tr.score} <b>{score}</b></div>

<div class="stage">
  <canvas bind:this={canvasEl} class="dodge-canvas"></canvas>
  {#if phase !== "play"}
    <div class="overlay">
      {#if phase === "over"}<p class="over">{tr.over}</p>{/if}
      {#if phase === "idle"}<p class="hint">{tr.hint}</p>{/if}
      <button class="play" onclick={start}>{phase === "over" ? tr.retry : tr.start}</button>
    </div>
  {/if}
</div>

<style>
  .head { text-align: center; font-family: var(--font-mono, monospace); font-size: 0.85rem; letter-spacing: 0.1em; text-transform: uppercase; color: var(--color-ink); margin-bottom: 0.75rem; }
  .head b { color: var(--color-accent); font-size: 1.1rem; }
  .stage { position: relative; width: 100%; aspect-ratio: 3 / 4; max-height: 60vh; border: 1px solid var(--color-ink); overflow: hidden; }
  .dodge-canvas { display: block; width: 100%; height: 100%; }
  .overlay { position: absolute; inset: 0; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 1rem; text-align: center; background: color-mix(in srgb, var(--color-bg) 70%, transparent); padding: 1rem; }
  .over { font-family: var(--font-display, sans-serif); font-weight: 700; font-size: clamp(1.6rem, 5vw, 2.6rem); text-transform: uppercase; color: var(--color-accent); margin: 0; }
  .hint { font-family: var(--font-mono, monospace); font-size: 0.78rem; line-height: 1.5; color: var(--color-ink); margin: 0; max-width: 20rem; }
  .play { padding: 0.7rem 1.9rem; border: 1px solid var(--color-ink); background: var(--color-ink); color: var(--color-bg); font-family: var(--font-mono, monospace); font-size: 0.8rem; letter-spacing: 0.12em; text-transform: uppercase; cursor: pointer; transition: background-color 0.2s ease; }
  .play:hover { background: var(--color-accent); border-color: var(--color-accent); }
</style>

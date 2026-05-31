<script lang="ts">
  import { onMount } from "svelte";
  import { ui } from "../../state/ui.svelte";
  import type { Lang } from "../../locales";

  let { lang = "pl" as Lang }: { lang?: Lang } = $props();

  const T = {
    pl: { start: "Start", retry: "Jeszcze raz", hits: "Odbicia", over: "Koniec!", hint: "Steruj paletką ruchem dłoni w górę / w dół" },
    en: { start: "Start", retry: "Again", hits: "Hits", over: "Game over!", hint: "Move your hand up / down to control the paddle" },
  } as const;
  const tr = $derived(T[lang] ?? T.pl);

  let canvasEl: HTMLCanvasElement;
  let phase = $state<"idle" | "play" | "over">("idle");
  let hits = $state(0);

  let raf = 0;
  let ctx: CanvasRenderingContext2D | null = null;
  let W = 0, H = 0;
  let paddleY = 0.5; // normalised
  let ball = { x: 0.5, y: 0.5, vx: -0.012, vy: 0.008, r: 0.02 };

  const PADDLE_H = 0.24;
  const PADDLE_W = 0.018;
  const PADDLE_X = 0.04;

  function reset() {
    hits = 0;
    ball = { x: 0.5, y: 0.5, vx: -0.011, vy: (Math.random() * 2 - 1) * 0.009, r: 0.02 };
    paddleY = 0.5;
  }

  function draw() {
    if (!ctx) return;
    const cs = getComputedStyle(document.documentElement);
    const ink = cs.getPropertyValue("--color-ink").trim() || "#010101";
    const accent = cs.getPropertyValue("--color-accent").trim() || "#fe5030";
    const bg = cs.getPropertyValue("--color-bg").trim() || "#ddd";
    ctx.fillStyle = bg;
    ctx.fillRect(0, 0, W, H);
    // paddle
    ctx.fillStyle = ink;
    ctx.fillRect(PADDLE_X * W, (paddleY - PADDLE_H / 2) * H, PADDLE_W * W, PADDLE_H * H);
    // ball
    ctx.fillStyle = accent;
    ctx.beginPath();
    ctx.arc(ball.x * W, ball.y * H, ball.r * W, 0, Math.PI * 2);
    ctx.fill();
  }

  function loop() {
    raf = requestAnimationFrame(loop);
    // paddle follows hand (smoothed)
    paddleY += (ui.handY - paddleY) * 0.25;
    paddleY = Math.max(PADDLE_H / 2, Math.min(1 - PADDLE_H / 2, paddleY));

    if (phase === "play") {
      ball.x += ball.vx;
      ball.y += ball.vy;
      if (ball.y - ball.r < 0) { ball.y = ball.r; ball.vy *= -1; }
      if (ball.y + ball.r > 1) { ball.y = 1 - ball.r; ball.vy *= -1; }
      if (ball.x + ball.r > 1) { ball.x = 1 - ball.r; ball.vx *= -1; }

      const paddleLeft = PADDLE_X + PADDLE_W;
      if (ball.x - ball.r <= paddleLeft && ball.vx < 0) {
        if (Math.abs(ball.y - paddleY) <= PADDLE_H / 2 + ball.r) {
          ball.x = paddleLeft + ball.r;
          ball.vx = Math.abs(ball.vx) * 1.04;
          ball.vy += (ball.y - paddleY) * 0.05;
          hits++;
        } else if (ball.x - ball.r <= 0) {
          phase = "over";
        }
      }
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

<div class="head">{tr.hits} <b>{hits}</b></div>

<div class="stage">
  <canvas bind:this={canvasEl} class="pong-canvas"></canvas>
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
  .stage { position: relative; width: 100%; aspect-ratio: 4 / 3; border: 1px solid var(--color-ink); overflow: hidden; }
  .pong-canvas { display: block; width: 100%; height: 100%; }
  .overlay { position: absolute; inset: 0; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 1rem; text-align: center; background: color-mix(in srgb, var(--color-bg) 70%, transparent); padding: 1rem; }
  .over { font-family: var(--font-display, sans-serif); font-weight: 700; font-size: clamp(1.6rem, 5vw, 2.6rem); text-transform: uppercase; color: var(--color-accent); margin: 0; }
  .hint { font-family: var(--font-mono, monospace); font-size: 0.78rem; line-height: 1.5; color: var(--color-ink); margin: 0; max-width: 20rem; }
  .play { padding: 0.7rem 1.9rem; border: 1px solid var(--color-ink); background: var(--color-ink); color: var(--color-bg); font-family: var(--font-mono, monospace); font-size: 0.8rem; letter-spacing: 0.12em; text-transform: uppercase; cursor: pointer; transition: background-color 0.2s ease; }
  .play:hover { background: var(--color-accent); border-color: var(--color-accent); }
</style>

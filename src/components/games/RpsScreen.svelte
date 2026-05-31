<script lang="ts">
  import { ui } from "../../state/ui.svelte";
  import type { Lang } from "../../locales";

  let { lang = "pl" as Lang }: { lang?: Lang } = $props();

  const T = {
    pl: { you: "TY", cpu: "CPU", play: "Graj rundę", again: "Jeszcze raz", show: "Pokaż gest!", invalid: "Pokaż ✊ ✋ lub ✌️", win: "WYGRANA", lose: "PRZEGRANA", draw: "REMIS", hint: "Na „0\" pokaż dłoń: ✊ kamień · ✋ papier · ✌️ nożyce" },
    en: { you: "YOU", cpu: "CPU", play: "Play round", again: "Again", show: "Show!", invalid: "Show ✊ ✋ or ✌️", win: "YOU WIN", lose: "YOU LOSE", draw: "DRAW", hint: "On \"0\" show: ✊ rock · ✋ paper · ✌️ scissors" },
  } as const;
  const tr = $derived(T[lang] ?? T.pl);

  const MOVE: Record<string, "rock" | "paper" | "scissors"> = {
    Closed_Fist: "rock",
    Open_Palm: "paper",
    Victory: "scissors",
  };
  const ICON = { rock: "✊", paper: "✋", scissors: "✌️" } as const;
  const MOVES = ["rock", "paper", "scissors"] as const;

  let phase = $state<"idle" | "count" | "result">("idle");
  let count = $state(3);
  let you = $state(0);
  let cpu = $state(0);
  let playerMove = $state<"rock" | "paper" | "scissors" | "">("");
  let cpuMove = $state<"rock" | "paper" | "scissors" | "">("");
  let outcome = $state<"win" | "lose" | "draw" | "invalid" | "">("");
  let timer: ReturnType<typeof setInterval> | null = null;

  const liveMove = $derived(MOVE[ui.handGesture]);

  function resolve(a: "rock" | "paper" | "scissors", b: string) {
    if (a === b) return "draw" as const;
    if ((a === "rock" && b === "scissors") || (a === "paper" && b === "rock") || (a === "scissors" && b === "paper")) return "win" as const;
    return "lose" as const;
  }
  function shoot() {
    const mv = MOVE[ui.handGesture];
    if (!mv) { outcome = "invalid"; playerMove = ""; cpuMove = ""; phase = "result"; return; }
    playerMove = mv;
    cpuMove = MOVES[Math.floor(Math.random() * 3)];
    outcome = resolve(mv, cpuMove);
    if (outcome === "win") you++;
    else if (outcome === "lose") cpu++;
    phase = "result";
  }
  function startRound() {
    if (phase === "count") return;
    outcome = ""; playerMove = ""; cpuMove = ""; count = 3; phase = "count";
    if (timer) clearInterval(timer);
    timer = setInterval(() => { count--; if (count <= 0) { if (timer) clearInterval(timer); timer = null; shoot(); } }, 800);
  }

  $effect(() => () => { if (timer) clearInterval(timer); });
</script>

<div class="score">
  <span>{tr.you} <b>{you}</b></span><span class="sep">—</span><span><b>{cpu}</b> {tr.cpu}</span>
</div>

<div class="arena">
  {#if phase === "count"}
    <div class="count">{count > 0 ? count : tr.show}</div>
    <div class="live">{liveMove ? ICON[liveMove] : "·"}</div>
  {:else if phase === "result"}
    {#if outcome === "invalid"}
      <p class="invalid">{tr.invalid}</p>
    {:else}
      <div class="versus">
        <div class="hand"><span class="emoji">{playerMove ? ICON[playerMove] : "?"}</span><span class="cap">{tr.you}</span></div>
        <span class="vs">VS</span>
        <div class="hand"><span class="emoji">{cpuMove ? ICON[cpuMove] : "?"}</span><span class="cap">{tr.cpu}</span></div>
      </div>
      <p class="outcome outcome--{outcome}">{outcome === "win" ? tr.win : outcome === "lose" ? tr.lose : tr.draw}</p>
    {/if}
  {:else}
    <p class="hint">{tr.hint}</p>
  {/if}
</div>

<button class="play" onclick={startRound} disabled={phase === "count"}>{phase === "result" ? tr.again : tr.play}</button>

<style>
  .score { display: flex; align-items: center; justify-content: center; gap: 1rem; font-family: var(--font-mono, monospace); font-size: 0.85rem; letter-spacing: 0.1em; text-transform: uppercase; color: var(--color-ink); }
  .score b { color: var(--color-accent); font-size: 1.15rem; }
  .sep { color: var(--color-mute); }
  .arena { min-height: 11rem; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 0.75rem; text-align: center; margin: 1rem 0; }
  .count { font-family: var(--font-display, sans-serif); font-weight: 700; font-size: clamp(3rem, 9vw, 6rem); line-height: 1; color: var(--color-ink); }
  .live { font-size: 2.5rem; line-height: 1; }
  .versus { display: flex; align-items: center; gap: 1.5rem; }
  .hand { display: flex; flex-direction: column; align-items: center; gap: 0.35rem; }
  .emoji { font-size: clamp(3rem, 8vw, 5rem); line-height: 1; }
  .cap { font-family: var(--font-mono, monospace); font-size: 0.7rem; letter-spacing: 0.12em; text-transform: uppercase; color: var(--color-mute); }
  .vs { font-family: var(--font-mono, monospace); font-size: 0.9rem; letter-spacing: 0.1em; color: var(--color-mute); }
  .outcome { font-family: var(--font-display, sans-serif); font-weight: 700; font-size: clamp(1.4rem, 4vw, 2.4rem); text-transform: uppercase; margin: 0.25rem 0 0; }
  .outcome--win { color: var(--color-accent); }
  .outcome--lose { color: var(--color-ink); }
  .outcome--draw { color: var(--color-mute); }
  .invalid { font-family: var(--font-mono, monospace); font-size: 0.85rem; color: var(--color-ink); margin: 0; }
  .hint { font-family: var(--font-mono, monospace); font-size: 0.8rem; line-height: 1.5; color: var(--color-mute); margin: 0; max-width: 22rem; }
  .play { display: block; margin: 0 auto; padding: 0.75rem 2rem; border: 1px solid var(--color-ink); background: var(--color-ink); color: var(--color-bg); font-family: var(--font-mono, monospace); font-size: 0.8rem; letter-spacing: 0.12em; text-transform: uppercase; cursor: pointer; transition: background-color 0.2s ease, opacity 0.2s ease; }
  .play:hover { background: var(--color-accent); border-color: var(--color-accent); }
  .play:disabled { opacity: 0.4; cursor: default; }
</style>

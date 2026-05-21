<script lang="ts">
  import { onMount } from "svelte";

  let {
    freeze = false,
    freezeAt = 60,
    minDuration = 0,
  }: { freeze?: boolean; freezeAt?: number; minDuration?: number } = $props();

  let progress = $state(freeze ? freezeAt : 0);
  let done = $state(false);
  let hidden = $state(false);

  const ROWS = 9;
  const COLS = 36;
  const ROW_ACTIVE_CHANCE = 0.7;
  const CELL_FILL_CHANCE = 0.55;
  const SHUFFLE_RATE = 0.12;

  const SYMBOLS = ["X", "O", "W", "✳", ">", "⌖", "✕", "◯", "+", "·", "*", "✺", "△", "▷"];

  type Cell = string | null;

  let grid = $state<Cell[][]>([]);
  let activeRows = $state<boolean[]>([]);

  function pickSymbol() {
    return SYMBOLS[Math.floor(Math.random() * SYMBOLS.length)];
  }

  function generateGrid() {
    const rows: boolean[] = Array.from({ length: ROWS }, () => Math.random() < ROW_ACTIVE_CHANCE);
    while (rows.filter(Boolean).length < 4) {
      rows[Math.floor(Math.random() * ROWS)] = true;
    }
    activeRows = rows;
    grid = rows.map((active) =>
      active
        ? Array.from({ length: COLS }, () => (Math.random() < CELL_FILL_CHANCE ? pickSymbol() : null))
        : Array<Cell>(COLS).fill(null),
    );
  }

  function shuffleSymbols() {
    grid = grid.map((row, r) =>
      activeRows[r]
        ? row.map((cell) => (cell != null && Math.random() < SHUFFLE_RATE ? pickSymbol() : cell))
        : row,
    );
  }

  const visibleCols = $derived(Math.max(0, Math.ceil((COLS * progress) / 100)));

  onMount(() => {
    generateGrid();

    const shuffle = setInterval(shuffleSymbols, 130);
    const reroll = setInterval(generateGrid, 4000);

    if (freeze) {
      return () => {
        clearInterval(shuffle);
        clearInterval(reroll);
      };
    }

    let loaded = false;
    const onLoad = () => (loaded = true);

    if (document.readyState === "complete") {
      loaded = true;
    } else {
      window.addEventListener("load", onLoad, { once: true });
    }

    const startedAt = performance.now();

    const interval = setInterval(() => {
      const elapsed = performance.now() - startedAt;
      const minDone = elapsed >= minDuration;
      const ready = loaded && minDone;

      if (ready) {
        progress = Math.min(100, progress + (100 - progress) * 0.12 + 0.6);
      } else if (minDuration > 0 && !minDone) {
        const timeTarget = Math.min(92, (elapsed / minDuration) * 92);
        progress = Math.max(progress, Math.min(timeTarget, progress + (timeTarget - progress) * 0.08 + 0.1));
      } else {
        const target = 88;
        progress = Math.min(target, progress + (target - progress) * 0.025 + 0.15);
      }

      if (progress >= 99.5) {
        progress = 100;
        done = true;
        clearInterval(interval);
        setTimeout(() => (hidden = true), 600);
      }
    }, 16);

    return () => {
      clearInterval(interval);
      clearInterval(shuffle);
      clearInterval(reroll);
      window.removeEventListener("load", onLoad);
    };
  });
</script>

{#if !hidden}
  <div
    class="fixed inset-0 z-[100] flex items-center justify-center bg-bg transition-opacity duration-500 ease-out"
    class:opacity-0={done}
    class:pointer-events-none={done}
    aria-hidden={done}
    role="progressbar"
    aria-valuemin="0"
    aria-valuemax="100"
    aria-valuenow={Math.round(progress)}
  >
    <div class="board relative">
      <div class="grid">
        {#each grid as row, r}
          {#each row as cell, c (`${r}-${c}`)}
            <div class="cell">
              {#if c < visibleCols && cell != null}
                <span class="tile">{cell}</span>
              {/if}
            </div>
          {/each}
        {/each}
      </div>

      <div class="percent">
        <span class="tabular-nums">
          {Math.round(progress).toString().padStart(3, "0")}%
        </span>
      </div>
    </div>
  </div>
{/if}

<style>
  .board {
    width: min(720px, 85vw);
  }

  .grid {
    display: grid;
    grid-template-columns: repeat(36, minmax(0, 1fr));
    grid-template-rows: repeat(9, minmax(0, 1fr));
    aspect-ratio: 36 / 9;
    gap: 2px;
  }

  .cell {
    position: relative;
  }

  .tile {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--color-ink);
    color: #fff;
    font-family: var(--font-mono);
    font-size: 0.7rem;
    line-height: 1;
  }

  .percent {
    position: absolute;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    padding: 0.4rem 0.7rem;
    background: var(--color-ink);
    color: var(--color-bg);
    font-family: var(--font-mono);
    font-size: 0.8rem;
    letter-spacing: 0.08em;
    z-index: 2;
    pointer-events: none;
  }
</style>

<script lang="ts">
  import { gsap } from "gsap";
  import { ui } from "../state/ui.svelte";
  import type { Lang } from "../locales";
  import RpsScreen from "./games/RpsScreen.svelte";
  import PongScreen from "./games/PongScreen.svelte";
  import DodgeScreen from "./games/DodgeScreen.svelte";

  let { lang = "pl" as Lang }: { lang?: Lang } = $props();

  const T = {
    pl: { open: "Gry", close: "Zamknij", title: "Mini-gry", menu: "Menu" },
    en: { open: "Games", close: "Close", title: "Mini games", menu: "Menu" },
  } as const;
  const tr = $derived(T[lang] ?? T.pl);

  const GAMES = {
    pl: [
      { id: "rps", icon: "✊✋✌️", name: "Kamień-Papier-Nożyce", desc: "Pokaż gest na „0\"" },
      { id: "pong", icon: "🏓", name: "Hand Pong", desc: "Paletka = wysokość dłoni" },
      { id: "dodge", icon: "🏃", name: "Unik", desc: "Omijaj przeszkody dłonią" },
    ],
    en: [
      { id: "rps", icon: "✊✋✌️", name: "Rock-Paper-Scissors", desc: "Show a gesture on \"0\"" },
      { id: "pong", icon: "🏓", name: "Hand Pong", desc: "Paddle = hand height" },
      { id: "dodge", icon: "🏃", name: "Dodge", desc: "Dodge obstacles with your hand" },
    ],
  } as const;
  const games = $derived(GAMES[lang] ?? GAMES.pl);

  type Screen = "menu" | "rps" | "pong" | "dodge";
  let screen = $state<Screen>("menu");
  let stageEl = $state<HTMLElement>();

  // GSAP: animate each freshly-mounted screen in.
  $effect(() => {
    if (stageEl) {
      gsap.fromTo(
        stageEl,
        { xPercent: 60, autoAlpha: 0 },
        { xPercent: 0, autoAlpha: 1, duration: 0.55, ease: "power3.out" },
      );
    }
  });

  async function go(s: Screen) {
    if (s === screen) return;
    if (stageEl) {
      await gsap.to(stageEl, { xPercent: -45, autoAlpha: 0, duration: 0.3, ease: "power2.in" });
    }
    screen = s;
  }

  function open() {
    ui.gameActive = true;
    screen = "menu";
  }
  function close() {
    ui.gameActive = false;
    screen = "menu";
  }

  const headTitle = $derived(
    screen === "menu" ? tr.title : games.find((g) => g.id === screen)?.name ?? tr.title,
  );
</script>

{#if ui.gestureActive}
  <button class="gg-open" onclick={() => (ui.gameActive ? close() : open())}>
    {ui.gameActive ? tr.close : tr.open}
  </button>
{/if}

{#if ui.gameActive}
  <div class="gg">
    <div class="gg-card">
      <div class="gg-bar">
        {#if screen !== "menu"}
          <button class="gg-back" onclick={() => go("menu")}>← {tr.menu}</button>
        {:else}
          <span class="gg-title">{headTitle}</span>
        {/if}
        <button class="gg-x" onclick={close} aria-label={tr.close}>✕</button>
      </div>

      {#if screen !== "menu"}
        <p class="gg-now">{headTitle}</p>
      {/if}

      {#key screen}
        <div class="gg-stage" bind:this={stageEl}>
          {#if screen === "menu"}
            <div class="gg-menu">
              {#each games as g}
                <button class="gg-game" onclick={() => go(g.id as Screen)}>
                  <span class="gg-ic">{g.icon}</span>
                  <span class="gg-nm">{g.name}</span>
                  <span class="gg-ds">{g.desc}</span>
                </button>
              {/each}
            </div>
          {:else if screen === "rps"}
            <RpsScreen {lang} />
          {:else if screen === "pong"}
            <PongScreen {lang} />
          {:else if screen === "dodge"}
            <DodgeScreen {lang} />
          {/if}
        </div>
      {/key}
    </div>
  </div>
{/if}

<style>
  .gg-open {
    position: fixed;
    left: 50%;
    bottom: 1.25rem;
    transform: translateX(-50%);
    z-index: 47;
    pointer-events: auto;
    padding: 0.5rem 1rem;
    border: 1px solid var(--color-ink);
    background: var(--color-bg);
    color: var(--color-ink);
    font-family: var(--font-mono, monospace);
    font-size: 0.75rem;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    cursor: pointer;
    transition: background-color 0.2s ease, color 0.2s ease;
  }
  .gg-open:hover { background: var(--color-accent); border-color: var(--color-accent); color: var(--color-bg); }

  .gg {
    position: fixed;
    inset: 0;
    z-index: 48;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(1, 1, 1, 0.35);
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
    padding: 1.5rem;
  }
  .gg-card {
    width: min(34rem, 92vw);
    background: var(--color-bg);
    border: 1px solid var(--color-ink);
    padding: 1.25rem 1.5rem 1.5rem;
    overflow: hidden;
  }

  .gg-bar { display: flex; align-items: center; justify-content: space-between; gap: 1rem; }
  .gg-title { font-family: var(--font-display, sans-serif); font-weight: 600; font-size: clamp(1.1rem, 2.4vw, 1.5rem); text-transform: uppercase; letter-spacing: -0.01em; color: var(--color-ink); }
  .gg-now { margin: 0.6rem 0 0; text-align: center; font-family: var(--font-display, sans-serif); font-weight: 600; font-size: clamp(1rem, 2.2vw, 1.35rem); text-transform: uppercase; color: var(--color-ink); }
  .gg-back { border: none; background: none; color: var(--color-ink); font-family: var(--font-mono, monospace); font-size: 0.72rem; letter-spacing: 0.1em; text-transform: uppercase; cursor: pointer; }
  .gg-back:hover { color: var(--color-accent); }
  .gg-x { border: none; background: none; color: var(--color-ink); font-size: 1.1rem; cursor: pointer; line-height: 1; }
  .gg-x:hover { color: var(--color-accent); }

  .gg-stage { margin-top: 1rem; }

  .gg-menu { display: flex; flex-direction: column; gap: 0.6rem; }
  .gg-game {
    display: grid;
    grid-template-columns: auto 1fr;
    grid-template-rows: auto auto;
    column-gap: 1rem;
    align-items: center;
    text-align: left;
    padding: 0.9rem 1rem;
    border: 1px solid var(--color-ink);
    background: var(--color-bg);
    color: var(--color-ink);
    cursor: pointer;
    transition: background-color 0.18s ease, color 0.18s ease;
  }
  .gg-game:hover { background: var(--color-ink); color: var(--color-bg); }
  .gg-ic { grid-row: 1 / 3; font-size: 1.5rem; line-height: 1; }
  .gg-nm { font-family: var(--font-display, sans-serif); font-weight: 600; font-size: 1.05rem; text-transform: uppercase; letter-spacing: -0.01em; }
  .gg-ds { font-family: var(--font-mono, monospace); font-size: 0.7rem; letter-spacing: 0.04em; text-transform: uppercase; opacity: 0.7; }
</style>

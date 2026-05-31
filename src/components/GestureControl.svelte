<script lang="ts">
  import { onMount } from "svelte";
  import { gsap } from "gsap";
  import { ScrollTrigger } from "gsap/ScrollTrigger";
  import { ui } from "../state/ui.svelte";
  import type { Lang } from "../locales";

  let { lang = "pl" as Lang }: { lang?: Lang } = $props();

  const T = {
    pl: {
      label: "Gesty",
      on: "Gesty ON",
      loading: "Uruchamiam…",
      denied: "Brak dostępu do kamery",
      failed: "Nie udało się uruchomić",
      privacy: "Obraz nie opuszcza Twojego urządzenia",
      waiting: "Pokaż dłoń kamerze",
      legendTitle: "Gesty",
      legend: [
        { g: "✋", d: "przewijaj — wysokość dłoni = kierunek" },
        { g: "✊", d: "stop" },
      ],
    },
    en: {
      label: "Gestures",
      on: "Gestures ON",
      loading: "Starting…",
      denied: "No camera access",
      failed: "Failed to start",
      privacy: "Video never leaves your device",
      waiting: "Show your hand to the camera",
      legendTitle: "Gestures",
      legend: [
        { g: "✋", d: "scroll — hand height = direction" },
        { g: "✊", d: "stop" },
      ],
    },
  } as const;
  const tr = $derived(T[lang] ?? T.pl);

  const GLABEL: Record<string, string> = {
    Open_Palm: "✋",
    Closed_Fist: "✊",
    Pointing_Up: "☝",
    Thumb_Up: "👍",
    Thumb_Down: "👎",
    Victory: "✌",
    ILoveYou: "🤟",
  };

  let active = $state(false);
  let status = $state<"idle" | "loading" | "running" | "error">("idle");
  let errorMsg = $state("");
  let detected = $state(false);
  let gestureIcon = $state("");
  let pulseId = $state(0);

  let videoEl: HTMLVideoElement;

  // non-reactive runtime state
  let stream: MediaStream | null = null;
  let recognizer: any = null;
  let raf = 0;
  let lastVideoTime = -1;
  let scrollVel = 0;
  let curGesture = "None";

  const MAX_SPEED = 30; // px per frame at full hand extension
  const DEADZONE = 0.18;

  const WASM_URL =
    "https://cdn.jsdelivr.net/npm/@mediapipe/tasks-vision@0.10.35/wasm";
  const MODEL_URL =
    "https://storage.googleapis.com/mediapipe-models/gesture_recognizer/gesture_recognizer/float16/1/gesture_recognizer.task";

  function setGesture(g: string) {
    if (g !== curGesture) {
      curGesture = g;
      ui.handGesture = g;
      gestureIcon = GLABEL[g] ?? "";
      if (g !== "None") pulseId++; // re-trigger pulse animation
    }
  }

  function updateFromResult(res: any) {
    if (!res?.landmarks?.length) {
      detected = false;
      scrollVel = 0;
      setGesture("None");
      return;
    }
    detected = true;
    ui.handY = res.landmarks[0][9].y;
    ui.handX = 1 - res.landmarks[0][9].x; // mirror to match preview
    const g = res.gestures?.[0]?.[0]?.categoryName ?? "None";
    setGesture(g);

    if (g === "Open_Palm") {
      const y = res.landmarks[0][9].y; // palm centre, 0=top .. 1=bottom
      const norm = (y - 0.5) * 2; // -1 (high) .. +1 (low)
      if (Math.abs(norm) > DEADZONE) {
        const mag = (Math.abs(norm) - DEADZONE) / (1 - DEADZONE);
        // hand high (norm<0) → scroll up; hand low → scroll down
        scrollVel = Math.sign(norm) * mag * MAX_SPEED;
      } else {
        scrollVel = 0;
      }
    } else {
      scrollVel = 0; // fist / anything else = brake
    }
  }

  function loop() {
    raf = requestAnimationFrame(loop);

    if (recognizer && videoEl && videoEl.readyState >= 2) {
      if (videoEl.currentTime !== lastVideoTime) {
        lastVideoTime = videoEl.currentTime;
        updateFromResult(recognizer.recognizeForVideo(videoEl, performance.now()));
      }
    }

    if (scrollVel !== 0 && !ui.gameActive && window.smoother) {
      const sm = window.smoother;
      const max = ScrollTrigger.maxScroll(window) || 0;
      const next = Math.max(0, Math.min(max, sm.scrollTop() + scrollVel));
      sm.scrollTop(next);
    }
  }

  async function enable() {
    status = "loading";
    errorMsg = "";
    try {
      stream = await navigator.mediaDevices.getUserMedia({
        video: { facingMode: "user", width: 640, height: 480 },
        audio: false,
      });
    } catch {
      status = "error";
      errorMsg = tr.denied;
      return;
    }

    try {
      videoEl.srcObject = stream;
      await videoEl.play();

      const vision = await import("@mediapipe/tasks-vision");
      const fileset = await vision.FilesetResolver.forVisionTasks(WASM_URL);
      const opts = {
        baseOptions: { modelAssetPath: MODEL_URL, delegate: "GPU" as const },
        runningMode: "VIDEO" as const,
        numHands: 1,
      };
      try {
        recognizer = await vision.GestureRecognizer.createFromOptions(fileset, opts);
      } catch {
        recognizer = await vision.GestureRecognizer.createFromOptions(fileset, {
          ...opts,
          baseOptions: { modelAssetPath: MODEL_URL, delegate: "CPU" as const },
        });
      }

      active = true;
      status = "running";
      ui.gestureActive = true;
      lastVideoTime = -1;
      scrollVel = 0;
      curGesture = "None";
      loop();
    } catch {
      status = "error";
      errorMsg = tr.failed;
      teardown();
    }
  }

  function teardown() {
    cancelAnimationFrame(raf);
    raf = 0;
    try {
      recognizer?.close?.();
    } catch {}
    recognizer = null;
    stream?.getTracks().forEach((t) => t.stop());
    stream = null;
    if (videoEl) videoEl.srcObject = null;
    detected = false;
    gestureIcon = "";
    scrollVel = 0;
  }

  function disable() {
    teardown();
    active = false;
    status = "idle";
    ui.gestureActive = false;
    ui.gameActive = false;
    ui.handGesture = "None";
  }

  function toggle() {
    if (active || status === "loading") disable();
    else enable();
  }

  onMount(() => {
    gsap.registerPlugin(ScrollTrigger);
    return () => teardown();
  });
</script>

<div class="gc" class:gc--active={active}>
  <div
    class="gc-panel"
    class:gc-panel--open={active || status === "loading" || status === "error"}
  >
    <div class="gc-video-wrap">
      <!-- svelte-ignore a11y_media_has_caption -->
      <video bind:this={videoEl} class="gc-video" playsinline muted></video>
      <span class="gc-dot" class:gc-dot--on={detected}></span>

      {#if gestureIcon}
        <span class="gc-gesture">{gestureIcon}</span>
      {/if}
      {#key pulseId}
        {#if active && gestureIcon}
          <span class="gc-pulse"></span>
        {/if}
      {/key}
    </div>

    {#if status === "error"}
      <p class="gc-text">{errorMsg}</p>
    {:else if status === "loading"}
      <p class="gc-text">{tr.loading}</p>
    {:else if active}
      <p class="gc-text">{detected ? "" : tr.waiting}</p>
      <ul class="gc-legend">
        {#each tr.legend as item}
          <li><span class="gc-legend__g">{item.g}</span>{item.d}</li>
        {/each}
      </ul>
    {/if}

    <p class="gc-privacy">{tr.privacy}</p>
  </div>

  <button class="gc-btn" onclick={toggle} aria-pressed={active} aria-label={tr.label}>
    <svg class="gc-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
      <path d="M23 7l-7 5 7 5V7z" />
      <rect x="1" y="5" width="15" height="14" rx="2" ry="2" />
    </svg>
    <span>{active ? tr.on : tr.label}</span>
  </button>
</div>

<style>
  .gc {
    position: fixed;
    left: 1.25rem;
    bottom: 1.25rem;
    z-index: 46;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 0.5rem;
    pointer-events: none;
  }

  .gc-btn {
    pointer-events: auto;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 0.85rem;
    border: 1px solid var(--color-ink);
    background: var(--color-bg);
    color: var(--color-ink);
    font-family: var(--font-mono, ui-monospace, monospace);
    font-size: 0.75rem;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    cursor: pointer;
    transition:
      background-color 0.2s ease,
      color 0.2s ease;
  }
  .gc--active .gc-btn {
    background: var(--color-accent);
    border-color: var(--color-accent);
    color: var(--color-bg);
  }
  .gc-btn:hover {
    background: var(--color-ink);
    color: var(--color-bg);
  }
  .gc--active .gc-btn:hover {
    background: var(--color-accent);
  }

  .gc-icon {
    width: 1.05rem;
    height: 1.05rem;
  }

  .gc-panel {
    pointer-events: auto;
    width: 230px;
    padding: 0.6rem;
    border: 1px solid var(--color-ink);
    background: var(--color-bg);
    display: none;
  }
  .gc-panel--open {
    display: block;
  }

  .gc-video-wrap {
    position: relative;
    width: 100%;
    aspect-ratio: 4 / 3;
    overflow: hidden;
    background: #000;
  }
  .gc-video {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transform: scaleX(-1);
    display: block;
  }
  .gc-dot {
    position: absolute;
    top: 0.4rem;
    right: 0.4rem;
    width: 0.55rem;
    height: 0.55rem;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.4);
    transition: background-color 0.2s ease;
    z-index: 2;
  }
  .gc-dot--on {
    background: var(--color-accent);
  }

  .gc-gesture {
    position: absolute;
    left: 0.4rem;
    bottom: 0.3rem;
    font-size: 1.4rem;
    line-height: 1;
    z-index: 2;
    filter: drop-shadow(0 1px 2px rgba(0, 0, 0, 0.6));
  }

  .gc-pulse {
    position: absolute;
    inset: 0;
    border: 2px solid var(--color-accent);
    pointer-events: none;
    z-index: 1;
    animation: gc-pulse 0.5s ease-out forwards;
  }
  @keyframes gc-pulse {
    from {
      opacity: 0.9;
      transform: scale(1);
    }
    to {
      opacity: 0;
      transform: scale(1.04);
    }
  }

  .gc-text {
    margin: 0.5rem 0 0;
    font-family: var(--font-mono, ui-monospace, monospace);
    font-size: 0.7rem;
    line-height: 1.4;
    letter-spacing: 0.02em;
    color: var(--color-ink);
    min-height: 1em;
  }

  .gc-legend {
    list-style: none;
    margin: 0.4rem 0 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
  }
  .gc-legend li {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-family: var(--font-mono, ui-monospace, monospace);
    font-size: 0.66rem;
    line-height: 1.3;
    letter-spacing: 0.02em;
    color: var(--color-ink);
  }
  .gc-legend__g {
    font-size: 0.95rem;
    width: 1.2rem;
    text-align: center;
    flex: none;
  }

  .gc-privacy {
    margin: 0.5rem 0 0;
    font-family: var(--font-mono, ui-monospace, monospace);
    font-size: 0.6rem;
    letter-spacing: 0.02em;
    color: var(--color-mute);
  }

  @media (max-width: 768px) {
    .gc-panel {
      width: 190px;
    }
  }
</style>

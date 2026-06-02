<script lang="ts">
  import { onMount } from "svelte";
  import { gsap } from "gsap";
  import { ScrollTrigger } from "gsap/ScrollTrigger";
  import { ui } from "../state/ui.svelte";
  import type { Lang } from "../locales";
  import * as THREE from "three";

  let handCanvasEl = $state<HTMLCanvasElement>();
  let isHovered = $state(false);

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

    // --- 3D Minecraft Waving Hand Setup --------------------------------------
    let hRenderer: THREE.WebGLRenderer | null = null;
    let hScene: THREE.Scene | null = null;
    let hCamera: THREE.OrthographicCamera | null = null;
    let armGroup: THREE.Group | null = null;

    let sleeveGeo: THREE.BoxGeometry | null = null;
    let handGeo: THREE.BoxGeometry | null = null;
    let thumbGeo: THREE.BoxGeometry | null = null;
    let finger1Geo: THREE.BoxGeometry | null = null;
    let finger2Geo: THREE.BoxGeometry | null = null;
    let finger3Geo: THREE.BoxGeometry | null = null;

    let sleeveMat: THREE.MeshLambertMaterial | null = null;
    let handMat: THREE.MeshLambertMaterial | null = null;
    let jointMat: THREE.MeshLambertMaterial | null = null;

    let hRaf = 0;

    if (handCanvasEl) {
      hRenderer = new THREE.WebGLRenderer({
        canvas: handCanvasEl,
        alpha: true,
        antialias: false, // Minecraft blocky style!
      });
      hRenderer.setSize(96, 96);
      hRenderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));

      hScene = new THREE.Scene();

      // Orthographic camera for isometric blocky look
      hCamera = new THREE.OrthographicCamera(-1.8, 1.8, 1.8, -1.8, 0.1, 100);
      hCamera.position.set(2.4, 2.8, 3.8);
      hCamera.lookAt(0, 0.8, 0);

      // Lights
      const ambientLight = new THREE.AmbientLight(0xffffff, 0.65);
      hScene.add(ambientLight);

      const dirLight = new THREE.DirectionalLight(0xffffff, 0.9);
      dirLight.position.set(2, 4, 3);
      hScene.add(dirLight);

      // Materials
      sleeveMat = new THREE.MeshBasicMaterial({ color: 0x010101, wireframe: true }); // wireframe sleeve
      handMat = new THREE.MeshBasicMaterial({ color: 0xFE5030, wireframe: true });   // wireframe glowing orange hand
      jointMat = new THREE.MeshBasicMaterial({ color: 0xFF6D52, wireframe: true });  // wireframe lighter orange hand

      armGroup = new THREE.Group();
      hScene.add(armGroup);

      // Construct blocky arm (Minecraft Voxel look)
      sleeveGeo = new THREE.BoxGeometry(0.7, 1.1, 0.7);
      const sleeveMesh = new THREE.Mesh(sleeveGeo, sleeveMat);
      sleeveMesh.position.set(0, 0.55, 0);
      armGroup.add(sleeveMesh);

      handGeo = new THREE.BoxGeometry(0.8, 0.7, 0.6);
      const handMesh = new THREE.Mesh(handGeo, handMat);
      handMesh.position.set(0, 1.45, 0);
      armGroup.add(handMesh);

      thumbGeo = new THREE.BoxGeometry(0.24, 0.35, 0.24);
      const thumbMesh = new THREE.Mesh(thumbGeo, jointMat);
      thumbMesh.position.set(-0.48, 1.35, 0.1);
      armGroup.add(thumbMesh);

      finger1Geo = new THREE.BoxGeometry(0.24, 0.45, 0.24);
      const finger1 = new THREE.Mesh(finger1Geo, handMat);
      finger1.position.set(-0.25, 1.95, 0);
      armGroup.add(finger1);

      finger2Geo = new THREE.BoxGeometry(0.24, 0.5, 0.24);
      const finger2 = new THREE.Mesh(finger2Geo, jointMat);
      finger2.position.set(0.02, 1.98, 0);
      armGroup.add(finger2);

      finger3Geo = new THREE.BoxGeometry(0.24, 0.42, 0.24);
      const finger3 = new THREE.Mesh(finger3Geo, handMat);
      finger3.position.set(0.28, 1.94, 0);
      armGroup.add(finger3);

      armGroup.position.set(0, -0.65, 0);

      const hAnimate = () => {
        hRaf = requestAnimationFrame(hAnimate);
        const time = performance.now() * 0.001;

        if (armGroup && hRenderer && hScene && hCamera) {
          if (active) {
            // Wave arm energetically when active (camera gestures ON)
            armGroup.rotation.z = Math.sin(time * 9.0) * 0.32;
            armGroup.rotation.x = Math.cos(time * 5.0) * 0.08;
            armGroup.rotation.y = 0.25 + Math.sin(time * 2.0) * 0.1;
            armGroup.position.y = -0.65 + Math.sin(time * 9.0) * 0.04;
          } else {
            // Idle breathing sway + slow Y rotation
            armGroup.rotation.z = Math.sin(time * 1.6) * 0.04;
            armGroup.rotation.x = 0;
            armGroup.position.y = -0.65 + Math.sin(time * 1.6) * 0.02;

            if (isHovered) {
              // Spin faster and wave on hover
              armGroup.rotation.y = time * 2.5;
              armGroup.rotation.z = Math.sin(time * 5) * 0.15;
            } else {
              // Normal slow spin
              armGroup.rotation.y = time * 0.65;
            }
          }
          hRenderer.render(hScene, hCamera);
        }
      };
      hAnimate();
    }

    return () => {
      teardown();
      cancelAnimationFrame(hRaf);
      
      // Dispose geometries
      sleeveGeo?.dispose();
      handGeo?.dispose();
      thumbGeo?.dispose();
      finger1Geo?.dispose();
      finger2Geo?.dispose();
      finger3Geo?.dispose();

      // Dispose materials
      sleeveMat?.dispose();
      handMat?.dispose();
      jointMat?.dispose();
      
      hRenderer?.dispose();
    };
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

  <div
    class="gc-hand-wrapper"
    onclick={toggle}
    onkeydown={(e) => e.key === "Enter" && toggle()}
    role="button"
    tabindex="0"
    aria-pressed={active}
    aria-label={tr.label}
    onmouseenter={() => (isHovered = true)}
    onmouseleave={() => (isHovered = false)}
  >
    <canvas bind:this={handCanvasEl} class="gc-hand-3d"></canvas>
    {#if active && gestureIcon}
      <span class="gc-btn-gesture-indicator">{gestureIcon}</span>
    {/if}
  </div>
</div>

<style>
  .gc {
    position: relative;
    z-index: 46;
    display: inline-flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 0.5rem;
    pointer-events: none;
  }

  .gc-hand-wrapper {
    pointer-events: auto;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
    background: none;
    border: none;
    padding: 0;
    text-decoration: none;
  }

  .gc-hand-3d {
    width: 96px;
    height: 96px;
    display: block;
    image-rendering: pixelated;
    transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
  }
  .gc-hand-wrapper:hover .gc-hand-3d {
    transform: scale(1.1);
  }

  .gc-hand-label {
    font-family: var(--font-display, sans-serif);
    font-size: 18px;
    font-weight: 500;
    text-transform: uppercase;
    color: var(--color-accent);
    transition: color 0.3s ease;
  }
  .gc-hand-wrapper:hover .gc-hand-label {
    color: var(--color-ink);
  }

  .gc-btn-gesture-indicator {
    font-size: 1.15rem;
    line-height: 1;
    margin-left: 0.1rem;
    display: inline-block;
    animation: gc-pulse-gesture 0.4s ease-out;
  }
  @keyframes gc-pulse-gesture {
    0% { transform: scale(0.6); opacity: 0.5; }
    100% { transform: scale(1); opacity: 1; }
  }

  .gc-panel {
    pointer-events: auto;
    position: absolute;
    bottom: 100%;
    left: 0;
    width: 230px;
    padding: 0.6rem;
    border: 1px solid var(--color-ink);
    background: var(--color-bg);
    display: none;
    margin-bottom: 0.5rem;
    z-index: 50;
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

<script lang="ts">
  import { onMount } from "svelte";
  import { animate } from "motion";
  import SphereScene from "./SphereScene.svelte";
  import { ui } from "../state/ui.svelte";

  let wrapperEl: HTMLDivElement;

  const targetOpacity = $derived(
    ui.menuOpen ? 0.5 : 0.15 + 0.35 * ui.heroScrollProgress,
  );

  function reveal() {
    animate(
      wrapperEl,
      { opacity: [0, 1], scale: [1.06, 1] },
      { duration: 1.6, ease: "easeOut" },
    );
  }

  onMount(() => {
    if ((window as any).loaderDone) {
      reveal();
    } else {
      window.addEventListener("loaderFinished", reveal, { once: true });
    }
    return () => window.removeEventListener("loaderFinished", reveal);
  });
</script>

<div
  bind:this={wrapperEl}
  class="sphere-fixed pointer-events-none"
  style="opacity: 0"
>
  <div class="sphere-box">
    <SphereScene {targetOpacity} />
  </div>
</div>

<style>
  .sphere-fixed {
    position: fixed;
    inset: 0;
    z-index: 0;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .sphere-box {
    position: relative;
    width: 50vw;
    height: 62vh;
  }
</style>

<script lang="ts">
  /**
   * MagneticDots — three dots that get "pulled" toward the cursor.
   *
   * How it works:
   *   1. A global `mousemove` listener (via <svelte:window>) reports the
   *      current cursor position on every frame the user moves the pointer.
   *   2. For each dot we measure its center point on the screen with
   *      getBoundingClientRect(). This stays accurate even after layout
   *      changes (resize, scroll).
   *   3. We compute the vector (dx, dy) from the dot's center to the cursor
   *      and its length `d` via Math.hypot.
   *   4. If the cursor is closer than RADIUS pixels, the dot is translated
   *      by (dx * STRENGTH, dy * STRENGTH) — so it visibly leans toward the
   *      cursor proportionally to how close the cursor is.
   *   5. Outside the radius the offset is reset to (0, 0). The CSS
   *      `transition-transform` on the spans smooths the return.
   *
   * Tuning:
   *   - RADIUS controls how far the magnetic field reaches.
   *   - STRENGTH controls how aggressively the dots follow the cursor
   *     (0 = no movement, 1 = the dot tries to meet the cursor).
   */
  let container: HTMLButtonElement | undefined = $state();
  let { onclick } = $props();
  let offsets = $state([
    { x: 0, y: 0 },
    { x: 0, y: 0 },
    { x: 0, y: 0 },
  ]);

  const RADIUS = 50;
  const STRENGTH = 0.6;
 
  function handleMove(e: MouseEvent) {
    if (!container) return;
    const dots = container.querySelectorAll<HTMLSpanElement>("span");
    offsets = Array.from(dots).map((dot) => {
      const r = dot.getBoundingClientRect();
      const cx = r.left + r.width / 2;
      const cy = r.top + r.height / 2;
      const dx = e.clientX - cx;
      const dy = e.clientY - cy;
      const d = Math.hypot(dx, dy);
      if (d < RADIUS) {
        return { x: dx * STRENGTH, y: dy * STRENGTH };
      }
      return { x: 0, y: 0 };
    });
  }
</script>

<svelte:window onmousemove={handleMove} />

<button
  bind:this={container}
  {onclick}
  class="label hover:text-accent transition-colors flex items-center gap-1 cursor-pointer p-8 -m-8"
  aria-label="More"
>
  {#each offsets as off}
    <span
      class="w-[5px] h-[5px] rounded-full bg-current transition-transform duration-300 ease-out"
      style="transform: translate({off.x}px, {off.y}px)"
    ></span>
  {/each}
</button>

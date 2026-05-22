<script lang="ts">
  import { onMount } from "svelte";
  import { Copyright } from "lucide-svelte";
  import { scrambleTo, DIGIT_CHARS } from "../utils/scramble";

  let mouseX = $state(0);
  let mouseY = $state(0);

  let xEl: HTMLSpanElement;
  let yEl: HTMLSpanElement;
  const xGen = { v: 0 };
  const yGen = { v: 0 };

  function pad(n: number) {
    return String(n).padStart(4, "0");
  }

  onMount(() => {
    let lastScramble = 0;

    const onMove = (e: MouseEvent) => {
      mouseX = e.clientX;
      mouseY = e.clientY;

      const now = Date.now();
      if (now - lastScramble > 80) {
        lastScramble = now;
        scrambleTo(xEl, pad(mouseX), xGen, DIGIT_CHARS, " ", 3);
        scrambleTo(yEl, pad(mouseY), yGen, DIGIT_CHARS, " ", 3);
      }
    };

    window.addEventListener("mousemove", onMove);
    return () => window.removeEventListener("mousemove", onMove);
  });
</script>

<div class="hero-mouse-info label opacity-0 invisible flex items-center gap-1.5 text-ink/60">
  <span>
    <span bind:this={xEl}>{pad(mouseX)}</span> x <span bind:this={yEl}>{pad(mouseY)}</span>
  </span>
  <Copyright size={12} strokeWidth={2} />
  <span>{new Date().getFullYear()}</span>
</div>

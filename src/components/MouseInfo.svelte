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

<div class="hero-mouse-info opacity-0 invisible fixed right-[10px] top-1/2 -translate-y-1/2 flex flex-col items-center gap-2 font-mono text-[11px] font-bold tracking-widest text-ink/60 z-50 py-10 px-0">
  <span class="[writing-mode:vertical-rl] pb-4 ">
    <span bind:this={xEl}>{pad(mouseX)}</span> x <span bind:this={yEl}>{pad(mouseY)}</span>
  </span>

  <Copyright size={12} strokeWidth={2} class="rotate-90" />
  <span class="[writing-mode:vertical-rl]">{new Date().getFullYear()}</span>
</div>

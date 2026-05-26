<script lang="ts">
  import { onMount } from "svelte";
  import { Copyright } from "lucide-svelte";

  let mouseX = $state(0);
  let mouseY = $state(0);

  function pad(n: number) {
    return String(n).padStart(4, "0");
  }

  onMount(() => {
    const onMove = (e: MouseEvent) => {
      mouseX = e.clientX;
      mouseY = e.clientY;
    };

    window.addEventListener("mousemove", onMove);
    return () => window.removeEventListener("mousemove", onMove);
  });
</script>

<div class="hero-mouse-info label opacity-0 invisible flex items-center gap-1.5" style="color: var(--color-accent);">
  <span>
    <span>{pad(mouseX)}</span> x <span>{pad(mouseY)}</span>
  </span>
  <Copyright size={12} strokeWidth={2} />
  <span>{new Date().getFullYear()}</span>
</div>

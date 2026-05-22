<script lang="ts">
  import { onMount } from "svelte";
  import { t, type Lang } from "../locales";
  import { scrambleTo, CHARS } from "../utils/scramble";

  let { lang = "pl" as Lang } = $props();
  const tr = $derived(t(lang));

  const roles    = $derived([...tr.hero.roles]);
  const statuses = $derived([...tr.hero.statuses]);

  let roleEl:   HTMLSpanElement;
  let statusEl: HTMLSpanElement;

  let roleIdx   = 0;
  let statusIdx = 0;
  let cursor    = $state(true);

  const rGen = { v: 0 };
  const sGen = { v: 0 };

  const intervals: any[] = [];

  function initAnimations() {
    if (!roleEl || !statusEl) return;
    scrambleTo(roleEl,   roles[0],    rGen);
    scrambleTo(statusEl, statuses[0], sGen);

    /* rotacja słów co 3s */
    const wordInterval = setInterval(() => {
      roleIdx   = (roleIdx   + 1) % roles.length;
      statusIdx = (statusIdx + 1) % statuses.length;
      scrambleTo(roleEl,   roles[roleIdx],      rGen);
      scrambleTo(statusEl, statuses[statusIdx], sGen);
    }, 3000);

    intervals.push(wordInterval);
  }

  onMount(() => {
    /* migający kursor */
    const cursorInterval = setInterval(() => {
      cursor = !cursor;
    }, 530);
    intervals.push(cursorInterval);

    if ((window as any).loaderDone) {
      initAnimations();
    } else {
      window.addEventListener("loaderFinished", initAnimations, { once: true });
    }

    return () => {
      intervals.forEach(clearInterval);
      window.removeEventListener("loaderFinished", initAnimations);
    };
  });
</script>

<div class="space-y-0.5" style="font-family: var(--font-display)">

  <div class="label">{tr.hero.prefix}</div>

  <div class="label flex items-center gap-1.5">
    <span>{tr.hero.roleLabel}</span>
    <span bind:this={roleEl} class="text-accent font-mono tracking-normal"></span>
    <span class="text-accent font-mono" style="opacity: {cursor ? 1 : 0}">_</span>
  </div>

  <div class="label flex items-center gap-1.5">
    <span>{tr.hero.availableLabel}</span>
    <span bind:this={statusEl} class="font-mono tracking-normal"></span>
  </div>


</div>

<script lang="ts">
  import { onMount } from "svelte";
  import { t, type Lang } from "../locales";
  import { scrambleTo, CHARS, DIGIT_CHARS } from "../utils/scramble";

  let { lang = "pl" as Lang } = $props();
  const tr = $derived(t(lang));

  const roles    = $derived([...tr.hero.roles]);
  const statuses = $derived([...tr.hero.statuses]);

  let roleEl:   HTMLSpanElement;
  let statusEl: HTMLSpanElement;
  let timeEl:   HTMLSpanElement;

  let roleIdx   = 0;
  let statusIdx = 0;
  let cursor    = $state(true);

  const rGen = { v: 0 };
  const sGen = { v: 0 };
  const tGen = { v: 0 };

  function getTime() {
    return new Date().toLocaleTimeString("pl-PL", {
      timeZone: "Europe/Warsaw",
      hour: "2-digit",
      minute: "2-digit",
    });
  }

  function getTimezone() {
    return Intl.DateTimeFormat("en", { timeZone: "Europe/Warsaw", timeZoneName: "short" })
      .formatToParts(new Date())
      .find((p) => p.type === "timeZoneName")?.value ?? "CET";
  }

  let timezone = $state(getTimezone());

  onMount(() => {
    scrambleTo(roleEl,   roles[0],    rGen);
    scrambleTo(statusEl, statuses[0], sGen);

    /* inicjalizacja zegara */
    let lastTime = getTime();
    scrambleTo(timeEl, lastTime, tGen, DIGIT_CHARS, ": ");

    /* rotacja słów co 3s */
    const wordInterval = setInterval(() => {
      roleIdx   = (roleIdx   + 1) % roles.length;
      statusIdx = (statusIdx + 1) % statuses.length;
      scrambleTo(roleEl,   roles[roleIdx],      rGen);
      scrambleTo(statusEl, statuses[statusIdx], sGen);
    }, 3000);

    /* migający kursor */
    const cursorInterval = setInterval(() => {
      cursor = !cursor;
    }, 530);

    /* zegar — scramble tylko gdy minuta się zmienia */
    const clockInterval = setInterval(() => {
      const now = getTime();
      if (now !== lastTime) {
        lastTime = now;
        scrambleTo(timeEl, now, tGen, DIGIT_CHARS, ": ");
      }
    }, 1000);

    return () => {
      clearInterval(wordInterval);
      clearInterval(cursorInterval);
      clearInterval(clockInterval);
    };
  });
</script>

<div class="space-y-0.5" style="font-family: var(--font-display)">

  <div class="label">{tr.hero.prefix}</div>

  <div class="label flex items-center gap-1.5">
    <span>{tr.hero.roleLabel}</span>
    <span bind:this={roleEl} class="text-accent font-mono tracking-wide"></span>
    <span class="text-accent font-mono" style="opacity: {cursor ? 1 : 0}">_</span>
  </div>

  <div class="label flex items-center gap-1.5">
    <span>{tr.hero.availableLabel}</span>
    <span bind:this={statusEl} class="font-mono tracking-wide"></span>
  </div>

  <div class="label text-mute flex items-center gap-1.5 pt-0.5">
    <span>{tr.hero.location}</span>
    <span class="text-mute/40">·</span>
    <span bind:this={timeEl} class="font-mono"></span>
    <span class="text-mute/40">{timezone}</span>
  </div>

</div>

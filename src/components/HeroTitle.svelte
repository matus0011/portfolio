<script lang="ts">
  import { onMount } from "svelte";
  import gsap from "gsap";
  import { t, type Lang } from "../locales";

  let { lang = "pl" as Lang } = $props();
  const tr = $derived(t(lang));

  const CHARS      = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789#@&%";
  const DIGIT_CHARS = "0123456789";

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

  /* skipChars — znaki które nie są animowane (np. ":" w czasie) */
  function scrambleTo(
    el: HTMLSpanElement,
    text: string,
    genRef: { v: number },
    charset = CHARS,
    skipChars = " ",
  ) {
    const myGen = ++genRef.v;

    el.innerHTML = "";
    const spans = [...text].map((char) => {
      const s = document.createElement("span");
      s.style.display = "inline-block";
      const skip = skipChars.includes(char);
      s.textContent = skip
        ? char
        : charset[Math.floor(Math.random() * charset.length)];
      el.appendChild(s);
      return { el: s, final: char, skip };
    });

    spans.forEach(({ el: span, final, skip }, i) => {
      if (skip) return;

      const delay      = i * 0.045;
      const iterations = 5 + i;
      const frameTime  = 0.055;
      let count = 0;

      const tick = () => {
        if (genRef.v !== myGen) return;
        if (count < iterations) {
          span.textContent = charset[Math.floor(Math.random() * charset.length)];
          count++;
          gsap.delayedCall(frameTime, tick);
        } else {
          span.textContent = final;
        }
      };

      gsap.delayedCall(delay, tick);
    });
  }

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
    <span class="text-mute/40">{tr.hero.timezone}</span>
  </div>

</div>

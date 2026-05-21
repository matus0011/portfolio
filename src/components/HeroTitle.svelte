<script lang="ts">
  import { onMount } from "svelte";
  import gsap from "gsap";

  const CHARS = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789#@&%";

  const roles    = ["Creative", "Full Stack", "Frontend", "Mobile"];
  const statuses = ["Full Time", "Freelance", "Remote", "Contract"];

  let roleEl:   HTMLSpanElement;
  let statusEl: HTMLSpanElement;
  let roleIdx   = 0;
  let statusIdx = 0;

  /* generacja — anuluje poprzednią animację gdy nowa się zaczyna */
  let roleGen   = 0;
  let statusGen = 0;

  function scrambleTo(el: HTMLSpanElement, text: string, genRef: { v: number }) {
    const myGen = ++genRef.v;
    const upper = text.toUpperCase();

    /* wyczyść i zbuduj spany dla każdej litery */
    el.innerHTML = "";
    const spans = [...upper].map((char) => {
      const s = document.createElement("span");
      s.style.display = "inline-block";
      s.style.minWidth = char === " " ? "0.3em" : "";
      s.textContent = char === " " ? " " : CHARS[Math.floor(Math.random() * CHARS.length)];
      el.appendChild(s);
      return { el: s, final: char === " " ? " " : char };
    });

    spans.forEach(({ el: span, final }, i) => {
      if (final === " ") return; /* spacja — nie animuj */

      const delay       = i * 0.045;
      const iterations  = 6 + i;          /* więcej iteracji dla późniejszych liter */
      const frameTime   = 0.055;

      let count = 0;

      const tick = () => {
        if (genRef.v !== myGen) return;  /* anulowane */
        if (count < iterations) {
          span.textContent = CHARS[Math.floor(Math.random() * CHARS.length)];
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
    const rGen = { v: 0 };
    const sGen = { v: 0 };

    scrambleTo(roleEl,   roles[0],    rGen);
    scrambleTo(statusEl, statuses[0], sGen);

    const interval = setInterval(() => {
      roleIdx   = (roleIdx   + 1) % roles.length;
      statusIdx = (statusIdx + 1) % statuses.length;
      scrambleTo(roleEl,   roles[roleIdx],    rGen);
      scrambleTo(statusEl, statuses[statusIdx], sGen);
    }, 3000);

    return () => clearInterval(interval);
  });
</script>

<div class="space-y-0.5">
  <div class="label">Web</div>

  <div class="label flex items-center gap-1.5">
    <span>Developer</span>
    <span bind:this={roleEl} class="text-accent font-mono tracking-wide"></span>
  </div>

  <div class="label flex items-center gap-1.5">
    <span>Available</span>
    <span bind:this={statusEl} class="font-mono tracking-wide"></span>
  </div>
</div>

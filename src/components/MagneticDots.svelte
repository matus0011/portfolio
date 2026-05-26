<script lang="ts">
  import { onMount } from "svelte";
  import gsap from "gsap";

  let container: HTMLButtonElement | undefined = $state();
  let { onclick } = $props();

  let dotEls: HTMLSpanElement[] = [];

  const RADIUS   = 64;
  const STRENGTH = 0.55;

  /* każda kropka ma inną "bezwładność" → efekt gumy / ogona */
  const SPEEDS = [0.25, 0.38, 0.52];

  onMount(() => {
    /* zapamiętaj oryginalne pozycje — nie używamy getBoundingClientRect w pętli */
    const origins = dotEls.map((el) => {
      const r = el.getBoundingClientRect();
      return { x: r.left + r.width / 2, y: r.top + r.height / 2 };
    });

    /* aktualizuj przy resize */
    const onResize = () => {
      dotEls.forEach((el, i) => {
        const r = el.getBoundingClientRect();
        origins[i] = { x: r.left + r.width / 2, y: r.top + r.height / 2 };
      });
    };
    window.addEventListener("resize", onResize);

    /* quickTo dla pozycji — osobny tween dla każdej kropki */
    const xTo = dotEls.map((el, i) =>
      gsap.quickTo(el, "x", { duration: SPEEDS[i], ease: "power3.out" })
    );
    const yTo = dotEls.map((el, i) =>
      gsap.quickTo(el, "y", { duration: SPEEDS[i], ease: "power3.out" })
    );

    const active = dotEls.map(() => false);

    const onMove = (e: MouseEvent) => {
      dotEls.forEach((dot, i) => {
        const dx = e.clientX - origins[i].x;
        const dy = e.clientY - origins[i].y;
        const d  = Math.hypot(dx, dy);

        if (d < RADIUS) {
          const t = (1 - d / RADIUS) * STRENGTH;
          xTo[i](dx * t * 2.2);
          yTo[i](dy * t * 2.2);

          if (!active[i]) {
            active[i] = true;
            gsap.to(dot, { scale: 1.6, duration: 0.3, ease: "back.out(2)", overwrite: "auto" });
          }
        } else if (active[i]) {
          active[i] = false;
          /* powrót pozycji i scale — osobne tweeny żeby nie kolidowały */
          gsap.to(dot, {
            x: 0, y: 0,
            duration: 0.7,
            ease: "elastic.out(1, 0.4)",
            delay: i * 0.04,
            overwrite: "auto",
          });
          gsap.to(dot, {
            scale: 1,
            duration: 0.4,
            ease: "back.out(1.5)",
            delay: i * 0.04,
            overwrite: "auto",
          });
        }
      });
    };

    window.addEventListener("mousemove", onMove);
    return () => {
      window.removeEventListener("mousemove", onMove);
      window.removeEventListener("resize", onResize);
    };
  });
</script>

<button
  bind:this={container}
  {onclick}
  class="label text-accent transition-colors flex items-center gap-3 cursor-pointer p-8 -m-8"
  aria-label="More"
>
  <span bind:this={dotEls[0]} class="w-[10px] h-[10px] rounded-full bg-current inline-block"></span>
  <span bind:this={dotEls[1]} class="w-[10px] h-[10px] rounded-full bg-current inline-block"></span>
  <span bind:this={dotEls[2]} class="w-[10px] h-[10px] rounded-full bg-current inline-block"></span>
</button>

<script lang="ts">
  import { onMount } from "svelte";
  import gsap from "gsap";

  let sectionEl: HTMLElement;

  const TITLE = "ABOUT ME";
  let charInners: HTMLElement[] = [];

  onMount(() => {
    // Filtruj undefined (spacja nie ma bind:this)
    const chars = charInners.filter(Boolean);

    // Literki tytułu schowane na starcie
    gsap.set(chars, { y: "-110%" });

    let shown = false;

    const onScroll = () => {
      const scrolled = window.scrollY / (document.documentElement.scrollHeight - window.innerHeight);

      if (scrolled >= 0.1 && !shown) {
        shown = true;
        const order = [...chars].sort(() => Math.random() - 0.5);
        gsap.to(order, { y: "0%", duration: 0.6, ease: "power3.out", stagger: 0.045 });
      } else if (scrolled < 0.1 && shown) {
        shown = false;
        const order = [...chars].sort(() => Math.random() - 0.5);
        gsap.to(order, { y: "-110%", duration: 0.4, ease: "power3.in", stagger: 0.035 });
      }
    };

    window.addEventListener("scroll", onScroll, { passive: true });

    return () => window.removeEventListener("scroll", onScroll);
  });
</script>

<section bind:this={sectionEl} class="about-section relative w-full px-8 md:px-12 overflow-hidden">

  <!-- Duży tytuł ABOUT ME -->
  <div class="about-title-wrap" aria-label="About me">
    {#each TITLE.split("") as char, i}
      {#if char === " "}
        <span class="title-space"></span>
      {:else}
        <span class="title-char-wrap">
          <span class="title-char" bind:this={charInners[i]}>{char}</span>
        </span>
      {/if}
    {/each}
  </div>

</section>


<style>
  /* ── Big title ───────────────────────────────────────────── */
  .about-title-wrap {
    display: flex;
    align-items: baseline;
    justify-content: center;
    padding-top: 50px;
    padding-bottom: 50px;
    line-height: 0.85;
  }

  .title-char-wrap {
    display: inline-block;
    overflow: hidden;
    line-height: 0.9;
  }

  .title-char {
    display: inline-block;
    will-change: transform;
    font-family: var(--font-display, "Mona Sans"), system-ui, sans-serif;
    font-size: clamp(4rem, 14vw, 16rem);
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: -0.02em;
    color: var(--color-ink);
  }

  .title-space {
    display: inline-block;
    width: clamp(1.5rem, 5vw, 6rem);
  }


</style>

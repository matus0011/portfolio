<script lang="ts">
  import MagneticDots from "./MagneticDots.svelte";

  const navLeft = [
    { label: "+1", href: "#" },
    { label: "Projekty", href: "#" },
  ];
  const navRight = [
    { label: "O mnie", href: "#" },
    { label: "Umiejętności", href: "#" },
    { label: "Kontakt", href: "#" },
  ];
</script>

<section class="relative h-screen w-full overflow-hidden px-8 md:px-12 py-6 md:py-8 flex flex-col">
  <!-- TOP NAV -->
  <nav class="flex items-center justify-between">
    <ul class="flex items-center gap-10">
      {#each navLeft as item}
        <li>
          <a href={item.href} class="label hover:text-accent transition-colors">{item.label}</a>
        </li>
      {/each}
    </ul>

    <ul class="flex items-center gap-10">
      {#each navRight as item}
        <li>
          <a href={item.href} class="label hover:text-accent transition-colors inline-flex items-center gap-1.5">
            {item.label}
          </a>
        </li>
      {/each}
      <li>
        <MagneticDots />
      </li>
    </ul>
  </nav>

  <!-- MAIN GRID -->
  <div class="flex-1 grid grid-cols-12 gap-6 mt-8 md:mt-12">

    <!-- LEFT COLUMN -->
    <div class="col-span-3 relative flex flex-col justify-center">
      <!-- Middle: collection meta + pagination, vertically centered -->
      <div>
        <div class="space-y-1">
          <div class="label">
            First Collection <span class="text-mute">//</span> 19.8.19
          </div>
        </div>

        <!-- Pagination dots -->
        <div class="mt-10 flex items-center gap-2">
          <span class="w-1.5 h-1.5 rounded-full bg-accent"></span>
          <span class="w-1.5 h-1.5 rounded-full bg-ink/20"></span>
          <span class="w-1.5 h-1.5 rounded-full bg-ink/20"></span>
          <span class="w-1.5 h-1.5 rounded-full bg-ink/20"></span>
          <span class="w-1.5 h-1.5 rounded-full bg-ink/20"></span>
        </div>
      </div>

      <!-- LinkedIn link — pinned to bottom of column -->
      <a
        href="#"
        class="absolute bottom-0 left-0 label inline-flex items-center gap-1.5 hover:text-accent transition-colors"
      >
        LinkedIn
        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <path d="M7 7h10v10" />
          <path d="m7 17 10-10" />
        </svg>
      </a>
    </div>

    <!-- CENTER — PLACEHOLDER IMAGE -->
    <div class="col-span-6 flex items-center justify-center">
      <div class="relative w-full h-full max-h-[78vh] flex items-center justify-center">
        <div
          class="w-full h-full rounded-sm relative overflow-hidden"
          style="background:
            radial-gradient(120% 100% at 50% 30%, #E8E5DE 0%, #D9D6D0 60%, #C8C4BD 100%);"
        >
          <div
            class="absolute inset-0 opacity-[0.06]"
            style="background-image: repeating-linear-gradient(135deg, #0A0A0A 0 1px, transparent 1px 14px);"
          ></div>

          <span class="absolute top-4 left-4 label text-ink/40">
            placeholder · model image
          </span>
        </div>
      </div>
    </div>

    <!-- RIGHT COLUMN -->
    <div class="col-span-3 relative flex flex-col justify-center">
      <!-- Top: 3 vertical text columns + 1st element = vertical line matching the longest -->
      <div class="absolute top-0 right-0 flex gap-1 label !text-base font-bold leading-tight items-stretch">
        <span class="block w-0.5 self-stretch bg-ink"></span>
        <span class="[writing-mode:vertical-rl]">Creation 2019</span>
        <span class="[writing-mode:vertical-rl]">Materials of</span>
        <span class="[writing-mode:vertical-rl]">X-Labs</span>
      </div>

      <!-- Middle: section -->
      <div>
        <div class="label mb-4">X/Labs</div>
        <div class="grid grid-cols-2 gap-4 max-w-xs ml-auto">
          <p class="label text-mute leading-relaxed">
            For automotation of x/labs unisex, summer collection and protection. Deep unisex.
          </p>
          <p class="label text-mute leading-relaxed">
            For automotation of x/labs unisex, sp/summer collection a.
          </p>
        </div>

        <!-- Arrow navigation -->
        <div class="mt-8 flex items-center gap-3 justify-end">
          <button aria-label="Previous" class="text-accent hover:opacity-70 transition-opacity">
            <svg width="22" height="10" viewBox="0 0 22 10" fill="none">
              <path d="M1 5H21M1 5L5 1M1 5L5 9" stroke="currentColor" stroke-width="1.2" />
            </svg>
          </button>
          <button aria-label="Next" class="text-accent hover:opacity-70 transition-opacity">
            <svg width="22" height="10" viewBox="0 0 22 10" fill="none">
              <path d="M21 5H1M21 5L17 1M21 5L17 9" stroke="currentColor" stroke-width="1.2" />
            </svg>
          </button>
        </div>
      </div>

    </div>

  </div>

  <!-- Down arrow — pinned to right edge of viewport -->
  <button
    aria-label="Scroll"
    class="group absolute right-0 bottom-0 w-[50px] h-[100px] bg-accent text-bg flex items-center justify-center overflow-hidden hover:bg-ink transition-colors duration-300"
  >
    <svg
      width="14"
      height="22"
      viewBox="0 0 14 22"
      fill="none"
      aria-hidden="true"
      class="transition-transform duration-300 ease-out group-hover:translate-y-1.5"
    >
      <path
        d="M7 1V21M7 21L1 15M7 21L13 15"
        stroke="currentColor"
        stroke-width="1.4"
        stroke-linecap="square"
      />
    </svg>
  </button>
</section>

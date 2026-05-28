<section class="accent-section">
  <div class="stripe stripe-1" data-speed="0.6"></div>
  <div class="stripe stripe-2" data-speed="0.75"></div>
  <div class="stripe stripe-3" data-speed="1.25"></div>
  <div class="stripe stripe-4" data-speed="1.4"></div>
</section>

<style>
  .accent-section {
    position: relative;
    width: 100%;
    min-height: 100vh;
    background-color: var(--color-accent);
    overflow: hidden;
  }

  .stripe {
    position: absolute;
    top: -20%;
    bottom: -20%;
    width: 1px;
    background-color: var(--color-ink);
    opacity: 0.35;
    transform: translateX(-50%);
  }

  .stripe-1 { left: calc(50% - 450px); }
  .stripe-2 { left: calc(50% - 150px); }
  .stripe-3 { left: calc(50% + 150px); }
  .stripe-4 { left: calc(50% + 450px); }
</style>

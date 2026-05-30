<!--
  CTA / outro screen. Presentational only — rendered inside AccentSection's
  pinned `.tech-pin`. AccentSection's scroll timeline slides it up (via
  `.cta-screen`) over the frozen experience screen, then drifts `.cta-fwrd`
  to the right.
-->
<div class="cta-screen">
  <div class="cta-lines">
    <div class="cta-line cta-line--top">
      <span class="word">WE</span>
      <span class="word">DRIVE</span>
    </div>
    <div class="cta-line">
      <span class="word"><span class="arrow">&#8594;</span>&nbsp;YOUR</span>
    </div>
    <div class="cta-line">
      <span class="word">SYSTEMS</span>
    </div>
    <div class="cta-line cta-line--fwrd">
      <span class="word cta-fwrd">FWRD</span>
    </div>
  </div>

  <div class="cta-aside">
    <p class="cta-desc">Digital architectures for an ever-shifting world.</p>
    <a class="cta-btn" href="#">
      <span>Let's talk</span>
      <span class="cta-btn__arrow">&#8594;</span>
    </a>
  </div>
</div>

<style>
  .cta-screen {
    position: absolute;
    inset: 0;
    z-index: 6;
    display: flex;
    flex-direction: column;
    justify-content: center;
    overflow: hidden;
    padding: 6vh 0;
    background-color: var(--color-bg);
    will-change: transform;
  }

  .cta-lines {
    display: flex;
    flex-direction: column;
    width: 100%;
  }

  .cta-line {
    display: flex;
    align-items: baseline;
    padding: 0 2.5rem;
  }

  .cta-line--top {
    justify-content: space-between;
  }

  .cta-line--fwrd {
    padding-left: 30%;
  }

  .word {
    font-family: var(--font-display, "Mona Sans"), system-ui, sans-serif;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 16vw;
    line-height: 0.82;
    letter-spacing: -0.03em;
    color: var(--color-ink);
    white-space: nowrap;
  }

  .cta-fwrd {
    display: inline-block;
    will-change: transform;
  }

  .arrow {
    font-weight: 400;
  }

  .cta-aside {
    position: absolute;
    top: 34%;
    right: 2.5rem;
    width: min(24rem, 32vw);
    z-index: 2;
  }

  .cta-desc {
    font-family: var(--font-display, "Mona Sans"), system-ui, sans-serif;
    font-size: clamp(0.9rem, 1.1vw, 1.0625rem);
    line-height: 1.35;
    letter-spacing: 0.01em;
    color: var(--color-ink);
    margin: 0 0 1.25rem;
  }

  .cta-btn {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1.5rem;
    width: 100%;
    padding: 0.85rem 1.25rem;
    border: 1px solid var(--color-ink);
    font-family: var(--font-mono, ui-monospace, monospace);
    font-size: 0.8125rem;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: var(--color-ink);
    text-decoration: none;
    transition:
      background-color 0.25s ease,
      color 0.25s ease;
  }

  .cta-btn:hover {
    background-color: var(--color-ink);
    color: var(--color-bg);
  }

  .cta-btn__arrow {
    font-size: 1rem;
  }

  @media (max-width: 768px) {
    .word {
      font-size: 19vw;
    }
    .cta-aside {
      position: static;
      width: auto;
      margin: 2rem 2.5rem 0;
      text-align: left;
    }
    .cta-line--fwrd {
      padding-left: 2.5rem;
    }
  }
</style>

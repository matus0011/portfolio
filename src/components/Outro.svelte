<!--
  Outro screen ("DOŚWIADCZENIE" / experience). Presentational only — it is
  rendered inside AccentSection's pinned `.tech-pin`, and AccentSection's
  scroll timeline slides it up (via the `.tech-outro` element) once the tech
  list collapses.
-->
<script lang="ts">
  const rows = [
    { company: "Studio Kreatywne", role: "Senior Frontend Developer", year: "2023" },
    { company: "Agencja Interaktywna", role: "Frontend Developer", year: "2021" },
    { company: "Freelance", role: "Fullstack Developer", year: "2020" },
    { company: "Software House", role: "Web Developer", year: "2019" },
    { company: "Startup SaaS", role: "Junior Developer", year: "2018" },
    { company: "Pierwsza Firma", role: "Stażysta", year: "2017" },
  ];
</script>

<div class="tech-outro">
  <div class="outro-inner">
    <h2 class="outro-heading">DOŚWIADCZENIE</h2>

    <div class="outro-table">
      <div class="outro-table__head">
        <span class="col col-1">(FIRMA)</span>
        <span class="col col-2">(STANOWISKO)</span>
        <span class="col col-3">(ROK)</span>
      </div>

      {#each rows as r}
        <div class="outro-table__row">
          <span class="col col-1">{r.company}</span>
          <span class="col col-2">{r.role}</span>
          <span class="col col-3">{r.year}</span>
        </div>
      {/each}
    </div>
  </div>
</div>

<style>
  .tech-outro {
    position: absolute;
    inset: 0;
    z-index: 5;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: var(--color-bg);
    color: var(--color-ink);
    will-change: transform;
  }

  .outro-inner {
    width: 100%;
    height: 85vh;
    display: flex;
    flex-direction: column;
    justify-content: center;
    gap: clamp(4rem, 12vh, 10rem);
    padding: 0 3rem;
  }

  .outro-heading {
    text-align: center;
    font-family: var(--font-display, "Mona Sans"), system-ui, sans-serif;
    font-size: clamp(2.5rem, 7.5vw, 8rem);
    font-weight: 600;
    text-transform: uppercase;
    line-height: 0.95;
    letter-spacing: -0.02em;
    margin: 0;
  }

  .outro-table {
    width: 100%;
    max-width: 72rem;
    margin: 0 auto;
  }

  .outro-table__head,
  .outro-table__row {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    align-items: center;
  }

  .outro-table__head {
    background-color: var(--color-ink);
    color: var(--color-bg);
    padding: 0.55rem 1.5rem;
    font-family: var(--font-mono, ui-monospace, monospace);
    font-size: 0.8125rem;
    text-transform: uppercase;
    letter-spacing: 0.04em;
  }

  .outro-table__row {
    padding: 0.62rem 1.5rem;
    font-family: var(--font-display, "Mona Sans"), system-ui, sans-serif;
    font-size: clamp(0.95rem, 1.1vw, 1.125rem);
    color: var(--color-ink);
  }

  .col-1 {
    text-align: left;
  }
  .col-2 {
    text-align: center;
  }
  .col-3 {
    text-align: right;
  }
</style>

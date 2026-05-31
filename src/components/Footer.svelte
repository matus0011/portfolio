<script lang="ts">
  import { onMount } from "svelte";
  import { t, type Lang } from "../locales";

  let { lang = "pl" as Lang }: { lang?: Lang } = $props();
  const tr = $derived(t(lang));
  const f = $derived(tr.footer);

  function goTo(target: string) {
    const sm = window.smoother;
    if (sm) {
      if (target === "top") sm.scrollTo(0, true);
      else sm.scrollTo(target, true, "top top");
    } else if (target === "top") {
      window.scrollTo({ top: 0, behavior: "smooth" });
    } else {
      document.querySelector(target)?.scrollIntoView({ behavior: "smooth" });
    }
  }

  const socials = [
    { label: "Instagram", href: "#" },
    { label: "LinkedIn", href: "#" },
    { label: "GitHub", href: "#" },
  ];

  const bottomLinks = [
    { label: "Dribbble", href: "#" },
    { label: "Behance", href: "#" },
    { label: "X / Twitter", href: "#" },
  ];

  let clock = $state("--:--");
  let tz = $state("GMT");

  function fmtTime() {
    return new Date().toLocaleTimeString("en-GB", {
      timeZone: "Europe/Warsaw",
      hour: "2-digit",
      minute: "2-digit",
    });
  }
  function fmtTz() {
    return (
      Intl.DateTimeFormat("en", {
        timeZone: "Europe/Warsaw",
        timeZoneName: "shortOffset",
      })
        .formatToParts(new Date())
        .find((p) => p.type === "timeZoneName")?.value ?? "GMT"
    );
  }

  onMount(() => {
    clock = fmtTime();
    tz = fmtTz();
    const id = setInterval(() => {
      clock = fmtTime();
    }, 10000);
    return () => clearInterval(id);
  });

  const year = new Date().getFullYear();
</script>

<footer id="contact" class="footer">
  <div class="footer-top" data-speed="0.92">
    <nav class="footer-nav">
      <a href="/" onclick={(e) => { e.preventDefault(); goTo("top"); }}>{tr.nav.home}</a>
      <a href="#about" onclick={(e) => { e.preventDefault(); goTo("#about"); }}>{tr.nav.about}</a>
      <a href="#tech" onclick={(e) => { e.preventDefault(); goTo("#tech"); }}>{tr.nav.tech}</a>
      <a href="#contact" onclick={(e) => { e.preventDefault(); goTo("#contact"); }}>{tr.nav.contact}</a>
    </nav>

    <div class="footer-contact">
      <a class="footer-phone" href={`tel:${f.phone.replace(/\s+/g, "")}`}>{f.phone}</a>
      <a class="footer-email" href={`mailto:${f.email}`}>{f.email}</a>

      <div class="footer-socials">
        {#each socials as s}
          <a class="footer-social" href={s.href} target="_blank" rel="noreferrer">
            {s.label}<span class="arrow">&#8599;</span>
          </a>
        {/each}
      </div>

      <div class="footer-address">
        <span class="footer-address__label">{f.addressLabel}:</span>
        {#each f.address as line}
          <span>{line}</span>
        {/each}
        <span class="footer-address__coords">{f.coords}</span>
      </div>
    </div>
  </div>

  <div class="footer-brackets">
    {#each bottomLinks as l}
      <a class="footer-bracket" href={l.href} target="_blank" rel="noreferrer">
        [ {l.label} ]
      </a>
    {/each}
  </div>

  <div class="footer-name" data-speed="1.15" aria-hidden="true">{f.name}</div>

  <div class="footer-bottom">
    <span>{tr.hero.location.toUpperCase()}: ({tz}) {clock}</span>
    <span class="footer-bottom__center">{f.role}</span>
    <span class="footer-bottom__right">©{year} — {f.rights}</span>
  </div>
</footer>

<style>
  .footer {
    position: relative;
    width: 100%;
    min-height: 100vh;
    background-color: var(--color-bg);
    color: var(--color-ink);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    padding: 3rem 2.5rem 1.75rem;
    overflow: hidden;
  }

  /* TOP --------------------------------------------------------------- */
  .footer-top {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    padding-top: 3vh;
  }

  .footer-nav {
    display: flex;
    flex-direction: column;
    gap: 0.4rem;
    align-self: center;
    font-family: var(--font-mono, ui-monospace, monospace);
    font-size: 0.95rem;
    letter-spacing: 0.04em;
    text-transform: uppercase;
  }

  .footer-nav a {
    color: var(--color-ink);
    text-decoration: none;
    width: max-content;
    transition: color 0.2s ease;
  }
  .footer-nav a:hover {
    color: var(--color-accent);
  }

  .footer-contact {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    text-align: right;
    gap: 0.35rem;
  }

  .footer-phone,
  .footer-email {
    font-family: var(--font-display, "Mona Sans"), system-ui, sans-serif;
    font-weight: 700;
    font-size: clamp(1.4rem, 2.8vw, 2.6rem);
    line-height: 1.05;
    letter-spacing: -0.01em;
    color: var(--color-ink);
    text-decoration: none;
  }
  .footer-phone:hover,
  .footer-email:hover {
    color: var(--color-accent);
  }

  .footer-socials {
    display: flex;
    gap: 2.5rem;
    margin-top: 1.5rem;
  }

  .footer-social {
    font-family: var(--font-mono, ui-monospace, monospace);
    font-size: 0.9rem;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    color: var(--color-ink);
    text-decoration: none;
    border-bottom: 1px solid var(--color-ink);
    padding-bottom: 2px;
    transition:
      color 0.2s ease,
      border-color 0.2s ease;
  }
  .footer-social .arrow {
    margin-left: 0.35rem;
  }
  .footer-social:hover {
    color: var(--color-accent);
    border-color: var(--color-accent);
  }

  .footer-address {
    display: flex;
    flex-direction: column;
    margin-top: 1.75rem;
    font-family: var(--font-mono, ui-monospace, monospace);
    font-size: 0.8125rem;
    line-height: 1.5;
    letter-spacing: 0.02em;
    color: var(--color-mute);
  }
  .footer-address__label {
    text-transform: uppercase;
  }

  /* BRACKET LINKS ----------------------------------------------------- */
  .footer-brackets {
    display: flex;
    justify-content: space-between;
    margin: 4vh 0;
  }

  .footer-bracket {
    font-family: var(--font-mono, ui-monospace, monospace);
    font-size: clamp(0.85rem, 1.1vw, 1.0625rem);
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: var(--color-ink);
    text-decoration: none;
    transition: color 0.2s ease;
  }
  .footer-bracket:hover {
    color: var(--color-accent);
  }

  /* GIANT NAME -------------------------------------------------------- */
  .footer-name {
    font-family: var(--font-display, "Mona Sans"), system-ui, sans-serif;
    font-weight: 700;
    text-transform: uppercase;
    font-size: 19vw;
    line-height: 0.8;
    letter-spacing: -0.03em;
    color: var(--color-ink);
    white-space: nowrap;
  }

  /* BOTTOM BAR -------------------------------------------------------- */
  .footer-bottom {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    gap: 2rem;
    margin-top: 3vh;
    font-family: var(--font-mono, ui-monospace, monospace);
    font-size: 0.75rem;
    letter-spacing: 0.04em;
    text-transform: uppercase;
    color: var(--color-mute);
  }
  .footer-bottom__center {
    text-align: center;
  }
  .footer-bottom__right {
    text-align: right;
    max-width: 22rem;
  }

  @media (max-width: 768px) {
    .footer-top {
      grid-template-columns: 1fr;
      gap: 2.5rem;
    }
    .footer-contact {
      align-items: flex-start;
      text-align: left;
    }
    .footer-socials {
      flex-wrap: wrap;
      gap: 1.25rem;
    }
    .footer-name {
      font-size: 24vw;
    }
    .footer-bottom {
      flex-direction: column;
      align-items: flex-start;
      gap: 0.75rem;
    }
    .footer-bottom__center,
    .footer-bottom__right {
      text-align: left;
    }
  }
</style>

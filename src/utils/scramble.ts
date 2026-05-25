import gsap from "gsap";

export const CHARS       = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789#@&%";
export const DIGIT_CHARS = "0123456789";

export function scrambleTo(
  el: HTMLElement,
  text: string,
  genRef: { v: number },
  charset = CHARS,
  skipChars = " ",
  speed = 1,
) {
  const myGen = ++genRef.v;

  el.innerHTML = "";
  const spans = [...text].map((char) => {
    const s = document.createElement("span");
    const skip = skipChars.includes(char);
    s.textContent = skip
      ? char
      : charset[Math.floor(Math.random() * charset.length)];
    el.appendChild(s);
    return { el: s, final: char, skip };
  });

  spans.forEach(({ el: span, final, skip }, i) => {
    if (skip) return;

    const delay      = (i * 0.045) / speed;
    const iterations = Math.max(2, Math.round((5 + i) / speed));
    const frameTime  = 0.055 / speed;
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

export function splitChars(el: HTMLElement, text: string): HTMLSpanElement[] {
  el.innerHTML = "";
  const spans: HTMLSpanElement[] = [];
  for (const char of text) {
    const s = document.createElement("span");
    s.textContent = char === " " ? " " : char;
    s.style.display = "inline-block";
    s.style.willChange = "transform, opacity";
    el.appendChild(s);
    spans.push(s);
  }
  return spans;
}

export function letterRise(
  el: HTMLElement,
  text: string,
  genRef: { v: number },
  opts: { distance?: string; duration?: number; stagger?: number; ease?: string } = {},
) {
  const myGen = ++genRef.v;
  const { distance = "100%", duration = 0.7, stagger = 0.05, ease = "power3.out" } = opts;

  el.innerHTML = "";
  const spans: HTMLSpanElement[] = [];
  for (const char of text) {
    const s = document.createElement("span");
    s.textContent = char === " " ? " " : char;
    s.style.display = "inline-block";
    s.style.willChange = "transform, opacity";
    el.appendChild(s);
    spans.push(s);
  }
  if (genRef.v !== myGen) return;

  gsap.fromTo(
    spans,
    { yPercent: 110, opacity: 0 },
    {
      yPercent: 0,
      opacity: 1,
      duration,
      stagger,
      ease,
      overwrite: "auto",
    },
  );
}

export function letterDrop(
  el: HTMLElement,
  genRef: { v: number },
  opts: { duration?: number; stagger?: number; ease?: string } = {},
) {
  const myGen = ++genRef.v;
  const { duration = 0.55, stagger = 0.04, ease = "power2.in" } = opts;

  const currentText = el.textContent || "";
  if (!currentText.trim()) return;

  // Re-split into per-char spans (handles both "TEXT" and pre-split state)
  el.innerHTML = "";
  const spans: HTMLSpanElement[] = [];
  for (const char of currentText) {
    const s = document.createElement("span");
    s.textContent = char === " " ? " " : char;
    s.style.display = "inline-block";
    s.style.willChange = "transform, opacity";
    el.appendChild(s);
    spans.push(s);
  }
  if (genRef.v !== myGen) return;

  gsap.to(spans, {
    yPercent: 110,
    opacity: 0,
    duration,
    stagger,
    ease,
    overwrite: "auto",
  });
}

export function scrambleOut(
  el: HTMLElement,
  genRef: { v: number },
  charset = CHARS,
  speed = 1,
) {
  const myGen = ++genRef.v;
  const currentText = el.textContent || "";
  if (!currentText) return;

  el.innerHTML = "";
  const spans = [...currentText].map((char) => {
    const s = document.createElement("span");
    s.textContent = char;
    el.appendChild(s);
    return { el: s, skip: char === " " };
  });

  spans.forEach(({ el: span, skip }, i) => {
    if (skip) {
      span.textContent = "";
      return;
    }

    const delay = (i * 0.04) / speed;
    const iterations = Math.max(2, Math.round((3 + i * 0.4) / speed));
    const frameTime = 0.05 / speed;
    let count = 0;

    const tick = () => {
      if (genRef.v !== myGen) return;
      if (count < iterations) {
        span.textContent =
          charset[Math.floor(Math.random() * charset.length)];
        count++;
        gsap.delayedCall(frameTime, tick);
      } else {
        span.textContent = "";
      }
    };

    gsap.delayedCall(delay, tick);
  });
}

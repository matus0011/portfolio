import gsap from "gsap";

export const CHARS       = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789#@&%";
export const DIGIT_CHARS = "0123456789";

export function scrambleTo(
  el: HTMLElement,
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

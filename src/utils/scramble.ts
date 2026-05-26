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
  onComplete?: () => void,
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

  const animatedCount = spans.filter((s) => !s.skip).length;
  let doneCount = 0;
  const maybeComplete = () => {
    doneCount++;
    if (doneCount === animatedCount && onComplete && genRef.v === myGen) {
      onComplete();
    }
  };

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
        maybeComplete();
      }
    };

    gsap.delayedCall(delay, tick);
  });

  if (animatedCount === 0 && onComplete) onComplete();
}

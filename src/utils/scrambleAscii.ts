// One-shot scramble: noise → settle → finalText. Spaces and newlines stay
// (so multi-line ASCII art keeps its shape). Returns a cancel function.
const SKIP_CHARS = " \n";
const RAND_CHARS = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789#@&%+-=*<>/\\|";

export function scrambleAsciiOnce(
  el: HTMLElement,
  finalText: string,
  opts: {
    scrambleFrames?: number; // pure-noise frames before settling
    scrambleFrameMs?: number;
    settleFrameMs?: number;
    settleBatchFraction?: number; // reveal this fraction of animated chars per tick
  } = {},
): () => void {
  const scrambleFrames = opts.scrambleFrames ?? 8;
  const scrambleFrameMs = opts.scrambleFrameMs ?? 45;
  const settleFrameMs = opts.settleFrameMs ?? 40;
  const settleBatchFraction = opts.settleBatchFraction ?? 1 / 14;

  const chars = [...finalText];
  const animatedIndices: number[] = [];
  for (let i = 0; i < chars.length; i++) {
    if (!SKIP_CHARS.includes(chars[i])) animatedIndices.push(i);
  }

  const rand = () =>
    RAND_CHARS[Math.floor(Math.random() * RAND_CHARS.length)];

  const renderRandom = () =>
    chars.map((c) => (SKIP_CHARS.includes(c) ? c : rand())).join("");

  let cancelled = false;
  let timeoutId: number | null = null;

  const schedule = (fn: () => void, ms: number) => {
    timeoutId = window.setTimeout(fn, ms);
  };

  let frames = 0;
  const scramblePhase = () => {
    if (cancelled) return;
    el.textContent = renderRandom();
    frames++;
    if (frames < scrambleFrames) {
      schedule(scramblePhase, scrambleFrameMs);
    } else {
      const revealed: (string | null)[] = chars.map((c) =>
        SKIP_CHARS.includes(c) ? c : null,
      );
      const order = [...animatedIndices];
      for (let i = order.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [order[i], order[j]] = [order[j], order[i]];
      }
      const batchSize = Math.max(
        1,
        Math.floor(animatedIndices.length * settleBatchFraction),
      );

      const settlePhase = () => {
        if (cancelled) return;
        for (let i = 0; i < batchSize && order.length > 0; i++) {
          const idx = order.shift()!;
          revealed[idx] = chars[idx];
        }
        el.textContent = revealed
          .map((r) => (r !== null ? r : rand()))
          .join("");
        if (order.length > 0) {
          schedule(settlePhase, settleFrameMs);
        } else {
          el.textContent = finalText;
        }
      };
      settlePhase();
    }
  };
  scramblePhase();

  return () => {
    cancelled = true;
    if (timeoutId !== null) {
      clearTimeout(timeoutId);
      timeoutId = null;
    }
  };
}

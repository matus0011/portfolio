class UIState {
  menuOpen = $state(false);
  heroScrollProgress = $state(0);
  // Gesture control / mini-game
  gestureActive = $state(false);
  gameActive = $state(false);
  handGesture = $state("None");
  handY = $state(0.5); // palm centre Y, 0=top .. 1=bottom
  handX = $state(0.5); // palm centre X (mirrored), 0=left .. 1=right
  activeSection = $state("home");
}

export const ui = new UIState();

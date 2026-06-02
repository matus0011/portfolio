class UIState {
  menuOpen = $state(false);
  heroScrollProgress = $state(0);
  activeSection = $state("home");
}

export const ui = new UIState();

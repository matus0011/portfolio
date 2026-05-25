class UIState {
  menuOpen = $state(false);
  heroScrollProgress = $state(0);
  morphProgress = $state(0);
}

export const ui = new UIState();

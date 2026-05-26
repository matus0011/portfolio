(function () {
  function patch() {
    document.querySelectorAll('select[data-setting="menu_hover_pointer"]').forEach((sel) => {
      if ([...sel.options].some(o => o.value === "flip_brandberry")) return;

      const opt = document.createElement("option");
      opt.value = "flip_brandberry";
      opt.textContent = "Flip Brandberry";
      sel.appendChild(opt);

      sel.addEventListener("change", () => {
        sel.dispatchEvent(new Event("input", { bubbles: true }));
      }, { once: true });
    });
  }

  const obs = new MutationObserver(patch);
  window.addEventListener("load", () => {
    patch();
    obs.observe(document.body, { childList: true, subtree: true });
  });
})();

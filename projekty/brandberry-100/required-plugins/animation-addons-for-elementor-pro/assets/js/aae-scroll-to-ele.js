document.addEventListener("DOMContentLoaded", function () {
  if (window.ScrollToPlugin) {
    gsap.registerPlugin(ScrollToPlugin);
  }
  if (typeof gsap !== "object" || typeof ScrollToPlugin === "undefined") {
    console.warn("GSAP or ScrollToPlugin not loaded.");
    return;
  }
  document.querySelectorAll(".aae-scroll-to, .elementor-element a:not(nav a):not(ul a):not(li a)").forEach(function (el) {
    let href = el.getAttribute("href");
    if (!href) return;
    el.addEventListener("click", function (e) {
      let targetHash = "";
      let hrefURL = null;
      try {
        hrefURL = new URL(href, window.location.origin);
        targetHash = hrefURL.hash; // e.g. "#form"
      } catch (err) {
        console.warn("Invalid href:", href);
        return;
      }
      let duration = el.dataset?.duration || 1;
      let ease = el.dataset?.ease || "power2.out";
      if (targetHash) {
        e.preventDefault();
        const targetEl = document.querySelector(targetHash);
        if (targetEl) {
          const currentUrl = window.location.href.split("#")[0]; // remove existing hash
          const newUrl = `${currentUrl}${targetHash}`;
          history.pushState(null, "", newUrl);
          gsap.to(window, {
            duration: duration,
            scrollTo: {
              y: targetEl,
              offsetY: 70
            },
            ease: ease
          });
        } else {
          href = href.replace(/\/#/, "/#!");
          window.location = href;
        }
      }
    });
  });

  // loaded
  if (!document.querySelector(".wcf-nav-menu-container")) {
    let hash = window.location.hash;
    if (hash) {
      hash = hash.replace(/\#!/, "#");
      const targetEl = document.querySelector(hash);
      if (targetEl) {
        gsap.to(window, {
          duration: 1,
          scrollTo: {
            y: targetEl,
            offsetY: 70
          },
          ease: "power2.out",
          onComplete: function () {
            const newUrl = window.location.href.replace("/#!", "/#");
            history.pushState(null, null, newUrl);
          }
        });
      }
    }
  }
});
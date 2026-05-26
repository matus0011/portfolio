($ => {
  window.addEventListener("elementor/frontend/init", () => {
    //Animation Elements
    if ("object" === typeof gsap) {
      const video_popup = function (currentEle) {
        if (!currentEle.length) return;
        currentEle = currentEle[0];
        let popup_content = document.querySelector(".wcf--popup-video-wrapper");
        if (popup_content && popup_content.parentNode.tagName.toLowerCase() !== "body") {
          if (!document.querySelector("body > .wcf--popup-video-wrapper")) {
            document.body.appendChild(popup_content);
          }
        }
        const innerPopup = currentEle.querySelector(".wcf--popup-video-wrapper");
        if (innerPopup) innerPopup.remove();
        const open_popup = currentEle.querySelectorAll(".wcf-popup-btn");
        open_popup.forEach(btn => {
          btn.addEventListener("click", () => {
            const url = btn.getAttribute("data-src");
            const popupWrapper = document.querySelector("body > .wcf--popup-video-wrapper");
            const container = popupWrapper.querySelector(".aae-popup-content-container");
            container.innerHTML = "";
            // Add iframe if not already present
            if (!popupWrapper.querySelector("iframe")) {
              container.innerHTML = `<iframe src="${url}" ></iframe>`;
            }
            // GSAP animation
            window.VideoAnimation = gsap.timeline({
              defaults: {
                ease: "power2.inOut"
              }
            }).to(".wcf--popup-video-wrapper", {
              scaleY: 0.01,
              x: 1,
              opacity: 1,
              visibility: "visible",
              duration: 0.4
            }).to(".wcf--popup-video-wrapper", {
              scaleY: 1,
              duration: 0.6
            }).to(".wcf--popup-video-wrapper .wcf--popup-video", {
              scaleY: 1,
              opacity: 1,
              visibility: "visible",
              duration: 0.6
            }, "-=0.4");
          });
        });
      };

      // Video Box
      const video_box = function ($currentEle) {
        const video_box_video = jQuery(".thumb video", $currentEle);
        if (video_box_video.length) {
          jQuery(".wcf--video-box", $currentEle).hover(function () {
            video_box_video.get(0).play();
          }, function () {
            video_box_video.get(0).pause();
            video_box_video.get(0).currentTime = 0;
          });
        }
      };
      let video_widgets = ["video-box", "video-box-slider"];
      for (const widget of video_widgets) {
        elementorFrontend.hooks.addAction(`frontend/element_ready/wcf--${widget}.default`, video_box);
      }
      elementorFrontend.hooks.addAction(`frontend/element_ready/wcf--video-popup.default`, video_popup);
      elementorFrontend.hooks.addAction(`frontend/element_ready/wcf--video-box.default`, video_popup);
      elementorFrontend.hooks.addAction(`frontend/element_ready/wcf--video-box-slider.default`, video_popup);
    } // end gsap
  });
})(jQuery);
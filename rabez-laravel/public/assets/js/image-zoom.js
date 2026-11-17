/**
 * RABEZ Smart Product Image Zoom & Fullscreen Lightbox
 * Ultra-smooth in-place cursor pan zoom + High-Definition Fullscreen Lightbox
 * Compatible with Single Product Page (/product/{slug}) and Quick View Modal.
 */
(function () {
  "use strict";

  // State
  let currentImages = [];
  let currentIndex = 0;
  let currentTitle = "Product Image";
  let zoomLevel = 1;
  const MIN_ZOOM = 1;
  const MAX_ZOOM = 3.5;
  const ZOOM_STEP = 0.5;

  let isDragging = false;
  let startX = 0;
  let startY = 0;
  let currentX = 0;
  let currentY = 0;

  // Touch pinch state
  let initialPinchDistance = null;
  let initialPinchZoom = 1;
  let lastTapTime = 0;

  // DOM Elements
  let lightbox = null;
  let lbImg = null;
  let lbTitle = null;
  let lbCounter = null;
  let lbZoomLevel = null;
  let lbThumbs = null;
  let lbPrev = null;
  let lbNext = null;
  let lbStage = null;

  function initLightboxElements() {
    lightbox = document.querySelector(".js-rabez-lightbox");
    if (!lightbox) return;

    lbImg = lightbox.querySelector(".js-lb-img");
    lbTitle = lightbox.querySelector(".js-lb-title");
    lbCounter = lightbox.querySelector(".js-lb-counter");
    lbZoomLevel = lightbox.querySelector(".js-lb-zoom-level");
    lbThumbs = lightbox.querySelector(".js-lb-thumbs");
    lbPrev = lightbox.querySelector(".js-lb-prev");
    lbNext = lightbox.querySelector(".js-lb-next");
    lbStage = lightbox.querySelector(".js-lb-stage");

    // Close button & backdrop click
    lightbox.querySelector(".js-lb-close")?.addEventListener("click", closeLightbox);
    lightbox.addEventListener("click", (e) => {
      if (e.target === lightbox || e.target.classList.contains("rabez-lightbox__stage")) {
        closeLightbox();
      }
    });

    // Zoom buttons
    lightbox.querySelector(".js-lb-zoom-in")?.addEventListener("click", () => applyZoom(zoomLevel + ZOOM_STEP));
    lightbox.querySelector(".js-lb-zoom-out")?.addEventListener("click", () => applyZoom(zoomLevel - ZOOM_STEP));
    lightbox.querySelector(".js-lb-zoom-reset")?.addEventListener("click", () => resetZoom());

    // Navigation buttons
    lbPrev?.addEventListener("click", prevImage);
    lbNext?.addEventListener("click", nextImage);

    // Keyboard controls
    document.addEventListener("keydown", (e) => {
      if (!lightbox || !lightbox.classList.contains("is-open")) return;
      if (e.key === "Escape") closeLightbox();
      if (e.key === "ArrowLeft") prevImage();
      if (e.key === "ArrowRight") nextImage();
      if (e.key === "+" || e.key === "=") applyZoom(zoomLevel + ZOOM_STEP);
      if (e.key === "-" || e.key === "_") applyZoom(zoomLevel - ZOOM_STEP);
      if (e.key === "0") resetZoom();
    });

    // Mouse drag to pan when zoomed
    if (lbStage && lbImg) {
      lbStage.addEventListener("mousedown", (e) => {
        if (zoomLevel <= 1) return;
        isDragging = true;
        startX = e.clientX - currentX;
        startY = e.clientY - currentY;
        lbStage.classList.add("is-dragging");
        e.preventDefault();
      });

      window.addEventListener("mousemove", (e) => {
        if (!isDragging) return;
        currentX = e.clientX - startX;
        currentY = e.clientY - startY;
        updateImgTransform();
      });

      window.addEventListener("mouseup", () => {
        if (isDragging) {
          isDragging = false;
          lbStage?.classList.remove("is-dragging");
        }
      });

      // Mouse wheel zoom
      lbStage.addEventListener("wheel", (e) => {
        e.preventDefault();
        const delta = e.deltaY < 0 ? ZOOM_STEP : -ZOOM_STEP;
        applyZoom(zoomLevel + delta);
      }, { passive: false });

      // Double-click toggle zoom
      lbStage.addEventListener("dblclick", (e) => {
        if (zoomLevel > 1) {
          resetZoom();
        } else {
          zoomLevel = 2.2;
          const rect = lbImg.getBoundingClientRect();
          const offsetX = (e.clientX - rect.left) / rect.width - 0.5;
          const offsetY = (e.clientY - rect.top) / rect.height - 0.5;
          currentX = -offsetX * rect.width * 1.2;
          currentY = -offsetY * rect.height * 1.2;
          applyZoom(zoomLevel);
        }
      });

      // Touch events (Pinch to zoom & drag)
      lbStage.addEventListener("touchstart", (e) => {
        if (e.touches.length === 2) {
          initialPinchDistance = getTouchDistance(e.touches);
          initialPinchZoom = zoomLevel;
        } else if (e.touches.length === 1) {
          const now = Date.now();
          if (now - lastTapTime < 300) {
            // Double tap detected
            if (zoomLevel > 1) {
              resetZoom();
            } else {
              applyZoom(2.2);
            }
          }
          lastTapTime = now;

          if (zoomLevel > 1) {
            isDragging = true;
            startX = e.touches[0].clientX - currentX;
            startY = e.touches[0].clientY - currentY;
          }
        }
      }, { passive: true });

      lbStage.addEventListener("touchmove", (e) => {
        if (e.touches.length === 2 && initialPinchDistance) {
          const newDist = getTouchDistance(e.touches);
          const factor = newDist / initialPinchDistance;
          applyZoom(initialPinchZoom * factor, false);
          e.preventDefault();
        } else if (e.touches.length === 1 && isDragging && zoomLevel > 1) {
          currentX = e.touches[0].clientX - startX;
          currentY = e.touches[0].clientY - startY;
          updateImgTransform();
          e.preventDefault();
        }
      }, { passive: false });

      lbStage.addEventListener("touchend", (e) => {
        if (e.touches.length < 2) {
          initialPinchDistance = null;
        }
        if (e.touches.length === 0) {
          isDragging = false;
        }
      }, { passive: true });
    }
  }

  function getTouchDistance(touches) {
    const dx = touches[0].clientX - touches[1].clientX;
    const dy = touches[0].clientY - touches[1].clientY;
    return Math.hypot(dx, dy);
  }

  function applyZoom(newZoom, updatePill = true) {
    zoomLevel = Math.max(MIN_ZOOM, Math.min(MAX_ZOOM, Math.round(newZoom * 10) / 10));
    if (zoomLevel <= 1) {
      currentX = 0;
      currentY = 0;
    }
    updateImgTransform();
    if (updatePill && lbZoomLevel) {
      lbZoomLevel.textContent = `${Math.round(zoomLevel * 100)}%`;
    }
  }

  function resetZoom() {
    zoomLevel = 1;
    currentX = 0;
    currentY = 0;
    updateImgTransform();
    if (lbZoomLevel) lbZoomLevel.textContent = "100%";
  }

  function updateImgTransform() {
    if (!lbImg) return;
    lbImg.style.transform = `translate(${currentX}px, ${currentY}px) scale(${zoomLevel})`;
  }

  function openLightbox(images, startIndex = 0, title = "Product Image") {
    if (!lightbox) initLightboxElements();
    if (!lightbox || !images || !images.length) return;

    currentImages = images;
    currentIndex = Math.max(0, Math.min(images.length - 1, startIndex));
    currentTitle = title;

    document.body.style.overflow = "hidden";
    lightbox.classList.add("is-open");

    renderCurrentImage();
    renderThumbs();
  }

  function closeLightbox() {
    if (!lightbox) return;
    lightbox.classList.remove("is-open");
    resetZoom();
    // Only restore body overflow if no quickview modal is open
    const qvOpen = document.querySelector(".js-quickview-backdrop.is-open");
    if (!qvOpen) {
      document.body.style.overflow = "";
    }
  }

  function renderCurrentImage() {
    if (!currentImages.length) return;
    const current = currentImages[currentIndex];
    const src = typeof current === "string" ? current : current.src;
    const label = (typeof current === "object" && current.label) ? current.label : `View ${currentIndex + 1}`;

    resetZoom();

    if (lbImg) {
      lbImg.style.opacity = "0.2";
      lbImg.src = src;
      lbImg.alt = `${currentTitle} - ${label}`;
      setTimeout(() => { lbImg.style.opacity = "1"; }, 120);
    }

    if (lbTitle) {
      lbTitle.textContent = currentTitle;
    }

    if (lbCounter) {
      lbCounter.textContent = `${currentIndex + 1} / ${currentImages.length}`;
    }

    // Toggle arrows
    if (lbPrev && lbNext) {
      const showNav = currentImages.length > 1;
      lbPrev.style.display = showNav ? "inline-flex" : "none";
      lbNext.style.display = showNav ? "inline-flex" : "none";
    }

    // Update active thumb
    if (lbThumbs) {
      const thumbs = lbThumbs.querySelectorAll(".js-lb-thumb-btn");
      thumbs.forEach((t, i) => t.classList.toggle("is-active", i === currentIndex));
    }
  }

  function renderThumbs() {
    if (!lbThumbs) return;
    if (currentImages.length <= 1) {
      lbThumbs.innerHTML = "";
      lbThumbs.style.display = "none";
      return;
    }

    lbThumbs.style.display = "flex";
    lbThumbs.innerHTML = currentImages.map((img, i) => {
      const src = typeof img === "string" ? img : img.src;
      const isAct = i === currentIndex;
      return `
        <button type="button" class="rabez-lightbox__thumb js-lb-thumb-btn ${isAct ? 'is-active' : ''}" data-idx="${i}" aria-label="View photo ${i + 1}">
          <img src="${src}" alt="Thumb ${i + 1}">
        </button>
      `;
    }).join("");

    lbThumbs.querySelectorAll(".js-lb-thumb-btn").forEach((btn) => {
      btn.addEventListener("click", (e) => {
        const idx = Number(btn.dataset.idx);
        if (!isNaN(idx)) {
          currentIndex = idx;
          renderCurrentImage();
        }
      });
    });
  }

  function prevImage() {
    if (currentImages.length <= 1) return;
    currentIndex = (currentIndex - 1 + currentImages.length) % currentImages.length;
    renderCurrentImage();
  }

  function nextImage() {
    if (currentImages.length <= 1) return;
    currentIndex = (currentIndex + 1) % currentImages.length;
    renderCurrentImage();
  }

  /* --- In-Place Desktop Cursor Hover Zoom --- */
  function attachHoverZoom(container) {
    if (!container || container.dataset.zoomAttached === "1") return;
    container.dataset.zoomAttached = "1";

    const img = container.querySelector("img");
    if (!img) return;

    const isHoverCapable = window.matchMedia("(hover: hover) and (pointer: fine)").matches;

    if (isHoverCapable) {
      container.addEventListener("mouseenter", () => {
        img.style.transform = "scale(2.2)";
        container.style.cursor = "zoom-in";
      });

      container.addEventListener("mousemove", (e) => {
        const rect = container.getBoundingClientRect();
        const x = Math.max(0, Math.min(100, ((e.clientX - rect.left) / rect.width) * 100));
        const y = Math.max(0, Math.min(100, ((e.clientY - rect.top) / rect.height) * 100));
        img.style.transformOrigin = `${x}% ${y}%`;
        img.style.transform = "scale(2.2)";
      });

      container.addEventListener("mouseleave", () => {
        img.style.transform = "scale(1)";
        img.style.transformOrigin = "center center";
      });
    }

    // Clicking image or zoom pill opens Fullscreen Lightbox
    container.addEventListener("click", (e) => {
      // Don't open if clicked on a flip pill (which has its own action)
      if (e.target.closest(".rabez-card-flip-pill")) return;

      const title = document.querySelector(".js-sp-title, .rabez-summary__title")?.textContent.trim() || "Football Jersey";
      const images = gatherGalleryImages(container);
      const activeSrc = img.src;
      let startIdx = images.findIndex((im) => {
        const s = typeof im === "string" ? im : im.src;
        return s === activeSrc || activeSrc.endsWith(s) || s.endsWith(activeSrc);
      });
      if (startIdx < 0) startIdx = 0;

      openLightbox(images, startIdx, title);
    });
  }

  function gatherGalleryImages(contextContainer) {
    let list = [];

    // Check Single Product Page thumbs
    const spRoot = contextContainer.closest(".js-single-product-root, .rabez-single-product");
    if (spRoot) {
      const thumbs = spRoot.querySelectorAll(".js-sp-thumb-btn");
      if (thumbs.length) {
        thumbs.forEach((t) => {
          if (t.dataset.src && !list.includes(t.dataset.src)) {
            list.push(t.dataset.src);
          }
        });
      }
    }

    // Check Quick View modal thumbs
    const qvModal = contextContainer.closest(".rabez-modal, .rabez-qv-layout");
    if (qvModal) {
      const qvThumbs = qvModal.querySelectorAll(".js-qv-thumb");
      if (qvThumbs.length) {
        qvThumbs.forEach((t) => {
          if (t.dataset.src && !list.includes(t.dataset.src)) {
            list.push(t.dataset.src);
          }
        });
      }
    }

    // Fallback to the main image itself
    const mainImg = contextContainer.querySelector("img");
    if (mainImg && (!list.length || !list.includes(mainImg.src))) {
      if (!list.length) list.push(mainImg.src);
    }

    return list;
  }

  // Scan and initialize containers
  function scanZoomContainers() {
    document.querySelectorAll(".rabez-gallery__main, .rabez-qv-main-media, .js-smart-zoom-container").forEach((el) => {
      attachHoverZoom(el);
    });
  }

  // Document ready
  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", () => {
      initLightboxElements();
      scanZoomContainers();
    });
  } else {
    initLightboxElements();
    scanZoomContainers();
  }

  // Observe DOM for dynamic quickview modals opening
  const observer = new MutationObserver(() => {
    scanZoomContainers();
  });
  observer.observe(document.body, { childList: true, subtree: true });

  // Expose global API
  window.RABEZ_ZOOM = {
    open: openLightbox,
    close: closeLightbox,
    scan: scanZoomContainers
  };
})();

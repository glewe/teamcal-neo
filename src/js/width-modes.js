/*!
 * Width mode toggler for TeamCal Neo
 * Copyright 2011-2024 George Lewe
 */

(() => {
  'use strict';

  /**
   * Retrieves the stored width from localStorage.
   *
   * @returns {string|null} The stored kwidth, or null if not set.
   */
  const getStoredWidth = () => localStorage.getItem('width');

  /**
   * Stores the given width in localStorage.
   *
   * @param {string} width - The width to store.
   */
  const setStoredWidth = width => localStorage.setItem('width', width);

  /**
   * Determines the preferred width based on stored width or system preference.
   *
   * @returns {string} The preferred width ('wide' or 'narrow').
   */
  const getPreferredWidth = () => {
    const storedWidth = getStoredWidth();
    console.log(`>>>[width-modes] - getPreferredWidth() - storedWidth: ${storedWidth}`);
    if (storedWidth) {
      return storedWidth;
    }
    return window.matchMedia('(prefers-width-scheme: narrow)').matches ? 'narrow' : 'wide';
  };

  /**
   * Sets the width by updating the `data-width` attribute on the document element.
   *
   * @param {string} width - The width to set ('wide', or 'narrow').
   */
  const setWidth = width => {
    if (width === 'wide') {
      console.log(`>>>[width-modes] - setWidth(wide) - width: ${width}`);
      // Select all div elements with the class "container"
      const containers = document.querySelectorAll('div.container');
      // Loop through each element and replace the class
      containers.forEach(container => {
        container.classList.add('container-fluid');
        container.classList.remove('container');
      });
    } else {
      console.log(`>>>[width-modes] - setWidth(else) - width: ${width}`);
      // Select all div elements with the class "container"
      const containers = document.querySelectorAll('div.container');
      console.log(`>>>[width-modes] - setWidth(else) - containers: ${containers}`);
      // Loop through each element and replace the class
      containers.forEach(container => {
        container.classList.add('container');
        container.classList.remove('container-fluid');
        console.log(`>>>[width-modes] - setWidth() - container: ${container.classList}`);
      });
    }
  };

  // Set the initial width based on the preferred width
  setWidth(getPreferredWidth());

  /**
   * Updates the active width display and accessibility attributes.
   *
   * @param {string} width - The active width.
   * @param {boolean} [focus=false] - Whether to focus the width switcher element.
   */
  const showActiveWidth = (width, focus = false) => {

    //-------------------------------------------------------------------------
    // Custom code to change the width icon in TeamCal Neo
    //
    const myActiveWidthIcon = document.querySelector('.width-icon-active');
    myActiveWidthIcon.className = '';
    switch (width) {
      case 'wide':
        myActiveWidthIcon.classList.add('width-icon-active', 'bi-fullscreen');
        break;
      case 'narrow':
        myActiveWidthIcon.classList.add('width-icon-active', 'bi-fullscreen-exit');
        break;
      default:
        myActiveWidthIcon.classList.add('width-icon-active', 'bi-fullscreen-exit');
        break;
    }
    // End custom code
    //-------------------------------------------------------------------------

    const widthSwitcher = document.querySelector('#bd-width');
    if (!widthSwitcher) {
      return;
    }

    const widthSwitcherText = document.querySelector('#bd-width-text');
    const activeWidthIcon = document.querySelector('.width-icon-active use');
    const btnToActive = document.querySelector(`[data-width-value="${width}"]`);
    const svgOfActiveBtn = btnToActive.querySelector('svg use').getAttribute('href');

    document.querySelectorAll('[data-width-value]').forEach(element => {
      element.classList.remove('active');
      element.setAttribute('aria-pressed', 'false');
    });
    btnToActive.classList.add('active');
    btnToActive.setAttribute('aria-pressed', 'true');
    activeWidthIcon.setAttribute('href', svgOfActiveBtn);
    const widthSwitcherLabel = `${widthSwitcherText.textContent} (${btnToActive.dataset.bsWidthValue})`;
    widthSwitcher.setAttribute('aria-label', widthSwitcherLabel);

    if (focus) {
      widthSwitcher.focus();
    }
  };

  // Listen for changes in the system's width scheme preference
  window.matchMedia('(prefers-width-scheme: narrow)').addEventListener('change', () => {
    const storedWidth = getStoredWidth();
    if (storedWidth !== 'wide' && storedTheme !== 'narrow') {
      setWidth(getPreferredWidth());
    }
  });

  // Initialize width switcher on DOMContentLoaded
  window.addEventListener('DOMContentLoaded', () => {
    showActiveWidth(getPreferredWidth());

    document.querySelectorAll('[data-width-value]')
        .forEach(toggle => {
          toggle.addEventListener('click', () => {
            const width = toggle.getAttribute('data-width-value');
            setStoredWidth(width);
            setWidth(width);
            showActiveWidth(width, true);
          });
        });
  });
})();

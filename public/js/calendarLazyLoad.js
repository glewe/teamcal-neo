(function () {
  'use strict';

  /**
   * Initialize Bootstrap tooltips on elements within a (possibly detached) root.
   * Mirrors the tooltip setup in layout.twig so lazy-loaded months behave identically.
   */
  function initTooltipsIn(root) {
    if (!window.jQuery) return;
    $(root).find('[data-bs-toggle="tooltip"]').each(function () {
      try {
        var opts = { html: true };
        if (this.hasAttribute('data-type')) {
          opts.template = '<div class="tooltip ' + $(this).attr('data-type') + '" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>';
        }
        $(this).tooltip(opts);
      } catch (e) {}
    });
  }

  /**
   * Execute inline <script> tags found in a tmp container.
   * Scripts injected via innerHTML do not execute automatically; this handles them.
   * DOMContentLoaded wrappers are replaced with an immediate IIFE since that
   * event has already fired by the time a lazy fragment is injected.
   */
  function execScriptsIn(root) {
    root.querySelectorAll('script').forEach(function (orig) {
      var s = document.createElement('script');
      s.textContent = orig.textContent.replace(
        /document\.addEventListener\s*\(\s*["']DOMContentLoaded["']\s*,\s*function\s*\(\s*\)\s*\{([\s\S]*?)\}\s*\)\s*;?/g,
        '(function(){$1}());'
      );
      document.head.appendChild(s);
      document.head.removeChild(s);
    });
  }

  document.addEventListener('DOMContentLoaded', function () {
    var placeholders = document.querySelectorAll('.cal-month-placeholder');
    var index = 0;

    function loadNext() {
      if (index >= placeholders.length) return;
      var el = placeholders[index++];
      var src = el.getAttribute('data-src');

      fetch(src)
        .then(function (r) { return r.text(); })
        .then(function (html) {
          // Parse into a detached container so we can init tooltips and
          // run scripts before the nodes enter the live DOM.
          var tmp = document.createElement('div');
          tmp.innerHTML = html;
          initTooltipsIn(tmp);
          execScriptsIn(tmp);

          // Move all children (calendarviewmonth.twig emits multiple root nodes
          // for responsive breakpoints) into the document, then remove placeholder.
          var parent = el.parentNode;
          while (tmp.firstChild) {
            parent.insertBefore(tmp.firstChild, el);
          }
          parent.removeChild(el);

          loadNext();
        })
        .catch(function () {
          el.innerHTML = '<div class="alert alert-danger">Failed to load month.</div>';
          loadNext();
        });
    }

    loadNext(); // sequential: month 2 → 3 → … avoids hammering shared-hosting servers
  });
}());

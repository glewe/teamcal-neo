(function () {
  'use strict';

  function mapWidthToBreakpoint(px) {
    if (px > 1024) return 'full';
    if (px >  800) return '1024';
    if (px >  640) return '800';
    if (px >  480) return '640';
    if (px >  400) return '480';
    if (px >  320) return '400';
    if (px >  240) return '320';
    return '240';
  }

  var container = document.querySelector('.container.content[data-cal-width]');
  if (!container) return;

  var currentBreakpoint = container.getAttribute('data-cal-width');
  var timer;

  var observer = new ResizeObserver(function (entries) {
    clearTimeout(timer);
    timer = setTimeout(function () {
      var px = entries[0].contentRect.width;
      var next = mapWidthToBreakpoint(px);
      if (next !== currentBreakpoint) {
        var url = new URL(window.location.href);
        url.searchParams.set('width', next);
        window.location.href = url.toString();
      }
    }, 300);
  });

  observer.observe(container);
}());

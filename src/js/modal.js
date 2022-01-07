/** 
 * Since confModal is essentially a nested modal it's enforceFocus method
 * must be no-op'd or the following error results. 
 * "Uncaught RangeError: Maximum call stack size exceeded"
 * But then when the nested modal is hidden we reset modal.enforceFocus
 * 
 * This fix is necessary since jQuery datepicker's year and month drop
 * down lists do not show when datepicker is used in a modal dialog.
 */
var enforceModalFocusFn = $.fn.modal.Constructor.prototype.enforceFocus;

$.fn.modal.Constructor.prototype.enforceFocus = function() {};

$confModal.on('hidden', function() {
    $.fn.modal.Constructor.prototype.enforceFocus = enforceModalFocusFn;
});

$confModal.modal({ backdrop : false });
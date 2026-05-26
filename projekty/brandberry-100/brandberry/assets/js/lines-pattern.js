jQuery(function ($) {
    var $page = $('#page');

    if (!$page.length) {
        return;
    }

    var linesHtml =
        '<div class="lines-pattern">' +
            '<div class="line-pattern"></div>' +
            '<div class="line-pattern"></div>' +
            '<div class="line-pattern"></div>' +
            '<div class="line-pattern"></div>' +
            '<div class="line-pattern mobile-hide"></div>' +
        '</div>';

    $page.prepend(linesHtml);
    // or: $page.after(linesHtml);
});

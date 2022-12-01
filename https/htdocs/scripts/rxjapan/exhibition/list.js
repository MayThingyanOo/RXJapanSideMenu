$(".qb-exhibition-list-header").on("click", function(e) {
    e.preventDefault();
    var parent = $(this).parents('table.qb-exhibition-list');
    var target = $(parent).find('.qb-exhibition-list-body');
    target.slideToggle('fast');
    parent.toggleClass('qb-open');
});
$(".qb-open")[0].scrollIntoView();
window.scrollBy(0, -100);
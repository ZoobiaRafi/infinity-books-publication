// Voyager's own slugify init only binds inputs inside .side-body (its
// multilingual panel wrapper). Re-apply it more broadly on DOMContentLoaded
// so a plain (non-translatable) field like our Service.slug still gets the
// auto-fill-from-title behavior. The plugin no-ops if already bound.
document.addEventListener('DOMContentLoaded', function () {
  if (typeof jQuery === 'undefined' || !jQuery.fn.slugify) return;

  jQuery('input[data-slug-origin]').each(function () {
    jQuery(this).slugify();
  });
});

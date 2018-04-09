jQuery(function ($) {

    var trigger = function () {
        $('.js-gdpr-conditional').each(function () {
            conditionalShow($(this));
        });
    };

    var conditionalShow = function ($el) {
        var type = $el.prop('tagName');
        if ('SELECT' === type) {
            conditionalShowSelect($el);
        } else if ('INPUT' === type) {
            if ('checkbox' === $el.attr('type')) {
                conditionalShowCheckbox($el);
            } else if ('radio' === $el.attr('type')) {
                conditionalShowRadio($el);
            } else {
                console.log('Unknown element type: ' + type);
            }
        } else {
            console.log('Unknown element type: ' + type);
        }
    };

    var conditionalShowSelect = function ($el) {
        $targets = [];
        $el.find('option').each(function () {
            if ($(this).data('show')) {
                $targets.push($(this).data('show'));
            }
        });

        $.each($targets, function (i, e) {
            $(e).hide();
        });

        if ($el.is(':visible')) {
            $el.find('option:selected').each(function () {
                if ($(this).data('show')) {
                  $($(this).data('show')).show();
                }
            });
        }
    }

    var conditionalShowCheckbox = function ($el) {
        if ($el.is(':checked') && $el.is(':visible')) {
            if ($el.data('show')) {
              if (isChange) {
                $($el.data('show')).addClass('slidePadding').slideDown();
              } else {
                $($el.data('show')).show();
              }
            }
        } else {
            if ($el.data('show')) {
              if (isChange) {
                $($el.data('show')).addClass('slidePadding').slideUp();
              } else {
                $($el.data('show')).hide();
              }
            }
        }
    };

    var conditionalShowRadio = function ($el) {
        $el.closest('fieldset').find('input[type=radio]').each(function (i, el) {
            if ($(el).is(':checked') && $el.is(':visible')) {
                if ($(el).data('show')) {
                  if (isChange) {
                    $($(el).data('show')).addClass('slidePadding').slideDown();
                  } else {
                    $($(el).data('show')).show();
                  }
                }
            } else {
                if ($(el).data('show')) {
                  if (isChange) {
                    $($(el).data('show')).addClass('slidePadding').slideUp();
                  } else {
                    $($(el).data('show')).hide();
                  }
                }
            }
        });
    };
  
    var isChange = false;

    $('.js-gdpr-conditional').each(function () {
        $(this).on('change', function () {
            isChange = true;
            conditionalShow($(this));

            // Hacky solution for 2nd layer of nested items
            trigger();
        });
        conditionalShow($(this));
    });

});

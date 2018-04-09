jQuery(function ($) {

    // Handler to open the modal dialog
    $(document).on('click', '.gdpr-open-modal', function (e) {
        $($(this).data('gdpr-modal-target')).dialog('open');
        e.preventDefault();
    });

    // Initialize all modals on page
    $('.gdpr-modal').each(function (i, e) {
        var $base = $(this);

        $base.dialog({
            title: $base.data('gdpr-title'),
            dialogClass: 'wp-dialog',
            autoOpen: false,
            draggable: false,
            width: 'auto',
            modal: true,
            resizable: false,
            closeOnEscape: true,
            position: {
                my: "center",
                at: "center",
                of: window
            },
            create: function () {
                // style fix for WordPress admin
                $('.ui-dialog-titlebar-close').addClass('ui-button');
            },
            open: function () {
                // Bind a click on the overlay to close the dialog
                $('.ui-widget-overlay').bind('click', function () {
                    $base.dialog('close');
                });

                // Bind a custom close button to close the dialog
                $base.find('.gdpr-close-modal').bind('click', function (e) {
                    $base.dialog('close');
                    e.preventDefault();
                });

                // Fix overlay CSS issues in admin
                $('.wp-dialog').css('z-index', 9999);
                $('.ui-widget-overlay').css('z-index', 9998);
            },
            close: function () {
                $('.wp-dialog').css('z-index', 101);
                $('.ui-widget-overlay').css('z-index', 100);
            }
        });
    });

    /**
     * https://github.com/DubFriend/jquery.repeater
     */
    $('.js-gdpr-repeater').each(function () {
        var $repeater = $(this).repeater({
            isFirstItemUndeletable: true
        });

        if (typeof window.repeaterData[$(this).data('name')] !== undefined) {
            $repeater.setList(window.repeaterData[$(this).data('name')]);
        }
    });

    /**
     * Init select2
     */
    $('.js-gdpr-select2').select2({
        width: 'style'
    });

    /**
     * Auto-fill DPA info
     */
    $('.js-gdpr-country-selector').on('change', function () {
        var dpaData, $website, $email, $phone;
        var countryCode = $(this).val();

        if (!window.gdprDpaData[countryCode]) {
            return;
        }

        dpaData = window.gdprDpaData[countryCode];

        $website = $('#gdpr_dpa_website');
        if ('' === $website.data('set')) {
            $website.val(dpaData['website']);
        }

        $email = $('#gdpr_dpa_email');
        if ('' === $email.data('set')) {
            $email.val(dpaData['email']);
        }

        $phone = $('#gdpr_dpa_phone');
        if ('' === $phone.data('set')) {
            $phone.val(dpaData['phone']);
        }
    });
});

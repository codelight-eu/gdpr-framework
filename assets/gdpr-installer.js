jQuery(function ($) {

    /**
     * Init select2
     */
    $('.js-gdpr-select2').select2({
        width: 'style'
    });

    $('#tabs').tabs();

    $(".sortable").sortable();

    /**
     * https://github.com/DubFriend/jquery.repeater
     */
    $repeater = $('.js-gdpr-repeater');
    if ($repeater.length) {
        $repeater.repeater({
            ready: function (setIndexes) {
                $(".sortable").on('sortupdate', setIndexes);
            }
        });

        if (typeof window.gdprConsentTypes !== undefined) {
            $repeater.setList(window.gdprConsentTypes);
        }
    }

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

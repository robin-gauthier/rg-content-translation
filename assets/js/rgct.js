jQuery(document).ready(function ($) {
    /*
     * Add action to remove notice on click
     */
    $('.notice-dismiss').live('click', function () {
        var notice = $(this).parents('.notice');
        notice.slideUp(function () {
            notice.remove();
        });
    });

    /*
     * Submit form with an ajax call
     */
    $('#translation-form').submit(function (e) {
        e.preventDefault();

        $('#rgct-loading').show();
        var data = $(this).serialize();

        $.post(ajaxurl, data, function (response) {
            createNotice('The values have been saved');
            $('#rgct-loading').hide();
        });

        return true;
    });

    /*
     * Function: createNotice
     * @param text: String
     * @param isNotice: Boolean (if false = error)
     */
    function createNotice(text, isNotice = true) {
        $('.notice').remove();
        var type = 'notice';
        if (!isNotice) {
            type = 'error';
        }

        var notice = '<div class="updated ' + type + ' is-dismissible"><p><strong>' + text + '</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Please disregard this message.</span></button><div>';
        $('#notice-container').append(notice);
    }


});

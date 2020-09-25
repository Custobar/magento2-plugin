define([
    'jquery',
    'jquery/ui',
    'mage/translate'
], function ($) {
    'use strict';

    $.widget('custobar.configLockToggle', {
        options: {
            disabled: true
        },

        _create: function () {
            this._disable(this.options.disabled);

            this.element.off('click').on('click', function (event) {
                event.preventDefault();

                this.options.disabled = !this.options.disabled;
                this._disable(this.options.disabled);
            }.bind(this));
        },

        _disable: function (disabled) {
            var affectedElements = this.element.parents('tbody').find('textarea, input, select');

            affectedElements.each(function () {
                var configInherit = $(this).closest('tr').find('input.config-inherit');

                if (!configInherit) {
                    $(this).prop('disabled', disabled);
                }
                if (!configInherit.is(':checked')) {
                    $(this).prop('disabled', disabled);
                }
            });

            var buttonText = $.mage.__('Lock');

            if (disabled) {
                buttonText = $.mage.__('Unlock');
            }
            $(this.element).html(buttonText);
        }
    });

    return $.custobar.configLockToggle;
});

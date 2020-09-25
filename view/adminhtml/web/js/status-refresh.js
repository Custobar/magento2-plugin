define([
    'jquery'
], function ($) {
    'use strict';

    $.widget('custobar.statusRefresh', {
        options: {
            refreshDataUrl: '',
            isRefreshActive: 0,
            refreshInterval: 10000
        },

        _create: function () {
            var self = this;

            if (self.options.isRefreshActive) {
                setInterval(function () {
                    $.ajax({
                        type: 'POST',
                        url: self.options.refreshDataUrl
                    }).complete(function (response) {
                        var statusDataItems = response.responseJSON;

                        $.each(statusDataItems, function (key, value) {
                            if (!value.hasOwnProperty('status_id')) {
                                return;
                            }

                            var rowContainer = $(self.element).find('#status-' + key);

                            if (rowContainer === undefined) {
                                return;
                            }

                            var currentStatus = rowContainer.data('current-status');

                            if (currentStatus !== 2) {
                                return;
                            }

                            if (value.hasOwnProperty('status_label')) {
                                rowContainer.find('td.column-status').html(value.status_label);
                            }
                            if (value.hasOwnProperty('export_percent')) {
                                rowContainer.find('td.column-export-percent').html(value.export_percent);
                            }
                            if (value.hasOwnProperty('last_export_time')) {
                                rowContainer.find('td.column-last-export-time').html(value.last_export_time);
                            }
                            if (value.hasOwnProperty('action_url') && value.hasOwnProperty('action_label')) {
                                rowContainer.find('td.column-button > a').attr('href', value.action_url);
                                rowContainer.find('td.column-button > a').html(value.action_label);
                            }
                        });
                    });
                }, self.options.refreshInterval);
            }
        }
    });

    return $.custobar.statusRefresh;
});

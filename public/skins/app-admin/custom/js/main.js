var app = (function (w, $) {
    var self = {
        utils: {},
        events: {}
    };

    /**
     * Adds get-params to search string
     *
     * @param {String} paramName
     * @param {String} paramValue
     * @param {String|Undefined} searchUrl
     *
     * @return {String}
     */
    self.utils.createSearchUrl = function (paramName, paramValue, searchUrl) {
        var url = searchUrl || location.search;
        if (url.length && paramName.indexOf('filter_sort') >= 0) {
            url = url.split('&');
            for (var i = 0; i < url.length; i++) {
                if (typeof url[i] !== 'undefined' && url[i].indexOf('filter_sort') >= 0) {
                    url.splice(i, 1);
                }
            }
            url = url.join('&');
            if (url.length && url[0] !== '?') url = '?' + url;
        }

        paramValue = encodeURIComponent(paramValue);

        if (url.indexOf(paramName + '=') >= 0) {
            var prefix = url.substring(0, url.indexOf(paramName + '='));
            var suffix = url.substring(url.indexOf(paramName + '='));
            if (suffix.indexOf('&') >= 0) {
                suffix = suffix.substring(suffix.indexOf('&'));
            } else {
                suffix = '';
            }
            url = prefix + paramName + '=' + paramValue + suffix;
        } else {
            if (url.indexOf('?') < 0) {
                url += '?' + paramName + '=' + paramValue;
            } else {
                url += '&' + paramName + '=' + paramValue;
            }
        }

        return url;
    };

    /**
     * Gets values from search string
     *
     * @param {String} paramName
     *
     * @return {String|Null}
     */
    self.utils.getSearchParam = function (paramName) {
        var search = w.location.search,
            data = [],
            params = {},
            param = [],
            i = 0;

        search = search.indexOf('?') === 0 ? search.substr(1) : search;
        data = search.split('&');

        for (i = 0; i < data.length; i++) {
            param = data[i].split('=');

            params[param[0]] = param[1];
        }

        if (paramName === null) {
            return params;
        }

        if (!(paramName in params)) {
            return null;
        }

        return params[paramName];
    };

    /**
     * Converts a set of keys-values in search string
     *
     * @param {Object} params
     *
     * @return {String}
     */
    self.utils.toSearchParams = function (params) {
        var url = '';

        if (!Object.keys(params).length) {
            return url;
        }

        for (key in params) {
            url += (key + '=' + params[key]);
            url += '&';
        }

        return url.slice(0, -1);
    };

    return self;
}(window, jQuery));

$(function () {
    CKFinder.setupCKEditor();
});
$(document).ready(function () {
    var sortField = app.utils.getSearchParam('sort_field'),
        sortType = app.utils.getSearchParam('sort_type'),
        $column,
        classes = [],
        currentType,
        $table = $('table.use-sorting');

    if (!$table.length) {
        return;
    }

    if (sortField) {
        sortType = sortType || 'asc';
        sortType = sortType.toLowerCase();
        $column = $table.find('.sort[data-name="' + sortField + '"]');
        $column.data('sort', sortType);

        $('.sort').each(function () {
            var name = $(this).data('name'),
                sortClass = 'sorting';

            classes = $(this).attr('class').split(' ').filter(function (value) {
                return Boolean(value) && value.indexOf('sorting') < 0;
            });

            if (sortField == name) {
                sortClass += ('_' + sortType);
            } else {
                $(this).data('sort', '');
            }

            classes.push(sortClass);

            $(this).attr('class', classes.join(' '));
        });
    }

    $table.on('click', '.sort', function () {
        var name = $(this).data('name'),
            type = $(this).data('sort'),
            search = location.search;

        if (type) {
            type = type.toLowerCase() === 'asc' ? 'desc' : 'asc';
        } else {
            type = 'asc';
        }

        search = app.utils.createSearchUrl('sort_field', name, search);
        search = app.utils.createSearchUrl('sort_type', type, search);

        location.search = search;
    });
});
$(document).ready(function() {
    $('.edit-btn').click(function() {
        var itemId = $(this).data('item-id');
        var labelElement = $('#item_' + itemId);
        var label = labelElement.data('value');
        
        if (labelElement.find('input').length) {
            // If label is already an input field, revert to text
            labelElement.html(label);
        } else {
            // If label is not an input field, replace with input field
            labelElement.html('<input type="text" name="items[edit][' + itemId + ']" class="form-control" value="' + label + '">');
        }
    });

    $('.add-child-btn').click(function() {
        var itemId = $(this).data('item-id');
        var childTable = '<table class="table">' +
                            '<tbody>' +
                                '<tr>' +
                                    '<td>' +
                                        '<input type="text" name="items[children][' + itemId + '][]" class="form-control">' +
                                    '</td>' +
                                    '<td>' +
                                        '<button type="button" class="btn btn-danger remove-child-btn"><i class="fas fa-trash"></i></button>' +
                                    '</td>' +
                                '</tr>' +
                            '</tbody>' +
                        '</table>';
        $(this).closest('tr').after('<tr class="child-row"><td colspan="3">' + childTable + '</td></tr>');
    });

    $(document).on('click', '.remove-child-btn', function() {
        $(this).closest('.child-row').remove();
    });
});
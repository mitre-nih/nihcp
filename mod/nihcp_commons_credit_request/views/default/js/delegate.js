$('.confirmation-required').click(function() {
    if(confirm(elgg.echo('question:areyousure'))) {
        document.forms[0].submit();
    } else {
        return false;
    }
});

$('#delegate-delete-button').click(function() {
    var button = $(this);
    var confirm = window.confirm(elgg.echo('question:areyousure'));
    if(confirm) {
        var delegation_guid = button.closest('tr').attr('id');
        elgg.action('delete_delegate', {
            data: {delegation_guid: delegation_guid},
            success: function () {
                location.reload();
            }
        });
    }
});
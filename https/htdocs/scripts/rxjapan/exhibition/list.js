var is_locked_delete_visitor_all;
if (vars) {
    is_locked_delete_visitor_all = vars('is_locked_delete_visitor_all');
}

$(function() {
    if (is_locked_delete_visitor_all) {
        $('.tool-tip').attr('title', '現在一括削除中です。\n削除完了後に再度お試してください。');
        $('[data-bs-toggle="tooltip"]').tooltip();
        $('._can_delete_visitor').addClass('disable').prop("disabled", true);
        $('._can_delete_visitor').addClass('visitor_delete_disable');
        $('._can_delete_visitor').css({
            cursor: 'not-allowed'
        });
    }

    var template = $('#delete_content_container').remove();
    $('._can_delete').on('click', function(event) {
        $('.qb-exhibition-dropdown-menu').removeClass('show');
        var button = $(this);
        if (button.hasClass('open_alert')) {
            return alert('開催中イベントです。削除できません。');
        }
        var clone = template.clone();
        clone.find('#' + button.data('type')).show();
        clone.find('input[type=text]').attr('in', JSON.stringify([button.data('name').toString()]));
        clone.find('.qb-notification-text').text(button.data('name'));
        prompt(button.data('title'), clone.show().html(), function(result, form) {
            form.attr("action", button.data("url")).submit();
        });
    });
    $('._can_delete_visitor').on('click', function(event) {
        $('.qb-exhibition-dropdown-menu').removeClass('show');
        var button = $(this);
        var exhibition_group_id = button.attr('data-attr');
        var url = button.attr('data-url');
        var sub_url = url.substr(0, url.indexOf('/delete_visitor'));
        var last_index = sub_url.lastIndexOf('/');
        var exhibition_id = sub_url.substring(last_index + 1);
        var session_update_url = vars('session_update').replace('@exhibition_id', exhibition_id);
        if (button.hasClass('open_alert')) {
            return alert('開催中イベントです。削除できません。');
        }
        var clone = template.clone();
        clone.find('#' + button.data('type')).show();
        clone.find('input[type=text]').attr('in', JSON.stringify([button.data('name').toString()]));
        clone.find('.qb-notification-text').text(button.data('name'));
        if (is_locked_delete_visitor_all) {
            return false;
        } else {
            prompt(button.data('title'), clone.show().html(), function(result, form) {
                var url = button.data("url");
                var token = form.find('input:hidden').val();
                var name = form.find('input[type=text]').val();
                var data = new FormData();
                data.append('name', name);
                data.append('_token', token);

                $.ajax({
                    url: url,
                    data: data,
                    type: 'POST',
                    processData: false,
                    contentType: false,
                });
                //update session value for delete visitor all alert
                $.ajax({
                    url: session_update_url,
                    type: 'GET',
                    processData: false,
                    contentType: false,
                    success: function(data, status, jqXHR) {
                        location.href = vars('return_route').replace('@exhibition_group_id', exhibition_group_id).replace('@deleted_visitor_count', data);
                    },
                });
            });
        }
    });
});

$(function() {
    var template = $('#exhibition_group_edit_container').remove();
    $('._exhibition_group_edit').on('click', function(event) {
        $('.qb-exhibition-dropdown-menu').removeClass('show');
        var button = $(this);
        var clone = template.clone();
        clone.find("#group_name").attr('value', button.data('name'));

        prompt('イベントタイトル変更', clone.show().html(), function(result, form) {
            if ($("#group_name").val().length <= 255)
                form.attr("action", button.data("url")).submit();
            else {
                $('#group_name_error').append('<label class="error">' + vars('error_message')['max']['string'].replace(':attribute', 'イベント名').replace(':max', '255') + '</label>');
                return false;
            }
        });
    });
});

$(function() {
    var template = $('#exhibition_group_function_container').remove();
    $('._exhibition_group_function').on('click', function(event) {
        $('.qb-exhibition-dropdown-menu').removeClass('show');
        var button = $(this);
        var clone = template.clone();
        var services = vars('groups')[button.data('id')];
        $.each(services, function(name, service) {
            var $el = clone.find('[data-id=' + service.id + ']').removeClass('hidden');
            clone.find($el.data('with')).removeClass('hidden');
            if (name === 'service_approval') {
                clone.find(service.type == 2 ? '#approval-ng' : '#approval-all').removeClass('hidden');
            }
        });
        if (services['service_approval'] && services['service_event'] && services['service_seminar']) {
            clone.find("#seminar-" + (services['service_seminar'].type == 2 ? 2 : 1)).removeClass('hidden');
        }

        info('オプション機能確認', clone.show().html(), {
            label: '閉じる',
            className: 'btn-default'
        });
    });
});

$("._link").on('click', function() {
    location.href = $(this).data("url");
});

// Close dropdown menu while opening another one
$(".dropdown").on("click", function() {
    $(this).removeClass("qb-collapsed");
    $(".dropdown").not(this).addClass("qb-collapsed");
});

function validateUI() {
    $(".qb-exhibition-list tfoot span.text").html('開く');
    $(".qb-open tfoot span.text").html('閉じる');
}

$(".qb-exhibition-list-header").on("click", function(e) {
    e.preventDefault();
    var parent = $(this).parents('table.qb-exhibition-list');
    var target = $(parent).find('.qb-exhibition-list-body');
    target.slideToggle('fast');
    parent.toggleClass('qb-open');
    validateUI();
});
validateUI();
$(".qb-open")[0].scrollIntoView();
window.scrollBy(0, -100);
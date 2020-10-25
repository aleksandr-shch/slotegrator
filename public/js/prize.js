$(document).ready(function()
{
    let form = $('#form_prize'), user = $('input[name="user"]');

    form.click(function(event)
    {
        event.preventDefault();

        tryLuck(user.val());
    });
});

function tryLuck(user)
{
    let message = $('#message');
    message.text('');

    $.ajax(
        {
            url: window.location.origin+'/ajax_prize',
            method: 'POST',
            data: {'user': user},
            success: function(response)
            {
                if(response.success)
                {
                    message.css('color', 'green').text(response.message);
                }
                else
                {
                    message.css('color', 'red').text(response.message);
                }
            },
            error(error)
            {
                message.css('color', 'red').text(error);
            }
        });
}
let messages_offset = 0;
let messages_limit = 5;
let fetched_counting = 5;

function show_more_messages() {
    const searchTerm = $('#search_message').val().trim();
    $.ajax({
        url: api_url + '/fetchmessages/' + conversation_id,
        type: 'GET',
        data: {
            offset: messages_offset + 5,
            limit: messages_limit,
            q: searchTerm
        },
        success: function (response) {
            response.messages.forEach(function (message) {
                $('#messages-conversations').append(convertToMessageHTML(message));
            });
            fetched_counting = fetched_counting + response.messages.length
            if (fetched_counting < response.count) {
                messages_offset = messages_offset + 5;
                $('#show_more').show();
            } else {
                $('#show_more').hide();
            }
        },
        error: function (xhr, status, error) {
            console.error(error);
        }
    });
}

let conversation_limit = 3;

function show_more_conversation(additional = 3) {
    conversation_limit = conversation_limit + additional;
    $.ajax({
        url: api_url + '/fetchConversationList/',
        type: 'GET',
        data: {
            limit: conversation_limit,
        },
        success: function (response) {
            $('#conversation_list').html('');
            response.conversation.forEach(function (conversation) {
                $('#conversation_list').append(convertToConversationHTML(conversation));
            });
            if (response.conversation.length < conversation_limit) {
                $('#show_more_conversations').hide();
            } else {
                $('#show_more_conversations').show();
            }
        },
        error: function (xhr, status, error) {
            console.error(error);
        }
    });
}

function refresh_messages() {
    let last_message_id = $('#messages-conversations').children().first().data('message-id');
    let searchTerm = $('#search_message').val().trim();
    if (last_message_id != undefined) {
        $.ajax({
            url: api_url + '/refreshMessageList/' + conversation_id + '/' + last_message_id,
            type: 'GET',
            data: {
                q: searchTerm
            },
            success: function (response) {
                response.messages.forEach(function (message) {
                    $('#messages-conversations').prepend(convertToMessageHTML(message));
                });
                show_more_conversation(0);
                console.log(response.messages.length);
                if (response.messages.length > 0) {
                    // show_more_conversation(0);
                }
            },
            error: function (xhr, status, error) {
                console.error(error);
            }
        });
    }
}

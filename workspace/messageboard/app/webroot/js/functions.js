let offset = 0;
let limit = 5;
let counting = 5;
function show_more_messages() {
    const searchTerm = $('#search_message').val().trim();
    $.ajax({
        url: api_url + '/fetchmessages/' + conversation_id,
        type: 'GET',
        data: {
            offset: offset + 5,
            limit: limit,
            q: searchTerm
        },
        success: function (response) {
            response.messages.forEach(function (message) {
                $('#messages-conversations').append(convertToMessageHTML(message));
            });
            counting = counting + response.messages.length
            if (counting < response.count) {
                offset = offset + 5;
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
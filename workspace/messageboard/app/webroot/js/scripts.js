function convertToMessageHTML(message) {
    return `<div class="message-group border-0 list-group-item list-group-item-action mb-0 p-0 text-${message.position} p${(message.position == 'left' ? 'l' : 'r')}-0" id="${message.id}" style="margin-top:1px;">
            <div class="mb-0 w-100 bg-${(message.position == 'left' ? 'secondary' : 'info')} text-white mb-0" >
                <div class="d-flex w-100 justify-content-between mb-0 p-0 ${(message.position == 'left' ? '' : 'flex-row-reverse')}">
                    <img src="/messageboard/img/${message.avatar}" alt="Profile Image of ${message.name}" class="img-fluid rounded-square" id="profileImage" style="width: 50px; max-height: 100px;">
                    <div class="w-100 mb-0">
                        <h5 class="mb-1">${message.name}</h5>
                        <div class="message-body text-truncate d-inline-block mb-0 px-0 w-100" style="word-break: break-word; text-overflow: ellipsis; max-width:100%; white-space:unset">
                            ${message.message}
                        </div>
                    </div>
                </div>
                <div class="my-0 py-0" style="font-size: 10px;">
                    ${message.created}
                    ${message.position == 'right' ? `<button class="message-delete border-0 badge badge-danger badge-sm text-decoration-none mt-1" style="cursor:pointer; font-size: 10px;" confirm="Are you sure you want to delete this message?" type="button" id="${message.id}">Delete</button>` : ''}
                </div>
            </div>
        </div>`;
}

function convertToConversationHTML(conversation) {
    return `
        <a href="` + conversation_url + '/view/' + conversation.conversation_id + `"
            class="list-group-item list-group-item-action border ${conversation.conversation_id == conversation_id ? 'active-conversation' : ''}">
            <div class="d-flex w-100  justify-content-start">
                <img src="/messageboard/img/${conversation.avatar}" alt="Profile Image of ${conversation.name}" class="img-fluid rounded-square" id="profileImage" style="width: 50px;  margin-right: 10px;">
                <div>
                    <b class="mb-1">${conversation.name}</b>
                    <p class="mb-1">${conversation.message}</p>
                </div>
            </div>
            <small>${conversation.modified}</small>
        </a>
    `;
}
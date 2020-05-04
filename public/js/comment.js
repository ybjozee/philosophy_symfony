const commentTextArea = document.getElementById('cMessage');
const numberOfCommentsLabel = document.getElementById('numberOfComments');
const commentList = document.getElementById('commentList');

const updateNumberOfComments = (numberOfComments) => {
    numberOfCommentsLabel.innerHTML =
        `${numberOfComments} Comment${+numberOfComments > 1 || +numberOfComments === 0 ? 's' : ''}`
};

const getCommentJSX = ({author, content, publishedAt}) => `
    <li class="depth-1 comment" style="color: black">
     <div class="comment__avatar">
        <img width="50" height="50" class="avatar" src="/images/avatars/user-01.jpg" alt="">
     </div>
     <div class="comment__content">
        <div class="comment__info">
            <cite>${author}</cite>
            <div class="comment__meta">
                <time class="comment__time">${publishedAt}</time>
                <a class="reply" href="#">Report</a>
            </div>
        </div>

        <div class="comment__text">
            <p>${content}</p>
        </div>
     </div>
     </li>
    `;

const insertAtTopOfCommentList = comment => {
    const li = document.createElement('li');
    li.setAttribute('class', 'depth-1 comment');
    li.style.color = 'black';
    li.innerHTML = getCommentJSX(comment);
    commentList.insertBefore(li, commentList.childNodes[0]);
    document.querySelector("#comments").scrollIntoView();
};

const showNotification = message => {
    if (Notification.permission === "granted") {
        new Notification(message);
    } else if (Notification.permission !== "denied") {
        Notification.requestPermission().then(permission => {
            if (permission === "granted") {
                new Notification(message);
            }
        });
    }
};

const handleAPIResponse = (comment, data) => {
    if (data) {
        commentTextArea.value = '';
        commentTextArea.blur();
        const {user, publishedAt} = data;
        updateNumberOfComments(parseInt(numberOfCommentsLabel.innerText) + 1);
        insertAtTopOfCommentList({content: comment, author: user, publishedAt});
    }
}

const makeAPIRequest = async ({comment, post}) => fetch('/comment/add', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
    },
    body: JSON.stringify({
        comment,
        post
    }),
})
    .then(response => response.json());

const submitComment = (event, postId) => {
    event.preventDefault();
    const post = +postId;
    const comment = commentTextArea.value;
    makeAPIRequest({comment, post})
        .then(({message, data}) => {
            showNotification(`${!data ? 'Error' : 'Success'}: ${message}`);
            handleAPIResponse(comment, data);
        })
        .catch(error => {
            showNotification(`Error: ${error.message}`);
        });
};

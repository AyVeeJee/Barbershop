import 'bootstrap';

const deleteBtn = document.querySelectorAll('#deleteCommentBtn');
const updateBtn = document.querySelectorAll('#updateCommentBtn');

document.addEventListener('DOMContentLoaded', function () {
    $("#btnModal").on('click', function () {
        let card = $("#barberCommentModal");

        card.modal('show')
        $("#close, #btn-close").on('click', function () {
            card.modal('hide')
        });
    });

    deleteBtn.forEach(button => button.addEventListener('click', delComment, false));
    updateBtn.forEach(button => button.addEventListener('click', updateComment, false));
});

function delComment() {
    let card = $("#deleteCommentModal");
    let employeeId = document.getElementById("delete-comment-btn-save");
    let commentId = this.getAttribute('value');

    employeeId.setAttribute("value", commentId)

    card.modal('show')
    $("#close, #delete-comment-btn-close").on('click', function () {
        card.modal('hide')
    });
}

function updateComment() {
    let card = $("#updateCommentModal");
    let employeeId = document.getElementById("update-comment-btn-save");
    let commentId = this.getAttribute('value');

    employeeId.setAttribute("value", commentId)

    card.modal('show')
    $("#close, #update-comment-btn-close").on('click', function () {
        card.modal('hide')
    });
}




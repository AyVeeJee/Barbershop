import 'bootstrap';

const deleteCommentBtn = document.querySelectorAll('#deleteCommentBtn');
const updateCommentBtn = document.querySelectorAll('#updateCommentBtn');
const deleteAppointmentBtn = document.querySelectorAll('#deleteAppointmentBtn');

document.addEventListener('DOMContentLoaded', function () {
    $("#btnModal").on('click', function () {
        let card = $("#barberCommentModal");

        card.modal('show')
        $("#close, #btn-close").on('click', function () {
            card.modal('hide')
        });
    });

    deleteCommentBtn.forEach(button => button.addEventListener('click', delComment, false));
    updateCommentBtn.forEach(button => button.addEventListener('click', updateComment, false));
    deleteAppointmentBtn.forEach(button => button.addEventListener('click', delAppointment, false));
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

function delAppointment() {
    let card = $("#deleteAppointmentModal");
    let userId = document.getElementById("delete-appointment-btn-save");
    let appointmentId = this.getAttribute('value');

    userId.setAttribute("value", appointmentId)

    card.modal('show')
    $("#close, #delete-appointment-btn-close").on('click', function () {
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




import {Calendar} from "@fullcalendar/core";
import interactionPlugin from "@fullcalendar/interaction";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import listPlugin from "@fullcalendar/list";

import "./index.css";
// import {$ref} from "yarn/lib/cli"; // this will create a calendar.css file reachable to 'encore_entry_link_tags'

let admin_employee = $("#booking_admin_employee");
let user_employee = $("#booking_user_employee");
let datetime = $("#booking_user_beginAt");

datetime.change(function () {
    let data = datetime.val()
    data = new Date(data);

    let currentDate = new Date();
    let tag = document.createElement("p");
    let text = document.createTextNode("Choose a future date");
    let errorMessage = document.getElementById("error");
    let formBtn = document.getElementById("form-btn");

    tag.setAttribute("id", "error");
    tag.classList.add("error");

    if (data >= currentDate && errorMessage !== null) {
        errorMessage.remove();
        formBtn.disabled = false
    } else if (data <= currentDate && errorMessage == null) {
        tag.appendChild(text);
        var element = document.getElementById("booking_user_beginAt");
        element.after(tag);
        $('.error').fadeIn('slow');
    }
})

admin_employee.change(function () {
    let data = {}

    data['booking_employee'] = admin_employee.val()

    $.post('/appointment/admin/ajax', data).then(function (response)
    {
        $("#booking_admin_service").replaceWith(
            $(response).find("#booking_admin_service")
        )
    })
})

user_employee.change(function () {
    let data = {}

    data['booking_employee'] = user_employee.val()

    if (user_employee.val()) {
        $.post('/appointment/user/ajax', data).then(function (response)
        {
            $("#booking_user_service").replaceWith(
                $(response).find("#booking_user_service")
            )
        })
    }
})

document.addEventListener("DOMContentLoaded", () => {

    let calendarEl = document.getElementById("calendar-holder");

    let id = calendarEl.getAttribute('value')

    let { eventsUrl } = calendarEl.dataset;

    let calendar = new Calendar(calendarEl, {
        editable: true,
        eventSources: [
            {
                url: eventsUrl,
                data: calendarEl.dataset.id,
                method: "POST",
                extraParams: {
                    filters: JSON.stringify({id}) // pass your parameters to the subscriber
                },
                failure: () => {
                    // alert("There was an error while fetching FullCalendar!");
                },
            },
        ],
        headerToolbar: {
            left: "prev,next today",
            center: "title",
            right: "dayGridMonth,timeGridWeek,timeGridDay,listWeek"
        },
        initialView: "dayGridMonth",
        navLinks: true, // can click day/week names to navigate views
        plugins: [ interactionPlugin, dayGridPlugin, timeGridPlugin, listPlugin ],
        timeZone: "UTC",
    });

    calendar.render();
});
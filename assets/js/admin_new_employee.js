import 'bootstrap';

const workDayCheck = document.querySelectorAll('#admin_new_employee_days');

document.addEventListener('DOMContentLoaded', function () {
    workDayCheck.forEach(check => check.addEventListener('click', workDay, false));
});

function workDay() {
    const startTime = document.getElementById("admin_new_employee_start_time_" + this.value);
    const endTime = document.getElementById("admin_new_employee_end_time_" + this.value);

    if(this.checked === true) {
        startTime.disabled = false;
        endTime.disabled = false;
    }
    else {
        startTime.disabled = true;
        endTime.disabled = true;
    }
}



import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
        this.updateDateTime();
        this.updateTimeId = setInterval(this.updateDateTime, 1000)
    }

    disconnect() {
        clearInterval(this.updateTimeId);
    }

    updateDateTime() {
        let currentDate = new Date();

        let day = currentDate.getDate();
        let month = currentDate.getMonth() + 1;
        let year = currentDate.getFullYear();

        let hours = currentDate.getHours();
        let minutes = currentDate.getMinutes();
        let seconds = currentDate.getSeconds();

        let formattedDay = (day < 10 ? '0' : '') + day;
        let formattedMonth = (month < 10 ? '0' : '') + month;

        let formattedDate = formattedDay + '.' + formattedMonth + '.' + year;

        let formattedHours = (hours < 10 ? '0' : '') + hours;
        let formattedMinutes = (minutes < 10 ? '0' : '') + minutes;
        let formattedSeconds = (seconds < 10 ? '0' : '') + seconds;

        let formattedTime = formattedHours + ':' + formattedMinutes + ':' + formattedSeconds;

        let timeElement = document.getElementById('datetime');
        if (timeElement) {
            timeElement.innerText = formattedTime + ' ' + formattedDate;
        }
    }
}
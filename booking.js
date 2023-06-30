let xhr = new XMLHttpRequest();

//function which gets the data from the form, checks it, and sends it to the server
function getData(dataSource, divID, cname, phone, unumber, snumber, stname, sbname, dsbname, date, time)
{
    //check if all required fields are filled in
    if(!cname || !phone || !snumber || !stname || !date || !time){
        alert("Please fill in all required fields");
        return;
    }

    //check if the phone number is in the correct format: digits only
    if(!/^\d+$/.test(phone)){
        alert("Please enter a valid phone number - only digits");
        return;
    }

    //check if the phone number is between 10 and 12 digits
    if(!(phone.length >= 10 && phone.length <= 12)) { 
            alert("Please enter a valid phone number - between 10 and 12 digits");
            return;
    }

    //check if the date and time are in the future
    if(!validateDateTime()) {
        return;
    }

    //send the data to the server
    let responsecontainer = document.getElementById(divID);
    let requestbody ="cname="+encodeURIComponent(cname)
        +"&phone="+encodeURIComponent(phone)
        +"&unumber="+encodeURIComponent(unumber)
        +"&snumber="+encodeURIComponent(snumber)
        +"&stname="+encodeURIComponent(stname)
        +"&sbname="+encodeURIComponent(sbname)
        +"&dsbname="+encodeURIComponent(dsbname)
        +"&date="+encodeURIComponent(date)
        +"&time="+encodeURIComponent(time);
    
    xhr.open("POST", dataSource, true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 && xhr.status == 200){
            //display the response from the server
            responsecontainer.innerHTML = xhr.responseText;
        }
    }
    xhr.send(requestbody);
}

//function which checks if the date and time are in the future
function validateDateTime() {
    let dateInput = document.getElementById('datechooser');
    let timeInput = document.getElementById('timechooser');

    let currentDateTime = new Date();

    let hours = String(currentDateTime.getHours()).padStart(2, '0');
    let minutes = String(currentDateTime.getMinutes()).padStart(2, '0');

    let formattedTime = `${hours}:${minutes}`;

    let today = currentDateTime.toISOString().split('T')[0];
    let time = timeInput.value;
    let selectedDate = dateInput.value;

    if(selectedDate < today) {
        alert("Please select a date in the future");
        return false;
    }
    if(selectedDate == today && time < formattedTime) {

        alert("Please select a time in the future");
        return false;
    }

    return true;
}
let xhr = new XMLHttpRequest();

//function which gets the searched data from the server, and displays it in the div with the specified ID
function getData(dataSource, divID, bsearch){
    let responsecontainer = document.getElementsByClassName(divID)[0];
    //check if the booking reference number is in the correct format
    if(checkQueryFormat(bsearch)) {
        let requestbody ="bsearch="+encodeURIComponent(bsearch);
        xhr.open("POST", dataSource, true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function(){
            if(xhr.readyState == 4 && xhr.status == 200){
                responsecontainer.innerHTML = xhr.responseText;
            }
        }
        xhr.send(requestbody);
    }
}

//function which assigns the booking request for a record, and displays a success message
function assignButton(dataSource, buttonID, brn){
    let button = document.getElementById(buttonID);
    let successP = document.getElementById("successP");

    let requestbody ="bookingref="+encodeURIComponent(brn);
    xhr.open("POST", dataSource, true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 && xhr.status == 200){
            //change the status of the booking request to assigned, and disable the assign button
            let statusField = button.parentNode.previousSibling;
            statusField.innerHTML = "Assigned";
            button.disabled = true;
            successP.innerHTML = "Congratulations! Booking request " + brn + " has been assigned!";
        }
    }
    xhr.send(requestbody);

}

//function which checks if the booking reference number is in the correct format: BRN followed by 5 digits
function checkQueryFormat(bsearch){
    if(bsearch != ""){
        if(!bsearch.match(/^BRN\d{5}$/)) {
            alert("Please enter a valid booking reference number - format like BRN12345");
            return false;
        }
    }
    return true;
}
<?php

$host="";
$user="";
$pswd="";
$dbnm="";

//create connection to database
$conn = new mysqli($host, $user, $pswd, $dbnm);

if ($conn->connect_error) {
    echo "Failed to connect to MySQL: ".$conn->connect_error;
}else {
    //if the user has input a booking reference number, search for that booking
    if (isset($_POST["bsearch"])) {
        $bsearch = $_POST["bsearch"];

        $query = "";
        //if the user has not input a booking reference number, search for all bookings within 2 hours
        if ($bsearch == "") {
            $query = "SELECT * FROM requests
            WHERE TIMESTAMP(pickup_date, pickup_time)
             BETWEEN NOW() AND NOW() + INTERVAL 2 HOUR
             AND status = 'unassigned';";
        } else {
            //if the user has input a booking reference number, search for that booking
            $query = "SELECT * FROM requests
            WHERE brn = '$bsearch';";
        }
        $result = $conn->query($query);
        if ($result) {
            //if no bookings are found, display an error message
            if ($result->num_rows == 0) {
                if ($bsearch == "") {
                    echo "No unassigned bookings found within 2 hours.";
                } else {
                    echo "No bookings found with that booking reference number.";
                }
            } else {
                //if bookings are found, display them in a table
                generateTableWithButtons($result);
            }
        }
    } elseif (isset($_POST["bookingref"])) {

        $bookingref = $_POST["bookingref"];
        $query = "UPDATE requests SET `status` = 'assigned' WHERE brn = '$bookingref';";
        $result = $conn->query($query);
    }

    $conn->close();
}

//This function generates a table of bookings with an assign button for each booking, and then sends it to the client to be displayed
function generateTableWithButtons($queryResult)
{
    if ($queryResult) {
        //create a table with the bookings
        $table = "<table><tr>
            <th>Booking Reference Number</th>
            <th>Customer Name</th>
            <th>Phone</th>
            <th>Pickup Suburb</th>
            <th>Destination Suburb</th
            ><th>Pickup Date and Time</th
            ><th>Status</th>
            <th>Assign</th></tr>";

        while ($row = $queryResult->fetch_assoc()) {
            $brn = $row["brn"];
            $cname = $row["cname"];
            $phone = $row["phone"];
            $sbname = $row["sbname"];
            $dsbname = $row["dsbname"];
            $date = $row["pickup_date"];
            $time = $row["pickup_time"];
            $dateTime = date('d/m/Y H:i', strtotime("$date $time"));
            $status = $row["status"];
            $table .= "<tr><td>$brn</td><td>$cname</td><td>$phone</td><td>$sbname</td><td>$dsbname</td><td>$dateTime</td><td>$status</td>";

            //if the booking is unassigned, display an assign button
            if ($status == "unassigned") {
                $table .= "<td><button id=\"$brn\" onclick=\"assignButton('admin.php', '$brn', '$brn')\">Assign</button></td></tr>";
            } else {
                $table .= "<td><button disabled>Assign</button></td></tr>";
            }

        }
        $table .= "</table>";
        $table .= "<p id=\"successP\"></p>";
        echo $table;

    }
}
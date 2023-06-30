<?php
    $cname = $_POST["cname"];
    $phone = $_POST["phone"];
    $unumber = $_POST["unumber"];
    $snumber = $_POST["snumber"];
    $stname = $_POST["stname"];
    $sbname = $_POST["sbname"];
    $dsbname = $_POST["dsbname"];
    $date = $_POST["date"];
    $time = $_POST["time"];

    $host = "";
    $user = "";
    $pswd = "";
    $dbnm = "";

    //create connection to database
    $conn = new mysqli($host, $user, $pswd, $dbnm);

    //check if connection was successful
    if ($conn->connect_error) {
        echo "Failed to connect to MySQL: ".$conn->connect_error;
    }else {
        //get the highest existing booking reference number from the database
        $brnQuery = "SELECT MAX(CAST(SUBSTRING(brn, 4) AS UNSIGNED)) AS max_brn FROM requests";
        $result = $conn->query($brnQuery);

        $row = $result->fetch_assoc();
        $max_brn = $row["max_brn"];

        //create a new booking reference number, which is either 1 or the highest booking reference number + 1
        $new_brn = $max_brn ? $max_brn + 1 : 1;

        //pad the booking reference number with 0s to make it 5 digits long
        $bookingReference = 'BRN' . str_pad($new_brn, 5, '0', STR_PAD_LEFT);
        $currentDate = date('Y-m-d');
        $currentTime = date('H:i:s');

        //insert the booking into the database
        $query = "INSERT INTO requests
            (brn, booking_date, booking_time, `status`, cname, phone, unumber, snumber, stname, sbname, dsbname, pickup_date, pickup_time)
            VALUES ('$bookingReference', '$currentDate', '$currentTime', 'unassigned',
            '$cname', '$phone', '$unumber', '$snumber', '$stname', '$sbname', '$dsbname', '$date', '$time')";

        $result = $conn->query($query);

        //if the booking was successfully inserted, display a success message
        if ($result) {
            $date = date("d/m/Y", strtotime($date));
            echo "<h2>Thank you for your booking!</h2>"
            . "<p>Booking reference number: <strong>$bookingReference</strong></p>"
            . "<p>Pickup time: <strong>$time</strong></p>"
            . "<p>Pickup date: <strong>$date</strong></p>";
        }
    }
    
<?php
function getCalendar() {
        $day_counter = (int)readline("Enter a number of days you wish to put statuses for: \n");
    if ($day_counter  && $day_counter <= 10) {
        $date = new DateTime();
        for ($i = 0; $i < $day_counter; $i++) {
            $readable_date = $date->format('Y-m-d');
            $status = readline("Enter a status (e.g. free, busy) for: " . $readable_date . "\n");
            $array_plan[$readable_date] = $status;
            $date->add(new DateInterval('P1D'));
        }
        echo "Thanks for entering data. Now you can select dates for meeting based on required status \n";
        return $array_plan;
    }
    else { 
        echo "Please enter a number that contains only digits and is bigger than 0, but less than 10 \n"; 
        return getCalendar();
    }
}

function checkAvailability($array) {
    $status_filter = readline("Put a desired status to filter your schedule (e.g. free): ");
    $array_status = array ();
    foreach ($array as $date => $status) {
        if ($status == $status_filter) {
            $confidence = 0;
            do {
                $confidence = readline("Enter a confidence (e.g. probabilty from 1 to 100) of status " . $status . " for: " . $date . "\n") . "%";
            }
            while ((int)$confidence <= 0);
            $array_status[$date] = "I will be " . $status . " with confidence " . $confidence;
        }
    }
    if ($array_status) {
        foreach ($array_status as $date_result => $status_result) {
            echo $status_result . " on " . $date_result . "\n";
        }   
        $check_info = readline("Please confirm that information above is correct (yes/no): \n");
        if ($check_info == "yes") {
            echo "Great, please wait until best date for meeting would be found. \n";
            return $array_status;
        }
        else {
            echo "Ok, let's try again \n";
            return checkAvailability($array);
        }
    }
    else {
        echo "Sorry, but it seems that there are not dates with specified status \n";
        return checkAvailability($array);
    }
}

function addBooking($array_result) {
    if ($array_result) {
        $array_booking = array ();
        $booking_confidence = 0;
        foreach ($array_result as $date_result => $status_result) {
            $status_confidence = strpos($array_result[$date_result], ' ') + 1;
            $booking_probability = (int)rtrim($status_confidence, '%');
            if ($booking_probability > $booking_confidence) {
                $array_booking[0] = $date_result;
                $booking_confidence = $booking_probability;
            }
        }
        sleep(5);
        if($array_booking[0] == date('Y-m-d')) {
            echo "Meeting is scheduled successfully for today \n";
        }
        else echo "Meeting is scheduled successfully on " . implode(", ",$array_booking) . "\n";
    }
    else echo "Sorry, but booking failed due to empty or corrupted array of dates \n";
}

addBooking(checkAvailability(getCalendar()));
?>

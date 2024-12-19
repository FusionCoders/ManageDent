<?php
include_once("../html/sidebar.php");
include_once("../php/db_functions.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule of the Month</title>
    <link rel="stylesheet" href="../css/style_menu.css">
    <link rel="stylesheet" href="../css/schedule_month.css">
</head>

<body class="main-body">
    <?php
        echo '<style>.sidebar .nav_menu .schedule { color: ' . 'white' . '; }</style>';
    ?>
    <section class="main-section">
        <div class="header">

            <h1 class="section-header">Schedule of the Month</h1>
            <form action="" method="GET">
                <?php 
                if (isset($_GET['selectedDate'])) {
                    $selectedDate = urldecode($_GET['selectedDate']);
                    echo '<input id="selectedDate" class="date-picker" type="month" name="selectedDate" value="' . $selectedDate . '" required>';
                } else {
                    echo '<input id="selectedDate" class="date-picker" type="month" name="selectedDate" value="' . date('Y-m') . '" required>';
                }
                ?>
            <button type="submit" class="date-button" name="show_schedules">Select</button>
        </form>
    </div>
        <section id="content" class="calendar-grid">

            <?php
                if (isset($_GET['selectedDate'])) {
                    $selectedDate = urldecode($_GET['selectedDate']);
                } else {
                    $selectedDate = date('Y-m');
                }
                
                $firstDayOfMonth = date('Y-m-01', strtotime($selectedDate));
                $lastDayOfMonth = date('Y-m-t', strtotime($selectedDate));
                
                $currentDate = $firstDayOfMonth;

                $firstDayOfWeek = date('N', strtotime($currentDate));

                $shiftDays = $firstDayOfWeek - 1;
                if ($shiftDays < 0) {
                    $shiftDays += 7;
                }

                $currentDate = date('Y-m-d', strtotime($currentDate . ' -' . $shiftDays . ' day'));
                
                while ($currentDate <= $lastDayOfMonth) {
                    $dayOfWeek = date('N', strtotime($currentDate));
                    
                    $role = $_SESSION['role'];

                    $dentistSchedule = getDentistSchedule($currentDate, $role);
                    $appointments = getAppointmentsDentist($currentDate, $role);
                    
                    echo '<div class="day-square">';
                    
                        echo '<div class="day-header">'; 
                            echo '<div class="weekday">' . date('D', strtotime($currentDate)) . '</div>';
                            echo '<div class="day-number">' . date('d', strtotime($currentDate)) . '</div>';
                        echo '</div>'; 

                        echo '<div class="day-text">';
                            echo '<h3>Working hours</h3>';
                            if (empty($dentistSchedule)) {
                                echo '<p>No schedule defined for this day.</p>';
                            } else {
                                $start_time = date('H:i', strtotime($dentistSchedule['start_time']));
                                echo 'Entry: ' . $start_time . '<br>';

                                $end_time = date('H:i', strtotime($dentistSchedule['end_time']));                        
                                echo 'Exit: ' . $end_time;
                            }
                            
                            echo '<h3 class="appointments-header">Appointments</h3>';
                            if (empty($appointments)) {
                                echo '<p>No appointments scheduled for this day.</p>';
                            } else {
                                foreach ($appointments as $appointment) {
                                    $start_time = date('H:i', strtotime($appointment['start_time']));
                                    echo 'Start time: ' . $start_time . '<br>';
                                    
                                    $end_time = date('H:i', strtotime($appointment['end_time']));                                      
                                    echo 'End time: ' . $end_time . '<br>';
                                }
                            }
                        echo '</div>'; 
                    echo '</div>'; 
                    
                    $currentDate = date('Y-m-d', strtotime($currentDate . ' +1 day'));
                }
            ?>
        </section>
    </section>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>

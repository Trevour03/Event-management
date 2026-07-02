<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dynamic Event Calendar</title>
    <style>
        body {
            background-image: url('image/UpE.jpg');
            background-size: cover;
            background-position: center;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            text-align: center;
        }

        h1 {
            background-color: #333;
            color: white;
            padding: 15px;
            margin: 0;
        } /* Added closing brace for h1 styling */

        .month {
            margin: 20px auto;
            width: 80%;
            background: #fff;
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .month-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #007BFF;
            color: white;
            padding: 10px;
            font-size: 20px;
            font-weight: bold;
        }

        .month-header a {
            color: white;
            text-decoration: none;
            font-size: 18px;
        }

        .calendar {
            width: 100%;
            border-collapse: collapse;
        }

        .calendar th,
        .calendar td {
            border: 1px solid #ddd;
            text-align: center;
            padding: 10px;
            height: 50px;
            vertical-align: top;
        }

        .calendar th {
            background-color: #333;
            color: white;
        }

        .calendar td {
            background-color: #f9f9f9;
        }

        .weekend {
            background-color: #fce4e4;
        }

        .event-day {
            background-color: #28a745; /* Green color */
            color: purple; /* Text color to ensure readability */
            border-radius: 50%;
            font-weight: bold;
        }

        .event-info {
            font-size: 12px;
            color: #155724;
        }
    </style>
</head>
<body>
    <h1>Event Calendar</h1>

    <?php
    include('db.php'); // Include your database connection file

    // Function to fetch events from the database
    function fetch_events($month, $year, $conn) {
        $events = [];
        $sql = "SELECT event_date, event_type, event_place FROM bookings WHERE MONTH(event_date) = ? AND YEAR(event_date) = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $month, $year);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $day = date('j', strtotime($row['event_date'])); // Extract day
            $events[$day] = [
                'event_type' => $row['event_type'],
                'event_place' => $row['event_place']
            ];
        }
        $stmt->close();
        return $events;
    }

    // Function to display the calendar
    function display_calendar($month, $year, $events) {
        $daysOfWeek = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        $firstDayOfMonth = mktime(0, 0, 0, $month, 1, $year);
        $totalDays = date('t', $firstDayOfMonth);
        $startDay = date('w', $firstDayOfMonth);
        $monthName = date('F', $firstDayOfMonth);

        // Display month header with navigation arrows
        echo "<div class='month'>";
        echo "<div class='month-header'>";

        // Back arrow for the previous month
        $prevMonth = $month - 1;
        $prevYear = $year;
        if ($prevMonth < 1) {
            $prevMonth = 12;
            $prevYear = $year - 1;
        }
        echo "<a href='?month=$prevMonth&year=$prevYear'>&laquo; Back</a>";

        // Current month and year
        echo "<span>$monthName $year</span>";

        // Next arrow for the next month
        $nextMonth = $month + 1;
        $nextYear = $year;
        if ($nextMonth > 12) {
            $nextMonth = 1;
            $nextYear = $year + 1;
        }
        echo "<a href='?month=$nextMonth&year=$nextYear'>Next &raquo;</a>";

        echo "</div>";

        // Start calendar table
        echo "<table class='calendar'>";
        echo "<tr>";
        foreach ($daysOfWeek as $day) {
            echo "<th>$day</th>";
        }
        echo "</tr><tr>";

        // Add blank cells for days before the first day of the month
        for ($i = 0; $i < $startDay; $i++) {
            echo "<td></td>";
        }

        // Display days of the month
        for ($day = 1; $day <= $totalDays; $day++) {
            $currentDay = ($startDay + $day - 1) % 7;
            $weekendClass = ($currentDay == 0 || $currentDay == 6) ? 'weekend' : '';
            $eventClass = isset($events[$day]) ? 'event-day' : '';
            $eventInfo = isset($events[$day]) ? "<div class='event-info'>{$events[$day]['event_type']}<br>{$events[$day]['event_place']}</div>" : '';

            echo "<td class='$weekendClass $eventClass'>$day $eventInfo</td>";

            // Start a new row at the end of the week
            if (($startDay + $day) % 7 == 0) {
                echo "</tr><tr>";
            }
        }

        // Add blank cells after the last day of the month
        while (($startDay + $totalDays) % 7 != 0) {
            echo "<td></td>";
            $startDay++;
        }

        echo "</tr></table>";
        echo "</div>";
    }

    // Get current month and year from URL or use the current date
    $currentMonth = isset($_GET['month']) ? (int)$_GET['month'] : date('m');
    $currentYear = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');

    // Fetch events for the current month
    $events = fetch_events($currentMonth, $currentYear, $conn);

    // Display the calendar
    display_calendar($currentMonth, $currentYear, $events);
    ?>

</body>
</html>

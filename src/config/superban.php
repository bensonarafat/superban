<?php 


return [
    // Number of requests before triggering a ban
   'requests_number' => env("SUPERBAN_REQUEST_NUMBER", 200),
    // Duration of the ban in minutes
    'ban_duration' => env("SUPERBAN_DURATION", 1440),
    // in minutes
   'time_interval' => env("SUPERBAN_TIME_INTERVAL", 2),
];
?>
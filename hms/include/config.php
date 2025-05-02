<?php
// Database Configuration
define('DB_SERVER','localhost');
define('DB_USER','root');
define('DB_PASS' ,'');
define('DB_NAME', 'hms');
$con = mysqli_connect(DB_SERVER,DB_USER,DB_PASS,DB_NAME);
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

// Razorpay Configuration
define('RAZORPAY_KEY_ID', 'rzp_test_xxxxxxxxxxxxx'); // Replace with your actual Key ID
define('RAZORPAY_KEY_SECRET', 'yyyyyyyyyyyyyyyyyyyyyyyy'); // Replace with your actual Key Secret
?>

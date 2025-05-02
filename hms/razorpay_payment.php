<?php
session_start();
require('vendor/autoload.php');

use Razorpay\Api\Api;

$apiKey = "";
$apiSecret = "";
$order_id = $_GET['order_id'];

$api = new Api($apiKey, $apiSecret);

// Fetch the order details
$order = $api->order->fetch($order_id);
$amount = $order['amount'] / 100; // Convert back to INR

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Payment</title>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

    <div class="bg-white shadow-lg rounded-lg p-8 w-96 text-center">
        <h1 class="text-2xl font-bold text-gray-700">Complete Your Payment</h1>
        <p class="text-gray-500 mt-2">Secure & Fast Payment</p>

        <div class="mt-6 bg-gray-50 p-4 rounded-lg">
            <h2 class="text-xl font-semibold text-gray-800">Amount: <span class="text-green-600">₹<?php echo $amount; ?></span></h2>
        </div>

        <button id="payButton" class="mt-6 w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg transition-all duration-300">
            Pay Now
        </button>
    </div>

    <script>
        var options = {
            "key": "<?php echo $apiKey; ?>",
            "amount": "<?php echo $order['amount']; ?>",
            "currency": "INR",
            "name": "Your Company Name",
            "description": "Appointment Payment",
            "order_id": "<?php echo $order_id; ?>",
"handler": function (response) {
    let appointmentId = new URLSearchParams(window.location.search).get("appointment_id"); // Get appointment ID from URL
    window.location.href = "payment-success.php?payment_id=" + response.razorpay_payment_id + "&appointment_id=" + appointmentId;
} ,
            "prefill": {
                "name": "abhay gupta",
                "email": "abhaygupta@example.com",
                "contact": "9876543210"
            },
            "theme": {
                "color": "#3399cc"
            }
        };
        
        var rzp1 = new Razorpay(options);
        document.getElementById('payButton').onclick = function(e){
            rzp1.open();
            e.preventDefault();
        }
    </script>
</body>
</html>

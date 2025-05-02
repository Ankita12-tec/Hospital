<?php
session_start();
require('vendor/autoload.php'); // Load Razorpay SDK

use Razorpay\Api\Api;

include('include/config.php');

if (isset($_GET['id'])) {
    $appointmentId = $_GET['id'];

    // Fetch appointment details
    $query = "SELECT consultancyFees FROM appointment WHERE id='$appointmentId'";
    $result = mysqli_query($con, $query);
    $appointment = mysqli_fetch_assoc($result);

    if ($appointment) {
        $amount = $appointment['consultancyFees'] * 100; // Convert to paise (Razorpay works in paise)

        // Initialize Razorpay API with configured credentials
        $api = new Api(RAZORPAY_KEY_ID, RAZORPAY_KEY_SECRET);

        // Create an order
        try {
            $order = $api->order->create([
                'receipt'         => 'order_' . $appointmentId,
                'amount'          => $amount,
                'currency'        => 'INR',
                'payment_capture' => 1 // Auto-capture payment
            ]);

            $_SESSION['order_id'] = $order['id'];

            // Redirect to payment page with Razorpay Order ID
            header("Location: razorpay_payment.php?order_id=" . $order['id'] . "&appointment_id=" . $_GET['id']);

            exit();
        } catch (Exception $e) {
            $_SESSION['msg'] = "Error creating Razorpay order: " . $e->getMessage();
            header('location: appointment-history.php');
        }
    } else {
        $_SESSION['msg'] = "Invalid appointment!";
        header('location: appointment-history.php');
    }
} else {
    $_SESSION['msg'] = "Invalid appointment!";
    header('location: appointment-history.php');
}
?>

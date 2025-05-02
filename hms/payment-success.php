<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Status</title>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <?php
    session_start();
    require('vendor/autoload.php');
    include('include/config.php'); // Database connection

    use Razorpay\Api\Api;

    $apiKey = "";
    $apiSecret = "";

    // Check if both payment_id and appointment_id are received
    if (!isset($_GET['payment_id']) || !isset($_GET['appointment_id'])) {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Invalid Request!'
            }).then(() => { window.location.href = 'appointment-history.php'; });
        </script>";
        exit();
    }

    $payment_id = $_GET['payment_id'];
    $appointmentId = $_GET['appointment_id'];

    $api = new Api($apiKey, $apiSecret);

    // Fetch payment details from Razorpay
    try {
        $payment = $api->payment->fetch($payment_id);

        if ($payment['status'] == 'captured') {
            // Update the paymentStatus to 'done'
            $query = "UPDATE appointment SET paymentStatus = 'done' WHERE id = '$appointmentId'";
            $result = mysqli_query($con, $query);

            if ($result) {
                echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Payment Successful!',
                        text: 'Payment ID: $payment_id',
                        confirmButtonText: 'OK'
                    }).then(() => { window.location.href = 'appointment-history.php'; });
                </script>";
            } else {
                echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Database Error!',
                        text: 'Error updating payment status: " . mysqli_error($con) . "'
                    }).then(() => { window.location.href = 'appointment-history.php'; });
                </script>";
            }
        } else {
            echo "<script>
                Swal.fire({
                    icon: 'warning',
                    title: 'Payment Not Completed!',
                    text: 'Try again or contact support.'
                }).then(() => { window.location.href = 'appointment-history.php'; });
            </script>";
        }
    } catch (Exception $e) {
        $_SESSION['msg'] = "Error verifying payment: " . $e->getMessage();
        header("Location: appointment-history.php");
    }
    ?>
</body>
</html>

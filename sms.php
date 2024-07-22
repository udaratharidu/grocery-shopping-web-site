<?php
// Database connection details
$servername = "localhost"; // Change this to your database server name
$username = "username"; // Change this to your database username
$password = "password"; // Change this to your database password
$dbname = "shop_db"; // Change this to your database name

// Create connection
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
// Set the PDO error mode to exception
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Assuming you have the user's ID available
$user_id = 1; // Change this to the actual user ID

// Fetch cart items for the user from the database
$stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ?");
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($cart_items) {
    // Calculate grand total
    $grand_total = 0;
    foreach ($cart_items as $item) {
        $grand_total += $item['price'] * $item['quantity'];
    }

    // Fetch user details from the database
    $stmt = $conn->prepare("SELECT name, email, address_line1, address_line2, city, state, postal_code, country FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // User details found
        $user_name = $user['name'];
        $user_email = $user['email'];
        $address_line1 = $user['address_line1'];
        $address_line2 = $user['address_line2'];
        $city = $user['city'];
        $state = $user['state'];
        $postal_code = $user['postal_code'];
        $country = $user['country'];

        // Construct the email content
        $subject = "Order Details";
        $message = "Hello $user_name,\n\n";
        $message .= "Thank you for your order. Below are your order details:\n";
        $message .= "Grand Total: $grand_total\n\n";
        $message .= "Shipping Address:\n";
        $message .= "Address Line 1: $address_line1\n";
        $message .= "Address Line 2: $address_line2\n";
        $message .= "City: $city\n";
        $message .= "State: $state\n";
        $message .= "Postal Code: $postal_code\n";
        $message .= "Country: $country\n\n";
        $message .= "If you have any questions, feel free to contact us.\n\n";
        $message .= "Best regards,\nYour Company";

        // Sender and recipient email addresses
        $to = $user_email;
        $from = "yourcompany@example.com";

        // Headers
        $headers = "From: $from" . "\r\n" .
                   "Reply-To: $from" . "\r\n" .
                   "X-Mailer: PHP/" . phpversion();

        // Send email
        $mail_sent = mail($to, $subject, $message, $headers);

        // Check if the email was sent successfully
        if ($mail_sent) {
            echo "Email sent successfully.";
        } else {
            echo "Failed to send email.";
        }
    } else {
        // User not found
        echo "User not found.";
    }
} else {
    // Cart is empty
    echo "Cart is empty.";
}
?>

<?php
// Include configuration file
@include 'config.php';

// Start session
session_start();

// Check if user is logged in
$user_id = $_SESSION['user_id'];
if(!isset($user_id)){
   header('location:login.php');
   exit(); // Terminate further execution
}

// Fetch user information from the database based on user_id
$user_query = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
$user_query->execute([$user_id]);
$user_info = $user_query->fetch(PDO::FETCH_ASSOC);

$name = $user_info['name']; // Assuming the name is stored in a column named 'name'
$email = $user_info['email']; // Assuming the email is stored in a column named 'email'
$phone = $user_info['phone']; // Assuming the phone number is stored in a column named 'phone'

// Handle form submission
if(isset($_POST['send'])){
   // Retrieve form data
   $name = $_POST['name'];
   $email = $_POST['email'];
   $phone = $_POST['phone'];
   $msg = $_POST['msg'];

   // Sanitize form data
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = filter_var($email, FILTER_SANITIZE_EMAIL);
   $phone = filter_var($phone, FILTER_SANITIZE_NUMBER_INT);
   $msg = filter_var($msg, FILTER_SANITIZE_STRING);

   // Insert the message into the database
   $insert_message = $conn->prepare("INSERT INTO `messages` (name, email, phone, message) VALUES (?, ?, ?, ?)");
   $insert_message->execute([$name, $email, $phone, $msg]);

   // Check if the message is successfully inserted
   if($insert_message){
      $message[] = 'Message sent successfully!';
   } else {
      $message[] = 'Failed to send message. Please try again.';
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>contact Us</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<section class="contact">
   <h1 class="title">Get in Touch</h1>
  <!-- Display messages -->
<?php if(!empty($message) && is_array($message)): ?>
   <div class="message">
      <?php foreach($message as $msg): ?>
         <p><?php echo $msg; ?></p>
      <?php endforeach; ?>
   </div>
<?php elseif(!empty($message) && is_string($message)): ?>
   <div class="message">
      <p><?php echo $message; ?></p>
   </div>
<?php endif; ?>

   <form action="" method="POST">
      <input type="text" name="name" class="box" placeholder="Your Name" value="<?php echo $name; ?>" required>
      <input type="email" name="email" class="box" placeholder="Your Email" value="<?php echo $email; ?>" required>
      <input type="tel" name="phone" class="box" placeholder="Your Phone Number" value="<?php echo $phone; ?>" required>
      <textarea name="msg" class="box" placeholder="Enter your message" cols="30" rows="10" required></textarea>
      <input type="submit" value="Send Message" class="btn" name="send" placeholder="<?php= $fetch_message['message']; ?>">
   </form>
</section>








<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
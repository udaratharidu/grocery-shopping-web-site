<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
};

if(isset($_POST['order'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $phone = $_POST['phone'];
   $phone = filter_var($phone, FILTER_SANITIZE_STRING);
   $city = $_POST['city'];
   $city = filter_var($city, FILTER_SANITIZE_STRING);
   $postel = $_POST['postel'];
   $postel = filter_var($postel, FILTER_SANITIZE_STRING);
    $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $method = $_POST['method'];
   $method = filter_var($method, FILTER_SANITIZE_STRING);
   $address =  $_POST['flat'] .' '. $_POST['street'] .' '. $_POST['city'] .' - '. $_POST['pin_code'];
   $address = filter_var($address, FILTER_SANITIZE_STRING);
   $placed_on = date('d-M-Y');

   $cart_total = 0;
   $cart_products[] = '';

   $cart_query = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
   $cart_query->execute([$user_id]);
   if($cart_query->rowCount() > 0){
      while($cart_item = $cart_query->fetch(PDO::FETCH_ASSOC)){
         $cart_products[] = $cart_item['name'].' ( '.$cart_item['quantity'].' )';
         $sub_total = ($cart_item['price'] * $cart_item['quantity']);
         $cart_total += $sub_total;
      };
   };

   $total_products = implode(', ', $cart_products);

   $order_query = $conn->prepare("SELECT * FROM `orders` WHERE name = ? AND number = ? AND email = ? AND method = ? AND address = ? AND total_products = ? AND total_price = ?");
   //$order_query->execute([$name, $phone, $city, $method, $address, $total_products, $cart_total]);

   if($cart_total == 0){
      $message[] = 'Your cart is empty';
   }elseif($order_query->rowCount() > 0){
      $message[] = 'Order placed already!';
   }else{
      $insert_order = $conn->prepare("INSERT INTO `orders`(user_id, name, total_products, total_price, placed_on) VALUES(?,?,?,?,?)");
      $insert_order->execute([$user_id, $name, $total_products, $cart_total, $placed_on]);
      $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
      $delete_cart->execute([$user_id]);
      $message[] = 'Order placed successfully!';
   }

}
// Start session
if (session_status() == PHP_SESSION_NONE) {
   session_start();
}


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
$phone = $user_info['phone']; // Assuming the number is stored in a column named 'number'
$city = $user_info['city'];
$postal = $user_info['postal'];
$address = $user_info['address'];
$email = $user_info['email'];

// Rest of your code remains unchanged...

?> 

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>orders</title>

  
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

  
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<section class="placed-orders">

   <h1 class="title">placed orders</h1>

   <div class="box-container">

   <?php
      $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ?");
      $select_orders->execute([$user_id]);
      if($select_orders->rowCount() > 0){
         while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){ 
   ?>
   <div class="box">
      <p> placed on : <span><?= $fetch_orders['placed_on']; ?></span> </p>
      <p> name : <span> <?php echo $name ?></span> </p>
      <p> number : <span><?php echo $phone ?></span> </p>
      <p> email : <span><?php echo $email ?></span> </p> 
      <p> address : <span><?php echo $address ?></span> </p>
      <!-- <p> payment method : <span><?= $fetch_orders['method']; ?></span> </p> -->
      <p> your orders : <span><?= $fetch_orders['total_products']; ?></span> </p>
      <p> total price : <span>Rs <?= $fetch_orders['total_price']; ?>/-</span> </p>
      <p> payment status : <span style="color:<?php if($fetch_orders['payment_status'] == 'pending'){ echo 'red'; }else{ echo 'green'; }; ?>"><?= $fetch_orders['payment_status']; ?></span> </p>
   </div>
   <?php
      }
   }else{
      echo '<p class="empty">no orders placed yet!</p>';
   }
   ?>

   </div>

</section>









<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
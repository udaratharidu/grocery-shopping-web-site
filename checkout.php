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
//    $email = $_POST['email'];
//   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $method = $_POST['method'];
   $method = filter_var($method, FILTER_SANITIZE_STRING);
   //$address =  $_POST['flat'] .' '. $_POST['street'] .' '. $_POST['city'] .' - '. $_POST['pin_code'];
   //$address = filter_var($address, FILTER_SANITIZE_STRING);
   // Send confirmation email to the user
   // $to = $user_info['email']; // Assuming user's email is stored in a column named 'email'
   // $subject = "Order Confirmation";
   // $message = "Dear $name,<br><br>Your order has been placed successfully!<br><br>Order Details:<br>$total_products<br>Total Price: Rs $cart_total/-<br><br>Payment Method: $method<br><br>Thank you for shopping with us!<br>";
   // $headers = "From: Your Website <noreply@example.com>\r\n";
   // $headers .= "Reply-To: noreply@example.com\r\n";
   // $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

   // mail($to, $subject, $message, $headers);

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

// Rest of your code remains unchanged...

?> 

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Checkout</title>

 
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

  
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<section class="display-orders">

   <?php
      $cart_grand_total = 0;
      $select_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
      $select_cart_items->execute([$user_id]);
      if($select_cart_items->rowCount() > 0){
         while($fetch_cart_items = $select_cart_items->fetch(PDO::FETCH_ASSOC)){
            $cart_total_price = ($fetch_cart_items['price'] * $fetch_cart_items['quantity']);
            $cart_grand_total += $cart_total_price;
   ?>
   <p> <?= $fetch_cart_items['name']; ?> <span>(<?= 'Rs '.$fetch_cart_items['price'].'/- x '. $fetch_cart_items['quantity']; ?>)</span> </p>
   <?php
    }
   }else{
      echo '<p class="empty">Your cart is empty!</p>';
   }
   ?>
   <div class="grand-total">Grand total : <span>Rs <?= $cart_grand_total; ?>/-</span></div>
</section>

<section class="checkout-orders">

   <form action="" method="POST">

      <h3>Place your order</h3>

      <div class="flex">
         <div class="inputBox">
            <span>Your name :</span>
            <input type="text" name="name" placeholder="<?php echo $name ?>" class="box"  >
         </div> 
         <div class="inputBox">
            <span>Your number :</span>
            <input type="number" name="phone" placeholder="+94 <?php echo $phone ?>" class="box" >
         </div>
         <!-- <div class="inputBox">
            <span>Your email :</span>
            <input type="email" name="email" placeholder="enter your email" class="box" required>
         </div> -->
         <div class="inputBox">
            <span>Payment method :</span>
            <select name="method" class="box" required>
               <option value="cash on delivery">Cash on delivery</option>
               <option value="credit card">Credit card</option>
            </select>
         </div>
         <div class="inputBox">
            <span>Address line  :</span>
            <input type="text" name="address" placeholder="<?php echo $address ?>" class="box" >
         </div>
         <!-- <div class="inputBox">
            <span>Address line 02 :</span>
            <input type="text" name="street" placeholder="e.g. street name" class="box" required>
         </div> -->
         <div class="inputBox">
            <span>City :</span>
            <input type="text" name="city" placeholder="<?php echo $city ?>" class="box" >
         </div>
        
         <div class="inputBox">
            <span>Postal code:</span>
            <input type="number" min="0" name="postel" placeholder="<?php echo $postal ?>" class="box" >
         </div>
      </div>

      <input type="submit" name="order" class="btn <?= ($cart_grand_total > 1)?'':'disabled'; ?>" value="place order">

   </form>

</section>








<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
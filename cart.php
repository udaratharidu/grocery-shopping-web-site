<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
};

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE id = ?");
   $delete_cart_item->execute([$delete_id]);
   header('location:cart.php');
}

if(isset($_GET['delete_all'])){
   $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
   $delete_cart_item->execute([$user_id]);
   header('location:cart.php');
}

if(isset($_POST['update_qty'])){
   $cart_id = $_POST['cart_id'];
   $p_qty = $_POST['p_qty'];
   $p_qty = filter_var($p_qty, FILTER_SANITIZE_STRING);
   $update_qty = $conn->prepare("UPDATE `cart` SET quantity = ? WHERE id = ?");
   $update_qty->execute([$p_qty, $cart_id]);
   $message[] = 'cart quantity updated';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>shopping cart</title>

   
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

  
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<section class="shopping-cart">

   <h1 class="title">Cart</h1>

   <div class="box-container">

   <?php
        $grand_total = 0;
        $item_totals = [];
        $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
        $select_cart->execute([$user_id]);
        if ($select_cart->rowCount() > 0) {
            while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
                // Fetch discount details for the current item from the discount table
                $select_discount = $conn->prepare("SELECT * FROM `discount` WHERE id = ?");
                $select_discount->execute([$fetch_cart['pid']]);
                $fetch_discount = $select_discount->fetch(PDO::FETCH_ASSOC);
                
                // Calculate the discount for the current item based on its total price
                if ($fetch_discount) {
                    $discount = ($fetch_cart['price'] * $fetch_discount['discount'] / 100) * $fetch_cart['quantity'];
                } else {
                    // If no discount found, set default discount to 0
                    $discount = 0;
                }
                
                // Calculate the subtotal for the current item after discount
                $sub_total = ($fetch_cart['price'] * $fetch_cart['quantity']) - $discount;
                
                $item_totals[$fetch_cart['id']] = array(
                    'sub_total' => $sub_total,
                    'discount' => $discount
                );
                $grand_total += $sub_total;
                ?>

   
   <form action="" method="POST" class="box">
      <a href="cart.php?delete=<?= $fetch_cart['id']; ?>" class="fas fa-times" onclick="return confirm('delete this from cart?');"></a>
      <a href="view_page.php?pid=<?= $fetch_cart['pid']; ?>" class="fas fa-eye"></a>
      <img src="uploaded_img/<?= $fetch_cart['image']; ?>" alt="">
      <div class="name"><?= $fetch_cart['name']; ?></div>
      <div class="price">Rs<?= $fetch_cart['price']; ?>/-</div>
      <input type="hidden" name="cart_id" value="<?= $fetch_cart['id']; ?>">
      <div class="flex-btn">
         <input type="number" min="1" value="<?= $fetch_cart['quantity']; ?>" class="qty" name="p_qty">
         <input type="submit" value="update" name="update_qty" class="option-btn">
      </div>
     
      <div class="sub-total"> Discount : <span>Rs <?= $discount; ?>/-</span> </div>
      <div class="sub-total"> Sub total : <span>Rs <?= $sub_total; ?>/-</span> </div>
   </form>
   <?php
      
      }
   }else{
      echo '<p class="empty">Your cart is empty</p>';
   }
   ?>
   </div>

   <div class="cart-total">
      <p>Grand total : <span>Rs <?= $grand_total; ?>/-</span></p>
      <a href="shop.php" class="option-btn">Continue shopping</a>
      <a href="cart.php?delete_all" class="delete-btn <?= ($grand_total > 1)?'':'disabled'; ?>">delete all</a>
      <a href="checkout.php" class="btn <?= ($grand_total > 1)?'':'disabled'; ?>">Proceed to checkout</a>
   </div>

</section>








<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
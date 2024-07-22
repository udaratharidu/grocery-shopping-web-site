<?php

@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:login.php');
};

if (isset($_POST['add_product'])) {

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $weight = $_POST['weight'];
   $weight = filter_var($weight, FILTER_SANITIZE_STRING);
   $mweight = $_POST['mweight'];
   $mweight = filter_var($mweight, FILTER_SANITIZE_STRING);
   $price = $_POST['price'];
   $price = filter_var($price, FILTER_SANITIZE_STRING);
   $discount = $_POST['discount'];
   $discount = filter_var($discount, FILTER_SANITIZE_STRING);
   $category = $_POST['category'];
   $category = filter_var($category, FILTER_SANITIZE_STRING);
   $details = $_POST['details'];
   $details = filter_var($details, FILTER_SANITIZE_STRING);

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploaded_img/' . $image;

   $select_discount = $conn->prepare("SELECT * FROM `discount` WHERE name = ?");
   $select_discount->execute([$name]);

   if ($select_discount->rowCount() > 0) {
      $message[] = 'Product name already exists!';
   } else {

      $insert_discount = $conn->prepare("INSERT INTO `discount`(name, weight, mweight, category, details, price, image, discount) VALUES(?,?,?,?,?,?,?,?)");
      $insert_discount->execute([$name, $weight, $mweight, $category, $details, $price, $image, $discount]);

      if ($insert_discount) {
         if ($image_size > 2000000) {
            $message[] = 'Image size is too large!';
         } else {
            move_uploaded_file($image_tmp_name, $image_folder);
            $message[] = 'New product added!';
         }
      }
   }
}

if (isset($_GET['delete'])) {

   $delete_id = $_GET['delete'];
   $select_delete_image = $conn->prepare("SELECT image FROM `discount` WHERE id = ?");
   $select_delete_image->execute([$delete_id]);
   $fetch_delete_image = $select_delete_image->fetch(PDO::FETCH_ASSOC);
   unlink('uploaded_img/' . $fetch_delete_image['image']);
   $delete_discount = $conn->prepare("DELETE FROM `discount` WHERE id = ?");
   $delete_discount->execute([$delete_id]);
   $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE pid = ?");
   $delete_wishlist->execute([$delete_id]);
   $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE pid = ?");
   $delete_cart->execute([$delete_id]);
   header('location:admin_discount.php');
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>products</title>

  
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="add-products">

   <h1 class="title">Add new product</h1>

   <form action="" method="POST" enctype="multipart/form-data">
      <div class="flex">
         <div class="inputBox">
         <input type="text" name="name" class="box" required placeholder="enter product name">
         <select name="category" class="box" required>
            <option value="" selected disabled>Select category</option>
               <option value="vegitables">Vegetables</option>
               <option value="fruits">Fruits</option>
               <option value="meat">Meat</option>
               <option value="fish">Fish</option>
         </select>
         <select name="mweight" class="box" required>
            <option value="" selected disabled>Select weigtht metric (kg)</option>
               <option value="kg">kg</option>
               <option value="g">g</option>
               <option value="nun"> </option> 
         </select>
         </div>
         <div class="inputBox">
         <input type="text" min="0" name="weight" class="box" required placeholder="Enter net weight">
         <input type="number" min="0" name="price" class="box" required placeholder="Enter product price">
         <input type="number" min="0" name="discount" class="box"  placeholder="Enter product discount">
         <input type="file" name="image" required class="box" accept="image/jpg, image/jpeg, image/png">
         </div>
      </div>
      <textarea name="details" class="box" required placeholder="enter product details" cols="30" rows="10"></textarea>
      <input type="submit" class="btn" value="add product" name="add_product">
   </form>

</section>

<section class="show-products">

   <h1 class="title">Products added</h1>

   <div class="box-container">

   <?php
      $show_discount = $conn->prepare("SELECT * FROM `discount`");
      $show_discount->execute();
      if($show_discount->rowCount() > 0){
         while($fetch_discount = $show_discount->fetch(PDO::FETCH_ASSOC)){  
   ?>
   <div class="box">
      <div class="price"><span><?= $fetch_discount['weight']; ?></span><span><?= $fetch_discount['mweight']; ?></span> <br>Rs <?= $fetch_discount['price']; ?>/-</div>
      <div class="discount"><span><?= $fetch_discount['discount']; ?></span> % <br> Discount</div>
      <img src="uploaded_img/<?= $fetch_discount['image']; ?>" alt="">
      <div class="name"><?= $fetch_discount['name']; ?></div>
      <div class="cat"><?= $fetch_discount['category']; ?></div>
      <div class="details"><?= $fetch_discount['details']; ?></div>
      <div class="flex-btn">
         <a href="admin_update_product.php?update=<?= $fetch_discount['id']; ?>" class="option-btn">Update</a>
         <a href="admin_discount.php?delete=<?= $fetch_discount['id']; ?>" class="delete-btn" onclick="return confirm('delete this product?');">Delete</a>
      </div>
   </div>
   <?php
      }
   }else{
      echo '<p class="empty">no products added yet!</p>';
   }
   ?>

   </div>

</section>











<script src="js/script.js"></script>

</body>
</html>
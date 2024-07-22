

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>discount</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
<section class="products">

   <h1 class="title">Offers</h1>

   <div class="box-container">

   <?php
      $select_discount = $conn->prepare("SELECT * FROM `discount` LIMIT 6");
      $select_discount->execute();
      if($select_discount->rowCount() > 0){
         while($fetch_discount = $select_discount->fetch(PDO::FETCH_ASSOC)){ 
   ?>
   <form action="" class="box" method="POST">
      <div class="price"><span><?= $fetch_discount['weight']; ?></span><span><?= $fetch_discount['mweight']; ?></span> <br>Rs <span><?= $fetch_discount['price']; ?></span>/-</div>
      <div class="discount"><span><?= $fetch_discount['discount']; ?></span> % <br> Discount</div>
      <img src="uploaded_img/<?= $fetch_discount['image']; ?>" alt="">
      <div class="name"><?= $fetch_discount['name']; ?></div>
      <input type="hidden" name="pid" value="<?= $fetch_discount['id']; ?>">
      <input type="hidden" name="p_name" value="<?= $fetch_discount['name']; ?>">
      <input type="hidden" name="p_price" value="<?= $fetch_discount['price']; ?>">
      <input type="hidden" name="p_image" value="<?= $fetch_discount['image']; ?>">
      <input type="number" min="1" value="1" name="p_qty" class="qty">
      <input type="submit" value="add to wishlist" class="option-btn" name="add_to_wishlist">
      <input type="submit" value="add to cart" class="btn" name="add_to_cart">
   </form>
   <?php
      }
   }else{
      echo '<p class="empty">No products added yet!</p>';
   }
   ?>

   </div>

</section>
<script src="js/script.js"></script>

</body>
</html>
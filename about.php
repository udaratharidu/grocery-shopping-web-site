<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>about</title>

   
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

  
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<section class="about">

   <div class="row">

      <div class="box">
         <img src="images/about_1.png" alt="">
         <h3>why choose us?</h3>
         <p>Choose us for your grocery needs because we offer a seamless online shopping experience with a wide selection of fresh produce . Our platform prioritizes convenience, with user-friendly navigation and speedy delivery options. Plus, we provide competitive prices, regular discounts, and exceptional customer service to ensure your satisfaction every time you shop.</p>
         <a href="contact.php" class="btn">contact us</a>
      </div>

      <div class="box">
         <img src="images/about_2.png" alt="">
         <h3>what we provide?</h3>
         <p>We pride ourselves on providing the freshest selection of fruits, vegetables, and meats to meet your culinary needs. From crisp, seasonal produce to premium cuts of meat, we source only the highest quality ingredients to ensure your meals are delicious and nutritious. Our commitment to freshness extends to every order, as we meticulously handpick and deliver your groceries directly to your doorstep, ensuring your satisfaction with every bite.</p>
         <a href="shop.php" class="btn">our shop</a>
      </div>

   </div>

</section>

<section class="reviews">

   <h1 class="title">clients reivews</h1>

   <div class="box-container">

      <div class="box">
         <img src="images/pic-1.png" alt="">
         <p>I've been using this grocery store for months now, and I couldn't be happier! The produce is always fresh, and the delivery is reliable. Highly recommend!.</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
         </div>
         <h3>john deo</h3>
      </div>

      <div class="box">
         <img src="images/pic-2.png" alt="">
         <p>As a busy professional, I rely on this grocery store for all my cooking needs. Their wide selection of fruits, vegetables, and meats makes meal planning a breeze. Plus, their customer service is top-notch.</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
         </div>
         <h3>Srah L</h3>
      </div>

      <div class="box">
         <img src="images/pic-3.png" alt="">
         <p>I've tried several online grocery platforms, but this one stands out for its quality and convenience. The meat selection is superb, and the prices are competitive. Definitely my go-to for grocery shopping.</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
         </div>
         <h3>john M</h3>
      </div>

      <div class="box">
         <img src="images/pic-4.png" alt="">
         <p>I love the ease of shopping on this platform. The interface is intuitive, and the checkout process is seamless. Plus, the deliveries always arrive on time, and the produce is consistently fresh. Five stars.</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
         </div>
         <h3>Emily K</h3>
      </div>

      <div class="box">
         <img src="images/pic-5.png" alt="">
         <p>I'm impressed by the level of care put into selecting the produce. Everything looks and tastes so fresh! This grocery store has become my trusted source for healthy ingredients for my family.</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
         </div>
         <h3>Michael H</h3>
      </div>

      <div class="box">
         <img src="images/pic-6.png" alt="">
         <p>I'm a discerning shopper when it comes to quality, and this grocery store never disappoints. Whether it's organic fruits or grass-fed meats, I can always count on finding premium options here. Keep up the great work.</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
         </div>
         <h3>Lisa W</h3>
      </div>

   </div>

</section>









<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
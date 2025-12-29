<!DOCTYPE html>
<html lang="en">
<?php
include("connection/connect.php");
include_once 'product-action.php';
error_reporting(0);
session_start();


function function_alert()
{


    echo "<script>alert('Thank you. Your Order has been placed!');</script>";
    echo "<script>window.location.replace('your_orders.php');</script>";
}

if (empty($_SESSION["user_id"])) {
    header('location:login.php');
} else {


    foreach ($_SESSION["cart_item"] as $item) {

        $item_total += ($item["price"] * $item["quantity"]);

        if ($_POST['submit']) {
            // Verify price from database before inserting
            $stmt = $db->prepare("SELECT price FROM dishes WHERE d_id = ?");
            $stmt->bind_param('i', $item['d_id']);
            $stmt->execute();
            $result = $stmt->get_result();
            $dish = $result->fetch_assoc();

            // Check if price matches
            if ($dish && $dish['price'] == $item['price']) {
                // Use prepared statement to prevent SQL injection
                $stmt = $db->prepare("INSERT INTO users_orders(u_id, title, quantity, price) VALUES(?, ?, ?, ?)");
                $stmt->bind_param('isid', $_SESSION["user_id"], $item["title"], $item["quantity"], $item["price"]);
                $stmt->execute();
            } else {
                die("Error: Price mismatch detected. Please refresh and try again.");
            }

            unset($_SESSION["cart_item"]);
            unset($item["title"]);
            unset($item["quantity"]);
            unset($item["price"]);
            $success = "Thank you. Your order has been placed!";

            function_alert();
        }
    }
?>


    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" href="#">
        <title>Checkout</title>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/font-awesome.min.css" rel="stylesheet">
        <link href="css/animsition.min.css" rel="stylesheet">
        <link href="css/animate.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet">
        <link href="css/dark-theme.css" rel="stylesheet">
    </head>

    <body>

        <div class="site-wrapper">
            <header id="header" class="header-scroll top-header headrom">
                <nav class="navbar navbar-dark">
                    <div class="container">
                        <button class="navbar-toggler hidden-lg-up" type="button" data-toggle="collapse"
                            data-target="#mainNavbarCollapse">&#9776;</button>
                        <a class="navbar-brand" href="index.php"> <img class="img-rounded" src="images/quick bites.png" alt="Quick Bites" width="200" height="45"> </a>
                        <div class="collapse navbar-toggleable-md  float-lg-right" id="mainNavbarCollapse">
                            <ul class="nav navbar-nav">
                                <li class="nav-item"> <a class="nav-link active" href="index.php">Home <span
                                            class="sr-only">(current)</span></a> </li>
                                <li class="nav-item"> <a class="nav-link active" href="restaurants.php">Restaurants <span
                                            class="sr-only"></span></a> </li>

                                <?php
                                if (empty($_SESSION["user_id"])) {
                                    echo '<li class="nav-item"><a href="login.php" class="nav-link active">Login</a> </li>
							  <li class="nav-item"><a href="registration.php" class="nav-link active">Register</a> </li>';
                                } else {


                                    echo  '<li class="nav-item"><a href="your_orders.php" class="nav-link active">My Orders</a> </li>';
                                    echo  '<li class="nav-item"><a href="logout.php" class="nav-link active">Logout</a> </li>';
                                }

                                ?>

                            </ul>
                        </div>
                    </div>
                </nav>
            </header>
            <div class="page-wrapper">
                <div class="top-links">
                    <div class="container">
                        <ul class="row links">

                            <li class="col-xs-12 col-sm-4 link-item"><span>1</span><a href="restaurants.php">Choose
                                    Restaurant</a></li>
                            <li class="col-xs-12 col-sm-4 link-item "><span>2</span><a href="#">Pick Your favorite food</a>
                            </li>
                            <li class="col-xs-12 col-sm-4 link-item active"><span>3</span><a href="checkout.php">Order and
                                    Pay</a></li>
                        </ul>
                    </div>
                </div>

                <div class="container">

                    <span style="color:green;">
                        <?php echo $success; ?>
                    </span>

                </div>




                <div class="container m-t-30">
                    <form action="" method="post">
                        <div class="widget clearfix" style="animation: fadeInUp 0.6s ease;">

                            <div class="widget-body"
                                style="background: white; border-radius: 12px; padding: 40px; box-shadow: 0 8px 25px rgba(0,0,0,0.1);">
                                <form method="post" action="#">
                                    <div class="row">

                                        <div class="col-sm-12">
                                            <div class="cart-totals margin-b-20">
                                                <div class="cart-totals-title"
                                                    style="background: url('images/footer_pattern.png'); background-size: cover; padding: 20px; border-radius: 12px 12px 0 0; margin: -40px -40px 20px -40px;">
                                                    <h4
                                                        style="color: white; margin: 0; display: flex; align-items: center;">
                                                        <i class="fa fa-shopping-bag" style="margin-right: 10px;"></i>Cart
                                                        Summary
                                                    </h4>
                                                </div>
                                                <div class="cart-totals-fields">

                                                    <table class="table" style="margin-bottom: 0;">
                                                        <tbody>



                                                            <tr style="border-bottom: 1px solid #e0e0e0;">
                                                                <td style="padding: 15px 0; font-size: 16px; color: #666;">
                                                                    Cart Subtotal</td>
                                                                <td
                                                                    style="padding: 15px 0; font-size: 16px; font-weight: 600; color: #333; text-align: right;">
                                                                    Rs. <?php echo $item_total; ?></td>
                                                            </tr>
                                                            <tr style="border-bottom: 1px solid #e0e0e0;">
                                                                <td style="padding: 15px 0; font-size: 16px; color: #666;">
                                                                    Delivery Charges</td>
                                                                <td
                                                                    style="padding: 15px 0; font-size: 16px; font-weight: 600; color: #28a745; text-align: right;">
                                                                    Free</td>
                                                            </tr>
                                                            <tr style="background: #f8f9fa;">
                                                                <td style="padding: 20px 15px; font-size: 18px;">
                                                                    <strong>Total</strong>
                                                                </td>
                                                                <td
                                                                    style="padding: 20px 15px; font-size: 24px; font-weight: 700; color: #667eea; text-align: right;">
                                                                    <strong> Rs. <?php echo $item_total; ?></strong>
                                                                </td>
                                                            </tr>
                                                        </tbody>




                                                    </table>
                                                </div>
                                            </div>
                                            <div class="payment-option" style="margin-top: 30px;">
                                                <h5 style="margin-bottom: 20px; font-weight: 700; color: #333;"><i
                                                        class="fa fa-credit-card"
                                                        style="margin-right: 10px; color: #667eea;"></i>Payment Method</h5>
                                                <ul class="list-unstyled">
                                                    <li style="margin-bottom: 15px;">
                                                        <label class="custom-control custom-radio"
                                                            style="display: flex; align-items: center; padding: 15px; border: 2px solid #667eea; border-radius: 8px; cursor: pointer; background: #f8f9ff; transition: all 0.3s;">
                                                            <input name="mod" id="radioStacked1" checked value="COD"
                                                                type="radio" class="custom-control-input"
                                                                style="margin-right: 10px;"> <span
                                                                class="custom-control-indicator"></span> <span
                                                                class="custom-control-description"
                                                                style="font-weight: 600; color: #667eea;"><i
                                                                    class="fa fa-money" style="margin-right: 8px;"></i>Cash
                                                                on Delivery</span>
                                                        </label>
                                                    </li>
                                                    <li>
                                                        <label class="custom-control custom-radio"
                                                            style="display: flex; align-items: center; padding: 15px; border: 2px solid #e0e0e0; border-radius: 8px; cursor: not-allowed; background: #f5f5f5; opacity: 0.6;">
                                                            <input name="mod" type="radio" value="esewa" disabled
                                                                class="custom-control-input" style="margin-right: 10px;">
                                                            <span class="custom-control-indicator"></span> <span
                                                                class="custom-control-description"
                                                                style="font-weight: 600; color: #666;"><i
                                                                    class="fa fa-credit-card"
                                                                    style="margin-right: 8px;"></i>eSewa <img
                                                                    src="images/esewa.png" alt="eSewa" width="70"
                                                                    style="margin-left: 10px;"></span> </label>
                                                    </li>
                                                </ul>
                                                <p class="text-xs-center" style="margin-top: 30px;"> <input type="submit"
                                                        onclick="return confirm('Do you want to confirm the order?');"
                                                        name="submit" class="btn btn-block" value="Place Order Now"
                                                        style="background: url('images/footer_pattern.png'); background-size: cover; color: white; border: none; padding: 18px; font-size: 18px; font-weight: 700; border-radius: 12px; transition: all 0.3s ease; box-shadow: 0 6px 20px rgba(0, 0, 0, 0.4);"
                                                        onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 25px rgba(0, 0, 0, 0.6)'"
                                                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 6px 20px rgba(0, 0, 0, 0.4)'">
                                                </p>
                                            </div>
                                </form>
                            </div>
                        </div>

                </div>
            </div>
            </form>
        </div>

        <footer class="footer">
            <div class="row bottom-footer">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12 col-sm-3 payment-options color-gray">
                            <h5>Payment Options</h5>
                            <ul>
                                <li>
                                    <a href="#"> <img src="images/esewa.png" alt="eSewa" style="height: 30px;"> </a>
                                </li>
                                <li>
                                    <a href="#"> <img src="images/nic asia.png" alt="NIC Asia Bank"> </a>
                                </li>
                                <li>
                                    <a href="#"> <img src="images/himalayan bank.jpg" alt="Himalayan Bank"> </a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-xs-12 col-sm-4 address color-gray">
                            <h5>Address</h5>
                            <p>Itahari-20,Tarahara,Nepal</p>
                            <h5>Phone: +977 9844084827</a></h5>
                        </div>
                        <div class="col-xs-12 col-sm-5 additional-info color-gray">
                            <h5>Addition informations</h5>
                            <p>Join thousands of other restaurants who benefit from having partnered with us.</p>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </footer>
        </div>
        </div>

        <script src="js/jquery.min.js"></script>
        <script src="js/tether.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/animsition.min.js"></script>
        <script src="js/bootstrap-slider.min.js"></script>
        <script src="js/jquery.isotope.min.js"></script>
        <script src="js/headroom.js"></script>
        <script src="js/foodpicky.min.js"></script>
    </body>

</html>

<?php
}
?>
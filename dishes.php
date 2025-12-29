<!DOCTYPE html>
<html lang="en">
<?php
include("connection/connect.php"); 
error_reporting(0);
session_start();

include_once 'product-action.php'; 

?>


<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="#">
    <title>Dishes</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animsition.min.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/dark-theme.css" rel="stylesheet"> </head>

<body>
    
        <header id="header" class="header-scroll top-header headrom">
            <nav class="navbar navbar-dark">
                <div class="container">
                    <button class="navbar-toggler hidden-lg-up" type="button" data-toggle="collapse" data-target="#mainNavbarCollapse">&#9776;</button>
                    <a class="navbar-brand" href="index.php"> <img class="img-rounded" src="images/quick%20bites.png" alt="Quick Bites" width="200" height="45"> </a>
                    <div class="collapse navbar-toggleable-md  float-lg-right" id="mainNavbarCollapse">
                       <ul class="nav navbar-nav">
                            <li class="nav-item"> <a class="nav-link active" href="index.php">Home <span class="sr-only">(current)</span></a> </li>
                            <li class="nav-item"> <a class="nav-link active" href="restaurants.php">Restaurants <span class="sr-only"></span></a> </li>
                            
							<?php
						if(empty($_SESSION["user_id"]))
							{
								echo '<li class="nav-item"><a href="login.php" class="nav-link active">Login</a> </li>
							  <li class="nav-item"><a href="registration.php" class="nav-link active">Register</a> </li>';
							}
						else
							{
									
									
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
                      
                        <li class="col-xs-12 col-sm-4 link-item"><span>1</span><a href="restaurants.php">Choose Restaurant</a></li>
                        <li class="col-xs-12 col-sm-4 link-item active"><span>2</span><a href="dishes.php?res_id=<?php echo $_GET['res_id']; ?>">Pick Your favorite food</a></li>
                        <li class="col-xs-12 col-sm-4 link-item"><span>3</span><a href="#">Order and Pay</a></li>
                        
                    </ul>
                </div>
            </div>
			<?php 
			// Validate and sanitize restaurant ID
			$res_id = filter_var($_GET['res_id'] ?? 0, FILTER_VALIDATE_INT);
			if(!$res_id || $res_id <= 0) {
				header('location:restaurants.php');
				exit();
			}
			
			// Use prepared statement to prevent SQL injection
			$stmt = $db->prepare("SELECT * FROM restaurant WHERE rs_id = ?");
			$stmt->bind_param('i', $res_id);
			$stmt->execute();
			$ress = $stmt->get_result();
			$rows = $ress->fetch_array();
			
			if(!$rows) {
				header('location:restaurants.php');
				exit();
			}
			?>
            <section class="inner-page-hero bg-image" data-image-src="images/img/restrrr.png" style="position: relative; overflow: hidden;">
                <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(30, 42, 56, 0.95);"></div>
                <div class="profile" style="position: relative; z-index: 1; padding: 60px 0;">
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-3 profile-img text-center">
                                <div class="image-wrap" style="width: 200px; height: 200px; margin: 0 auto; border-radius: 50%; overflow: hidden; border: 6px solid white; box-shadow: 0 15px 40px rgba(0,0,0,0.4); animation: fadeInLeft 0.8s ease; background: white;">
                                    <figure style="margin: 0;"><?php echo '<img src="admin/Res_img/'.$rows['image'].'" alt="Restaurant logo" style="width: 100%; height: 100%; object-fit: cover;">'; ?></figure>
                                </div>
                            </div>
							
                            <div class="col-xs-12 col-sm-12 col-md-8 col-lg-9 profile-desc">
                                <div class="right-text white-txt" style="animation: fadeInRight 0.8s ease; padding: 20px 0;">
                                    <h1 style="color: white; font-weight: 700; margin: 0 0 20px 0; font-size: 48px; text-shadow: 0 2px 10px rgba(0,0,0,0.3);"><?php echo $rows['title']; ?></h1>
                                    <p style="color: rgba(255,255,255,0.95); font-size: 20px; margin: 0; display: flex; align-items: center; flex-wrap: wrap; line-height: 1.6;">
                                        <i class="fa fa-map-marker" style="margin-right: 12px; font-size: 24px; color: #fff;"></i>
                                        <span style="font-weight: 500;"><?php echo $rows['address']; ?></span>
                                    </p>   
                                </div>
                            </div>
							
							
                        </div>
                    </div>
                </div>
            </section>
            <div class="breadcrumb">
                <div class="container">
                   
                </div>
            </div>
            <div class="container m-t-30">
                <div class="row">
                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-3">
                        
                         <div class="widget widget-cart" style="position: sticky; top: 100px; border-radius: 12px; overflow: hidden; box-shadow: 0 8px 25px rgba(0,0,0,0.1); animation: slideInRight 0.5s ease;">
                                <div class="widget-heading" style="background: url('images/footer_pattern.png'); background-size: cover; padding: 20px;">
                                    <h3 class="widget-title" style="color: white; margin: 0; display: flex; align-items: center;">
                                 <i class="fa fa-shopping-cart" style="margin-right: 10px;"></i> Your Cart
                              </h3>
							  				  
							  
                                    <div class="clearfix"></div>
                                </div>
                                <div class="order-row bg-white">
                                    <div class="widget-body" style="padding: 20px;">
									
									
	<?php

$item_total = 0;

foreach ($_SESSION["cart_item"] as $item)  
{
?>									
									
                                        <div class="title-row">
										<?php echo $item["title"]; ?><a href="dishes.php?res_id=<?php echo $_GET['res_id']; ?>&action=remove&id=<?php echo $item["d_id"]; ?>" >
										<i class="fa fa-trash pull-right"></i></a>
										</div>
										
                                        <div class="form-group row no-gutter">
                                            <div class="col-xs-8">
                                                 <input type="text" class="form-control b-r-0" value=<?php echo "Rs. ".$item["price"]; ?> readonly id="exampleSelect1">
                                                   
                                            </div>
                                            <div class="col-xs-4">
                                               <input class="form-control" type="text" readonly value='<?php echo $item["quantity"]; ?>' id="example-number-input"> </div>
                                        
									  </div>
									  
	<?php
$item_total += ($item["price"]*$item["quantity"]); 
}
?>								  
									  
									  
									  
                                    </div>
                                </div>
                               
                         
                             
                                <div class="widget-body" style="padding: 25px; background: #f8f9fa; border-radius: 0 0 12px 12px;">
                                    <div class="price-wrap text-xs-center">
                                        <p style="color: #666; font-size: 14px; margin-bottom: 10px; font-weight: 600;">TOTAL AMOUNT</p>
                                        <h3 class="value" style="margin: 15px 0;"><strong style="color: #667eea; font-size: 32px;">Rs. <?php echo $item_total; ?></strong></h3>
                                        <p style="color: #28a745; font-weight: 600; margin-bottom: 20px;"><i class="fa fa-truck" style="margin-right: 5px;"></i>Free Delivery!</p>
                                        <?php
                                        if($item_total==0){
                                        ?>
                                        <div style="padding: 20px; text-align: center;">
                                            <i class="fa fa-shopping-cart" style="font-size: 50px; color: #e0e0e0; margin-bottom: 15px;"></i>
                                            <p style="color: #999; margin-bottom: 0;">Your cart is empty</p>
                                        </div>
                                        <a href="#" class="btn btn-lg" style="background: #e0e0e0; color: #999; border: none; padding: 15px; border-radius: 25px; font-weight: 600; width: 100%; cursor: not-allowed;">Checkout</a>

                                        <?php
                                        }
                                        else{   
                                        ?>
                                        <a href="checkout.php?res_id=<?php echo $_GET['res_id'];?>&action=check" class="btn btn-lg" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; border: none; padding: 15px; border-radius: 25px; font-weight: 600; width: 100%; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 6px 20px rgba(40, 167, 69, 0.5)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(40, 167, 69, 0.3)'"><i class="fa fa-check-circle" style="margin-right: 8px;"></i>Proceed to Checkout</a>
                                        <?php   
                                        }
                                        ?>

                                    </div>
                                </div>
								
						
								
								
                            </div>
                    </div>

                    <div class="col-md-8">
                      
             
                        <div class="menu-widget" id="2" style="animation: fadeInUp 0.6s ease;">
                            <div class="widget-heading" style="background: white; padding: 20px; border-radius: 12px 12px 0 0; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
                                <h3 class="widget-title text-dark" style="margin: 0; display: flex; align-items: center; justify-content: space-between;">
                              <span><i class="fa fa-cutlery" style="margin-right: 10px; color: #667eea;"></i>MENU</span> <a class="btn btn-link" data-toggle="collapse" href="#popular2" aria-expanded="true" style="color: #667eea;">
                              <i class="fa fa-angle-right pull-right"></i>
                              <i class="fa fa-angle-down pull-right"></i>
                              </a>
                           </h3>
                                <div class="clearfix"></div>
                            </div>
                            <div class="collapse in" id="popular2" style="background: white; border-radius: 0 0 12px 12px; padding: 20px; box-shadow: 0 8px 25px rgba(0,0,0,0.1);">
						<?php  
									$stmt = $db->prepare("select * from dishes where rs_id='$_GET[res_id]'");
									$stmt->execute();
									$products = $stmt->get_result();
									if (!empty($products)) 
									{
									foreach($products as $product)
										{
						
													
													 
													 ?>
                                <div class="food-item" style="background: white; border-radius: 12px; padding: 20px; margin-bottom: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); transition: all 0.3s ease; border: 2px solid transparent;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 25px rgba(102, 126, 234, 0.2)'; this.style.borderColor='#667eea';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(0,0,0,0.08)'; this.style.borderColor='transparent';">
                                    <div class="row align-items-center">
                                        <div class="col-xs-12 col-sm-12 col-lg-8">
										<form method="post" action='dishes.php?res_id=<?php echo $_GET['res_id'];?>&action=add&id=<?php echo $product['d_id']; ?>'>
                                            <div class="rest-logo pull-left" style="width: 100px; height: 100px; border-radius: 12px; overflow: hidden; margin-right: 20px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                                                <a class="restaurant-logo pull-left" href="#"><?php echo '<img src="admin/Res_img/dishes/'.$product['img'].'" alt="Food logo" style="width: 100%; height: 100%; object-fit: cover;">'; ?></a>
                                            </div>
                                
                                            <div class="rest-descr">
                                                <h6 style="margin: 0 0 10px 0; font-weight: 700; color: #333;"><a href="#" style="color: #333; text-decoration: none; transition: color 0.3s;" onmouseover="this.style.color='#667eea'" onmouseout="this.style.color='#333'"><?php echo $product['title']; ?></a></h6>
                                                <p style="color: #666; margin: 0; font-size: 14px;"> <?php echo $product['slogan']; ?></p>
                                            </div>
                           
                                        </div>
                               
                                        <div class="col-xs-12 col-sm-12 col-lg-4 text-center item-cart-info" style="padding: 15px;"> 
										<div style="margin-bottom: 15px;">
                                            <span class="price" style="font-size: 24px; font-weight: 700; color: #667eea; display: block; margin-bottom: 10px;">Rs. <?php echo $product['price']; ?></span>
                                        </div>
                                        <div style="display: flex; align-items: center; justify-content: center; gap: 10px; margin-bottom: 15px;">
                                            <label style="margin: 0; font-weight: 600; color: #666;">Qty:</label>
										  <input class="form-control" type="number" name="quantity" value="1" min="1" style="width: 70px; border-radius: 8px; border: 2px solid #e0e0e0; padding: 8px; text-align: center;" />
                                        </div>
										  <input type="submit" class="btn" value="Add To Cart" style="background: url('images/footer_pattern.png'); background-size: cover; color: white; border: none; padding: 12px 25px; border-radius: 25px; font-weight: 600; width: 100%; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);" onmouseover="this.style.transform='scale(1.05)'; this.style.boxShadow='0 6px 20px rgba(0, 0, 0, 0.5)'" onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 4px 15px rgba(0, 0, 0, 0.3)'" />
										</div>
										</form>
                                    </div>
              
                                </div>
                
								
								<?php
									  }
									}
									
								?>
								
								
                              
                            </div>
             
                        </div>
            
                       
                    </div>
                    
                </div>
     
            </div>
        
            <footer class="footer">
                <div class="container">
           
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
                                    <h5>Phone: +977 9844084827</a></h5> </div>
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

 
    <div class="modal fade" id="order-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                <div class="modal-body cart-addon">
                    <div class="food-item white">
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-lg-6">
                                <div class="item-img pull-left">
                                    <a class="restaurant-logo pull-left" href="#"><img src="http://placehold.it/70x70" alt="Food logo"></a>
                                </div>
              
                                <div class="rest-descr">
                                    <h6><a href="#">Sandwich de Alegranza Grande Menü (28 - 30 cm.)</a></h6> </div>
               
                            </div>
           
                            <div class="col-xs-6 col-sm-2 col-lg-2 text-xs-center"> <span class="price pull-left">$ 2.99</span></div>
                            <div class="col-xs-6 col-sm-4 col-lg-4">
                                <div class="row no-gutter">
                                    <div class="col-xs-7">
                                        <select class="form-control b-r-0" id="exampleSelect2">
                                            <option>Size SM</option>
                                            <option>Size LG</option>
                                            <option>Size XL</option>
                                        </select>
                                    </div>
                                    <div class="col-xs-5">
                                        <input class="form-control" type="number" value="0" id="quant-input-2"> </div>
                                </div>
                            </div>
                        </div>
               
                    </div>
              
                    <div class="food-item">
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-lg-6">
                                <div class="item-img pull-left">
                                    <a class="restaurant-logo pull-left" href="#"><img src="http://placehold.it/70x70" alt="Food logo"></a>
                                </div>
                    
                                <div class="rest-descr">
                                    <h6><a href="#">Sandwich de Alegranza Grande Menü (28 - 30 cm.)</a></h6> </div>
                
                            </div>
               
                            <div class="col-xs-6 col-sm-2 col-lg-2 text-xs-center"> <span class="price pull-left">$ 2.49</span></div>
                            <div class="col-xs-6 col-sm-4 col-lg-4">
                                <div class="row no-gutter">
                                    <div class="col-xs-7">
                                        <select class="form-control b-r-0" id="exampleSelect3">
                                            <option>Size SM</option>
                                            <option>Size LG</option>
                                            <option>Size XL</option>
                                        </select>
                                    </div>
                                    <div class="col-xs-5">
                                        <input class="form-control" type="number" value="0" id="quant-input-3"> </div>
                                </div>
                            </div>
                        </div>
            
                    </div>
            
                    <div class="food-item">
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-lg-6">
                                <div class="item-img pull-left">
                                    <a class="restaurant-logo pull-left" href="#"><img src="http://placehold.it/70x70" alt="Food logo"></a>
                                </div>
                       
                                <div class="rest-descr">
                                    <h6><a href="#">Sandwich de Alegranza Grande Menü (28 - 30 cm.)</a></h6> </div>
                 
                            </div>
                
                            <div class="col-xs-6 col-sm-2 col-lg-2 text-xs-center"> <span class="price pull-left">$ 1.99</span></div>
                            <div class="col-xs-6 col-sm-4 col-lg-4">
                                <div class="row no-gutter">
                                    <div class="col-xs-7">
                                        <select class="form-control b-r-0" id="exampleSelect5">
                                            <option>Size SM</option>
                                            <option>Size LG</option>
                                            <option>Size XL</option>
                                        </select>
                                    </div>
                                    <div class="col-xs-5">
                                        <input class="form-control" type="number" value="0" id="quant-input-4"> </div>
                                </div>
                            </div>
                        </div>
                 
                    </div>
               
                    <div class="food-item">
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-lg-6">
                                <div class="item-img pull-left">
                                    <a class="restaurant-logo pull-left" href="#"><img src="http://placehold.it/70x70" alt="Food logo"></a>
                                </div>
                 
                                <div class="rest-descr">
                                    <h6><a href="#">Sandwich de Alegranza Grande Menü (28 - 30 cm.)</a></h6> </div>
                      
                            </div>
                       
                            <div class="col-xs-6 col-sm-2 col-lg-2 text-xs-center"> <span class="price pull-left">$ 3.15</span></div>
                            <div class="col-xs-6 col-sm-4 col-lg-4">
                                <div class="row no-gutter">
                                    <div class="col-xs-7">
                                        <select class="form-control b-r-0" id="exampleSelect6">
                                            <option>Size SM</option>
                                            <option>Size LG</option>
                                            <option>Size XL</option>
                                        </select>
                                    </div>
                                    <div class="col-xs-5">
                                        <input class="form-control" type="number" value="0" id="quant-input-5"> </div>
                                </div>
                            </div>
                        </div>
           
                    </div>
             
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn theme-btn">Add To Cart</button>
                </div>
            </div>
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

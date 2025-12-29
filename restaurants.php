<!DOCTYPE html>
<html lang="en">
<?php
include("connection/connect.php");
error_reporting(0);
session_start();
?>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="#">
    <title>Restaurants</title>
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
                       
                        <li class="col-xs-12 col-sm-4 link-item active"><span>1</span><a href="#">Choose Restaurant</a></li>
                        <li class="col-xs-12 col-sm-4 link-item"><span>2</span><a href="#">Pick Your favorite food</a></li>
                        <li class="col-xs-12 col-sm-4 link-item"><span>3</span><a href="#">Order and Pay</a></li>
                    </ul>
                </div>
            </div>
            <div class="inner-page-hero bg-image" data-image-src="images/footer_pattern.png" style="position: relative; padding: 80px 0; overflow: hidden; background: url('images/footer_pattern.png'); background-size: cover; background-position: center;">
                <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(30, 42, 56, 0.95);"></div>
                <div class="container" style="position: relative; z-index: 1;">
                    <div class="text-center" style="animation: fadeInDown 0.6s ease;">
                        <h1 style="color: white; font-weight: 700; font-size: 42px; margin-bottom: 15px;">
                            <i class="fa fa-cutlery" style="margin-right: 15px;"></i>Our Restaurants
                        </h1>
                        <p style="color: rgba(255,255,255,0.9); font-size: 18px; margin: 0;">Discover amazing food from the best restaurants in town</p>
                    </div>
                </div>
            </div>
            
            <section class="restaurants-page" style="padding: 60px 0; background: #f8f9fa;">
                <div class="container">
                    <!-- Page Header -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div style="background: white; padding: 20px 30px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap;">
                                <div>
                                    <h3 style="margin: 0; color: #333; font-weight: 700;">
                                        <i class="fa fa-list" style="color: #667eea; margin-right: 10px;"></i>Available Restaurants
                                    </h3>
                                </div>
                                <div style="color: #666; font-size: 16px;">
                                    <?php 
                                    $count_query = mysqli_query($db,"select COUNT(*) as total from restaurant");
                                    $count_row = mysqli_fetch_array($count_query);
                                    echo '<span style="background: url(\'images/footer_pattern.png\'); background-size: cover; color: white; padding: 8px 20px; border-radius: 20px; font-weight: 600;">'.$count_row['total'].' Restaurants</span>';
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Restaurant Grid -->
                    <div class="row">
								<?php 
                                $ress= mysqli_query($db,"select * from restaurant");
                                $delay = 0;
									      while($rows=mysqli_fetch_array($ress))
										  {
                                            $delay += 0.1;
													
						
													 echo' <div class="col-12 col-md-6 col-lg-6 mb-4" style="animation: fadeInUp 0.6s ease both; animation-delay: '.$delay.'s;">
                                                        <div class="restaurant-card" style="background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.08); transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); border: 2px solid transparent; height: 100%; display: flex; flex-direction: column;">
                                                            
                                                            <!-- Restaurant Image/Logo Section -->
                                                            <div style="position: relative; padding: 20px; background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%); border-bottom: 1px solid #e0e0e0;">
                                                                <div style="display: flex; align-items: center; gap: 15px;">
                                                                    <div class="entry-logo" style="width: 90px; height: 90px; flex-shrink: 0; border-radius: 12px; overflow: hidden; border: 3px solid white; box-shadow: 0 6px 15px rgba(102, 126, 234, 0.2); transition: all 0.3s ease;">
                                                                        <a href="dishes.php?res_id='.$rows['rs_id'].'" > 
                                                                            <img src="admin/Res_img/'.$rows['image'].'" alt="Restaurant logo" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease;">
                                                                        </a>
                                                                    </div>
                                                                    
                                                                    <div style="flex: 1;">
                                                                        <h3 style="margin: 0 0 8px 0; font-weight: 700; color: #333; font-size: 20px; line-height: 1.3;">
                                                                            <a href="dishes.php?res_id='.$rows['rs_id'].'" style="color: #333; text-decoration: none; transition: color 0.3s;">'.$rows['title'].'</a>
                                                                        </h3>
                                                                        
                                                                        <div style="display: flex; align-items: flex-start; color: #666; font-size: 13px; margin-bottom: 10px;">
                                                                            <i class="fa fa-map-marker" style="color: #667eea; margin-right: 6px; margin-top: 2px; font-size: 14px;"></i>
                                                                            <span style="line-height: 1.4;">'.$rows['address'].'</span>
                                                                        </div>
                                                                        
                                                                        <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                                                                            <span style="display: inline-flex; align-items: center; background: #e8f5e9; color: #2e7d32; padding: 6px 12px; border-radius: 20px; font-size: 13px; font-weight: 600;">
                                                                                <i class="fa fa-clock-o" style="margin-right: 5px;"></i> '.$rows['o_hr'].' - '.$rows['c_hr'].'
                                                                            </span>
                                                                            <span style="display: inline-flex; align-items: center; background: #fff3e0; color: #e65100; padding: 6px 12px; border-radius: 20px; font-size: 13px; font-weight: 600;">
                                                                                <i class="fa fa-star" style="margin-right: 5px;"></i> 4.5
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            <!-- Action Section -->
                                                            <div style="padding: 18px 20px; background: white; margin-top: auto;">
                                                                <div style="display: flex; gap: 10px; align-items: center;">
                                                                    <a href="dishes.php?res_id='.$rows['rs_id'].'" class="btn" style="flex: 1; background: url(\'images/footer_pattern.png\'); background-size: cover; color: white; border: none; padding: 12px 20px; border-radius: 10px; font-weight: 600; font-size: 14px; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3); text-align: center; text-decoration: none; display: inline-flex; align-items: center; justify-content: center;">
                                                                        <i class="fa fa-cutlery" style="margin-right: 8px;"></i>View Menu
                                                                    </a>
                                                                    <a href="dishes.php?res_id='.$rows['rs_id'].'" style="width: 45px; height: 45px; display: inline-flex; align-items: center; justify-content: center; background: #f8f9ff; border: 2px solid #667eea; border-radius: 10px; color: #667eea; font-size: 16px; transition: all 0.3s ease; text-decoration: none;">
                                                                        <i class="fa fa-arrow-right"></i>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>';
										  }
						
						
						?>
                    </div>
                </div>
            </section>
       
        <footer class="footer">
            <div class="container">
                
              
                <div class="bottom-footer">
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
                                    <h5>Phone: +977 984084827</a></h5> </div>
                                <div class="col-xs-12 col-sm-5 additional-info color-gray">
                                    <h5>Addition informations</h5>
                                   <p>Join thousands of other restaurants who benefit from having partnered with us.</p>
                                </div>
                    </div>
                </div>
       
            </div>
        </footer>
        
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
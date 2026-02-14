<!DOCTYPE html>
<html lang="en">
<?php
include("connection/connect.php");
error_reporting(0);
session_start();

if(empty($_SESSION['user_id']))  
{
	header('location:login.php');
}
else
{
?>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="#">
    <title>My Orders</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animsition.min.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/dark-theme.css" rel="stylesheet">
<style type="text/css" rel="stylesheet">


.indent-small {
  margin-left: 5px;
}
.form-group.internal {
  margin-bottom: 0;
}
.dialog-panel {
  margin: 10px;
}
.datepicker-dropdown {
  z-index: 200 !important;
}
.panel-body {
  background: #e5e5e5;
  /* Old browsers */
  background: -moz-radial-gradient(center, ellipse cover, #e5e5e5 0%, #ffffff 100%);
  /* FF3.6+ */
  background: -webkit-gradient(radial, center center, 0px, center center, 100%, color-stop(0%, #e5e5e5), color-stop(100%, #ffffff));
  /* Chrome,Safari4+ */
  background: -webkit-radial-gradient(center, ellipse cover, #e5e5e5 0%, #ffffff 100%);
  /* Chrome10+,Safari5.1+ */
  background: -o-radial-gradient(center, ellipse cover, #e5e5e5 0%, #ffffff 100%);
  /* Opera 12+ */
  background: -ms-radial-gradient(center, ellipse cover, #e5e5e5 0%, #ffffff 100%);
  /* IE10+ */
  background: radial-gradient(ellipse at center, #e5e5e5 0%, #ffffff 100%);
  /* W3C */
  filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#e5e5e5', endColorstr='#ffffff', GradientType=1);
  font: 600 15px "Open Sans", Arial, sans-serif;
}
label.control-label {
  font-weight: 600;
  color: #777;
}

/* 
table { 
	width: 750px; 
	border-collapse: collapse; 
	margin: auto;
	
	}

/* Zebra striping */
/* tr:nth-of-type(odd) { 
	background: #eee; 
	}

th { 
	background: #404040; 
	color: white; 
	font-weight: bold; 
	
	}

td, th { 
	padding: 10px; 
	border: 1px solid #ccc; 
	text-align: left; 
	font-size: 14px;
	
	} */ */


@media 
only screen and (max-width: 760px),
(min-device-width: 768px) and (max-device-width: 1024px)  {

	/* table { 
	  	width: 100%; 
	}

	
	table, thead, tbody, th, td, tr { 
		display: block; 
	} */
	
	
	/* thead tr { 
		position: absolute;
		top: -9999px;
		left: -9999px;
	}
	
	tr { border: 1px solid #ccc; } */
	
	/* td { 
		
		border: none;
		border-bottom: 1px solid #eee; 
		position: relative;
		padding-left: 50%; 
	}

	td:before { 
		
		position: absolute;
	
		top: 6px;
		left: 6px;
		width: 45%; 
		padding-right: 10px; 
		white-space: nowrap;
		
		content: attr(data-column);

		color: #000;
		font-weight: bold;
	} */

}







	</style>

	</head>

<body>
    
      
        <header id="header" class="header-scroll top-header headrom">
  
            <nav class="navbar navbar-dark">
                <div class="container">
                    <button class="navbar-toggler hidden-lg-up" type="button" data-toggle="collapse" data-target="#mainNavbarCollapse">&#9776;</button>
                    <a class="navbar-brand" href="index.php"> <img class="img-rounded" src="images/quick bites.png" alt="" width="200" height="45"> </a>
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
       
           
    
            <div class="inner-page-hero bg-image" data-image-src="images/footer_pattern.png" style="position: relative; padding: 80px 0; overflow: hidden; background: url('images/footer_pattern.png'); background-size: cover; background-position: center;">
                <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(30, 42, 56, 0.95);"></div>
                <div class="container" style="position: relative; z-index: 1;"> </div>
        
            </div>
            <div class="result-show"> 
                <div class="container">
                    <div class="row">
                       
                       
                    </div>
                </div>
            </div>
    
            <section class="restaurants-page">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12">
                          </div>
                        <div class="col-xs-12">
                            <div style="background: white; border-radius: 12px; padding: 30px; box-shadow: 0 8px 25px rgba(0,0,0,0.1); animation: fadeInUp 0.6s ease;">
                                <div style="margin-bottom: 30px;">
                                    <h2 style="color: #333; font-weight: 700; display: flex; align-items: center;"><i class="fa fa-shopping-bag" style="margin-right: 15px; color: #667eea;"></i>My Orders</h2>
                                    <p style="color: #666; margin: 10px 0 0 0;">Track and manage your food orders</p>
                                </div>
                                <div class="row">
								<div class="table-responsive">
							<table class="table table-hover" style="margin-bottom: 0;">
						  <thead style="background: url('images/footer_pattern.png'); background-size: cover; color:white;">
							<tr>
							
							  <th style="padding: 15px; border: none; font-weight: 600;">Item</th>
							  <th style="padding: 15px; border: none; font-weight: 600;">Quantity</th>
							  <th style="padding: 15px; border: none; font-weight: 600;">Price</th>
							   <th style="padding: 15px; border: none; font-weight: 600;">Status</th>
							     <th style="padding: 15px; border: none; font-weight: 600;">Date</th>
								   <th style="padding: 15px; border: none; font-weight: 600;">Action</th>
							  
							</tr>
						  </thead>
						  <tbody>
						  
						  
							<?php 
				
						$query_res= mysqli_query($db,"select * from users_orders where u_id='".$_SESSION['user_id']."'");
												if(!mysqli_num_rows($query_res) > 0 )
														{
															echo '<tr><td colspan="6" style="padding: 60px; text-align: center;"><div style="animation: fadeIn 1s ease;"><i class="fa fa-shopping-cart" style="font-size: 80px; color: #e0e0e0; margin-bottom: 20px;"></i><h4 style="color: #999; font-weight: 600;">No Orders Yet</h4><p style="color: #bbb;">Start ordering delicious food from your favorite restaurants!</p><a href="restaurants.php" class="btn" style="background: url(\'images/footer_pattern.png\'); background-size: cover; color: white; border: none; padding: 12px 30px; border-radius: 25px; font-weight: 600; margin-top: 20px; display: inline-block; text-decoration: none;">Browse Restaurants</a></div></td></tr>';
														}
													else
														{			      
										  
										  while($row=mysqli_fetch_array($query_res))
										  {
						
							?>
												<tr style="transition: all 0.3s ease; border-bottom: 1px solid #e0e0e0;" onmouseover="this.style.backgroundColor='#f8f9ff'" onmouseout="this.style.backgroundColor='white'">	
														 <td data-column="Item" style="padding: 20px; font-weight: 600; color: #333;"> <?php echo $row['title']; ?></td>
														  <td data-column="Quantity" style="padding: 20px; color: #666;"> <span style="background: #f0f0f0; padding: 5px 15px; border-radius: 20px; font-weight: 600;"><?php echo $row['quantity']; ?></span></td>
														  <td data-column="price" style="padding: 20px; font-weight: 700; color: #667eea; font-size: 16px;">Rs. <?php echo $row['price']; ?></td>
														   <td data-column="status" style="padding: 20px;"> 
														   <?php 
																			$status=$row['status'];
																			if($status=="" or $status=="NULL")
																			{
																			?>
																			<button type="button" class="btn btn-info" style="border-radius: 20px; padding: 8px 20px; font-weight: 600; border: none; box-shadow: 0 4px 10px rgba(23, 162, 184, 0.3);"><span class="fa fa-bars" aria-hidden="true"></span> Dispatch</button>
																		   <?php 
																			  }
																			   if($status=="in process")
																			 { ?>
																				<button type="button" class="btn btn-warning" style="border-radius: 20px; padding: 8px 20px; font-weight: 600; border: none; box-shadow: 0 4px 10px rgba(255, 193, 7, 0.3);"><span class="fa fa-cog fa-spin" aria-hidden="true"></span> On The Way!</button>
																			<?php
																				}
																			if($status=="closed")
																				{
																			?>
																			 <button type="button" class="btn btn-success" style="border-radius: 20px; padding: 8px 20px; font-weight: 600; border: none; box-shadow: 0 4px 10px rgba(40, 167, 69, 0.3);"><span class="fa fa-check-circle" aria-hidden="true"></span> Delivered</button> 
																			<?php 
																			} 
																			?>
																			<?php
																			if($status=="rejected")
																				{
																			?>
																			 <button type="button" class="btn btn-danger" style="border-radius: 20px; padding: 8px 20px; font-weight: 600; border: none; box-shadow: 0 4px 10px rgba(220, 53, 69, 0.3);"> <i class="fa fa-close"></i> Cancelled</button>
																			<?php 
																			} 
																			?>
														   
														   
														   
														   
														   
														   
														   </td>
														  <td data-column="Date" style="padding: 20px; color: #666;"> <i class="fa fa-calendar" style="margin-right: 5px; color: #667eea;"></i><?php echo $row['date']; ?></td>
														   <td data-column="Action" style="padding: 20px;"> 
														   <a href="order_invoice.php?order_id=<?php echo $row['o_id'];?>" class="btn btn-primary" style="border-radius: 8px; padding: 8px 15px; border: none; transition: all 0.3s ease; box-shadow: 0 4px 10px rgba(102, 126, 234, 0.3); margin-right: 5px;" onmouseover="this.style.transform='scale(1.05)'; this.style.boxShadow='0 6px 15px rgba(102, 126, 234, 0.5)'" onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 4px 10px rgba(102, 126, 234, 0.3)'" title="View Invoice"><i class="fa fa-file-text-o" style="font-size:16px"></i></a>
														   <a href="delete_orders.php?order_del=<?php echo $row['o_id'];?>" onclick="return confirm('Are you sure you want to cancel your order?');" class="btn btn-danger" style="border-radius: 8px; padding: 8px 15px; border: none; transition: all 0.3s ease; box-shadow: 0 4px 10px rgba(220, 53, 69, 0.3);" onmouseover="this.style.transform='scale(1.05)'; this.style.boxShadow='0 6px 15px rgba(220, 53, 69, 0.5)'" onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 4px 10px rgba(220, 53, 69, 0.3)'" title="Cancel Order"><i class="fa fa-trash-o" style="font-size:16px"></i></a> 
															</td>
														 
												</tr>
												
											
														<?php }} ?>					
							
							
										
						
						  </tbody>
					</table>
						</div>
					
                                    
                                </div>
                           
                            </div>
                         
                            
                                
                            </div>
                          
                          
                           
                        </div>
                    </div>
                </div>
            </section>


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
                                    <p>1086 Stockert Hollow Road, Seattle</p>
                                    <h5>Phone: 75696969855</a></h5> </div>
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
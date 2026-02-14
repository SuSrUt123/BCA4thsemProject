<?php
session_start();
error_reporting(0);
include("connection/connect.php");
require_once 'includes/security.php';

$message = '';

if (isset($_POST['submit'])) {
    $username   = sanitize_input($_POST['username'] ?? '');
    $firstname  = sanitize_input($_POST['firstname'] ?? '');
    $lastname   = sanitize_input($_POST['lastname'] ?? '');
    $email      = sanitize_input($_POST['email'] ?? '');
    $phone      = sanitize_input($_POST['phone'] ?? '');
    $password   = $_POST['password'] ?? '';
    $cpassword  = $_POST['cpassword'] ?? '';
    $address    = sanitize_input($_POST['address'] ?? '');

    if (
        empty($username) ||
        empty($firstname) ||
        empty($lastname) ||
        empty($email) ||
        empty($phone) ||
        empty($password) ||
        empty($cpassword) ||
        empty($address)
    ) {
        $message = "All fields must be required!";
    } else {
        $stmt = $db->prepare("SELECT username FROM users WHERE username = ?");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $check_username = $stmt->get_result();

        $stmt2 = $db->prepare("SELECT email FROM users WHERE email = ?");
        $stmt2->bind_param('s', $email);
        $stmt2->execute();
        $check_email = $stmt2->get_result();

        if ($password !== $cpassword) {
            echo "<script>alert('Password not match');</script>";
        } elseif (strlen($password) < 6) {
            echo "<script>alert('Password must be at least 6 characters');</script>";
        } elseif (strlen($phone) < 10) {
            echo "<script>alert('Invalid phone number!');</script>";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<script>alert('Invalid email address please type a valid email!');</script>";
        } elseif ($check_username && $check_username->num_rows > 0) {
            echo "<script>alert('Username already exists!');</script>";
        } elseif ($check_email && $check_email->num_rows > 0) {
            echo "<script>alert('Email already exists!');</script>";
        } else {
            $stmt = $db->prepare("INSERT INTO users(username, f_name, l_name, email, phone, password, address) VALUES(?, ?, ?, ?, ?, ?, ?)");
            $hashed_password = hash_password($password);
            $stmt->bind_param('sssssss', $username, $firstname, $lastname, $email, $phone, $hashed_password, $address);
            $stmt->execute();
            header("Location: login.php");
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="#">
    <title>Registration</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animsition.min.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/dark-theme.css" rel="stylesheet"> </head>
<body>
<div style=" background-image: url('images/footer_pattern.png');">
         <header id="header" class="header-scroll top-header headrom">
            <nav class="navbar navbar-dark">
               <div class="container">
                  <button class="navbar-toggler hidden-lg-up" type="button" data-toggle="collapse" data-target="#mainNavbarCollapse">&#9776;</button>
                  <a class="navbar-brand" href="index.php"> <img class="img-rounded" src="images/quick%20bites.png" alt="Quick Bites" width="200" height="48"> </a>
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
         <div class="page-wrapper" style="min-height: 100vh; background: #f8f9fa; padding: 60px 0;">
            
            <section class="contact-page inner-page">
               <div class="container">
                  <div class="row justify-content-center">
                     <div class="col-md-11 col-lg-10 col-xl-9">
                        <div class="widget" style="box-shadow: 0 10px 50px rgba(0,0,0,0.1); border-radius: 20px; overflow: hidden; background: white; animation: fadeInUp 0.6s ease;">
                           <div style="background: url('images/footer_pattern.png'); background-size: cover; padding: 50px 40px; text-align: center; position: relative; overflow: hidden;">
                              <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23ffffff&quot; fill-opacity=&quot;0.05&quot;%3E%3Cpath d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E'); opacity: 0.3;"></div>
                              <div style="position: relative; z-index: 1;">
                                 <div style="width: 80px; height: 80px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; backdrop-filter: blur(10px);">
                                    <i class="fa fa-user-plus" style="font-size: 36px; color: white;"></i>
                                 </div>
                                 <h2 style="color: white; margin: 0; font-size: 36px; font-weight: 700; text-shadow: 0 2px 10px rgba(0,0,0,0.2);">Create Your Account</h2>
                                 <p style="color: rgba(255,255,255,0.95); margin: 15px 0 0 0; font-size: 18px;">Join thousands of food lovers today!</p>
                              </div>
                           </div>
                           <div class="widget-body" style="padding: 50px 40px;">
                            
							  <form action="" method="post">
                                 <div class="row">
								  <div class="form-group col-sm-12">
                                       <label style="font-weight: 700; color: #333; font-size: 14px; margin-bottom: 10px; display: block;">
                                          <i class="fa fa-user" style="margin-right: 8px; color: #667eea;"></i>Username
                                       </label>
                                       <input class="form-control" type="text" name="username" placeholder="Choose a unique username" style="border-radius: 12px; border: 2px solid #e0e0e0; padding: 14px 18px; transition: all 0.3s; font-size: 15px; background: #f8f9fa;" onfocus="this.style.borderColor='#667eea'; this.style.background='white'; this.style.boxShadow='0 0 0 4px rgba(102, 126, 234, 0.1)'" onblur="this.style.borderColor='#e0e0e0'; this.style.background='#f8f9fa'; this.style.boxShadow='none'" required> 
                                    </div>
                                    <div class="form-group col-sm-6">
                                       <label style="font-weight: 700; color: #333; font-size: 14px; margin-bottom: 10px; display: block;">
                                          <i class="fa fa-id-card" style="margin-right: 8px; color: #667eea;"></i>First Name
                                       </label>
                                       <input class="form-control" type="text" name="firstname" placeholder="Your first name" style="border-radius: 12px; border: 2px solid #e0e0e0; padding: 14px 18px; transition: all 0.3s; font-size: 15px; background: #f8f9fa;" onfocus="this.style.borderColor='#667eea'; this.style.background='white'; this.style.boxShadow='0 0 0 4px rgba(102, 126, 234, 0.1)'" onblur="this.style.borderColor='#e0e0e0'; this.style.background='#f8f9fa'; this.style.boxShadow='none'" required> 
                                    </div>
                                    <div class="form-group col-sm-6">
                                       <label style="font-weight: 700; color: #333; font-size: 14px; margin-bottom: 10px; display: block;">
                                          <i class="fa fa-id-card" style="margin-right: 8px; color: #667eea;"></i>Last Name
                                       </label>
                                       <input class="form-control" type="text" name="lastname" placeholder="Your last name" style="border-radius: 12px; border: 2px solid #e0e0e0; padding: 14px 18px; transition: all 0.3s; font-size: 15px; background: #f8f9fa;" onfocus="this.style.borderColor='#667eea'; this.style.background='white'; this.style.boxShadow='0 0 0 4px rgba(102, 126, 234, 0.1)'" onblur="this.style.borderColor='#e0e0e0'; this.style.background='#f8f9fa'; this.style.boxShadow='none'" required> 
                                    </div>
                                    <div class="form-group col-sm-6">
                                       <label style="font-weight: 700; color: #333; font-size: 14px; margin-bottom: 10px; display: block;">
                                          <i class="fa fa-envelope" style="margin-right: 8px; color: #667eea;"></i>Email Address
                                       </label>
                                       <input type="email" class="form-control" name="email" placeholder="your.email@example.com" style="border-radius: 12px; border: 2px solid #e0e0e0; padding: 14px 18px; transition: all 0.3s; font-size: 15px; background: #f8f9fa;" onfocus="this.style.borderColor='#667eea'; this.style.background='white'; this.style.boxShadow='0 0 0 4px rgba(102, 126, 234, 0.1)'" onblur="this.style.borderColor='#e0e0e0'; this.style.background='#f8f9fa'; this.style.boxShadow='none'" required> 
                                    </div>
                                    <div class="form-group col-sm-6">
                                       <label style="font-weight: 700; color: #333; font-size: 14px; margin-bottom: 10px; display: block;">
                                          <i class="fa fa-phone" style="margin-right: 8px; color: #667eea;"></i>Phone Number
                                       </label>
                                       <input class="form-control" type="tel" name="phone" placeholder="+977 9XXXXXXXXX" style="border-radius: 12px; border: 2px solid #e0e0e0; padding: 14px 18px; transition: all 0.3s; font-size: 15px; background: #f8f9fa;" onfocus="this.style.borderColor='#667eea'; this.style.background='white'; this.style.boxShadow='0 0 0 4px rgba(102, 126, 234, 0.1)'" onblur="this.style.borderColor='#e0e0e0'; this.style.background='#f8f9fa'; this.style.boxShadow='none'" required> 
                                    </div>
                                    <div class="form-group col-sm-6">
                                       <label style="font-weight: 700; color: #333; font-size: 14px; margin-bottom: 10px; display: block;">
                                          <i class="fa fa-lock" style="margin-right: 8px; color: #667eea;"></i>Password
                                       </label>
                                       <input type="password" class="form-control" name="password" placeholder="Min. 6 characters" style="border-radius: 12px; border: 2px solid #e0e0e0; padding: 14px 18px; transition: all 0.3s; font-size: 15px; background: #f8f9fa;" onfocus="this.style.borderColor='#667eea'; this.style.background='white'; this.style.boxShadow='0 0 0 4px rgba(102, 126, 234, 0.1)'" onblur="this.style.borderColor='#e0e0e0'; this.style.background='#f8f9fa'; this.style.boxShadow='none'" required> 
                                    </div>
                                    <div class="form-group col-sm-6">
                                       <label style="font-weight: 700; color: #333; font-size: 14px; margin-bottom: 10px; display: block;">
                                          <i class="fa fa-lock" style="margin-right: 8px; color: #667eea;"></i>Confirm Password
                                       </label>
                                       <input type="password" class="form-control" name="cpassword" placeholder="Re-enter password" style="border-radius: 12px; border: 2px solid #e0e0e0; padding: 14px 18px; transition: all 0.3s; font-size: 15px; background: #f8f9fa;" onfocus="this.style.borderColor='#667eea'; this.style.background='white'; this.style.boxShadow='0 0 0 4px rgba(102, 126, 234, 0.1)'" onblur="this.style.borderColor='#e0e0e0'; this.style.background='#f8f9fa'; this.style.boxShadow='none'" required> 
                                    </div>
									 <div class="form-group col-sm-12">
                                       <label style="font-weight: 700; color: #333; font-size: 14px; margin-bottom: 10px; display: block;">
                                          <i class="fa fa-map-marker" style="margin-right: 8px; color: #667eea;"></i>Delivery Address
                                       </label>
                                       <textarea class="form-control" name="address" rows="3" placeholder="Enter your complete delivery address" style="border-radius: 12px; border: 2px solid #e0e0e0; padding: 14px 18px; transition: all 0.3s; font-size: 15px; background: #f8f9fa; resize: vertical;" onfocus="this.style.borderColor='#667eea'; this.style.background='white'; this.style.boxShadow='0 0 0 4px rgba(102, 126, 234, 0.1)'" onblur="this.style.borderColor='#e0e0e0'; this.style.background='#f8f9fa'; this.style.boxShadow='none'" required></textarea>
                                    </div>
                                   
                                 </div>
                                
                                 <div class="row" style="margin-top: 30px;">
                                    <div class="col-sm-12 text-center">
                                       <button type="submit" name="submit" style="background: url('images/footer_pattern.png'); background-size: cover; border: none; padding: 16px 60px; font-size: 17px; font-weight: 700; border-radius: 12px; transition: all 0.3s ease; width: 100%; max-width: 400px; color: white; cursor: pointer; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.4);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(0, 0, 0, 0.6)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(0, 0, 0, 0.4)'">
                                          <i class="fa fa-user-plus" style="margin-right: 10px;"></i>Create My Account
                                       </button>
                                       <p style="margin-top: 25px; color: #666; font-size: 15px;">
                                          Already have an account? 
                                          <a href="login.php" style="color: #667eea; font-weight: 700; text-decoration: none; transition: all 0.3s;" onmouseover="this.style.color='#764ba2'" onmouseout="this.style.color='#667eea'">
                                             Login here <i class="fa fa-arrow-right" style="margin-left: 5px;"></i>
                                          </a>
                                       </p>
                                    </div>
                                 </div>
                              </form>
                  
						   </div>
           
                        </div>
                     
                     </div>
                    
                  </div>
               </div>
            </section>
            
      
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
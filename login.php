<?php
session_start();
error_reporting(0);
include("connection/connect.php");
require_once 'includes/security.php';

$message = '';

// Handle messages from redirects (logout, session expired, etc.)
if (isset($_GET['error']) && $_GET['error'] === 'session_expired') {
    $message = 'Your session has expired. Please log in again.';
} elseif (isset($_GET['logout'])) {
    $message = 'You have been logged out successfully.';
}

if (isset($_POST['submit'])) {
    $username = sanitize_input($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $message = "Please enter both username and password!";
    } else {
        $stmt = $db->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $row = $result->fetch_assoc();
            $password_valid = false;

            if (password_verify($password, $row['password'])) {
                $password_valid = true;

                if (password_needs_rehash($row['password'], PASSWORD_BCRYPT, ['cost' => 12])) {
                    $new_hash = hash_password($password);
                    $update = $db->prepare("UPDATE users SET password = ? WHERE u_id = ?");
                    $update->bind_param('si', $new_hash, $row['u_id']);
                    $update->execute();
                    $update->close();
                }
            } elseif (md5($password) === $row['password']) {
                $password_valid = true;
                $new_hash = hash_password($password);
                $update = $db->prepare("UPDATE users SET password = ? WHERE u_id = ?");
                $update->bind_param('si', $new_hash, $row['u_id']);
                $update->execute();
                $update->close();
            }

            if ($password_valid) {
                session_regenerate_id(true);
                $_SESSION["user_id"] = $row['u_id'];
                $_SESSION["role"]    = $row['role'] ?? 'user';

                // Redirect based on role
                if (!empty($row['role']) && $row['role'] === 'admin') {
                    header("Location: admin/dashboard.php");
                } else {
                    header("Location: index.php");
                }
                exit();
            } else {
                $message = "Invalid Username or Password!";
            }
        } else {
            $message = "Invalid Username or Password!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en" >

<head>
  <meta charset="UTF-8">
  <title>Login</title>
  
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">

  <link rel='stylesheet prefetch' href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700,900|RobotoDraft:400,100,300,500,700,900'>
<link rel='stylesheet prefetch' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css'>

      <link rel="stylesheet" href="css/login.css">

	  <style type="text/css">
	  #buttn{
		  color:#fff;
		  background-color: #5c4ac7;
	  }
	  </style>


    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animsition.min.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/dark-theme.css" rel="stylesheet">
  
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

<div style="min-height: 100vh; display: flex; background: #f8f9fa;">
    <!-- Left Side - Image/Branding -->
    <div style="flex: 1; background: url('images/footer_pattern.png'); background-size: cover; background-position: center; display: flex; align-items: center; justify-content: center; padding: 60px; position: relative; overflow: hidden;" class="login-left-side">
        <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23ffffff&quot; fill-opacity=&quot;0.05&quot;%3E%3Cpath d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E'); opacity: 0.3;"></div>
        <div style="position: relative; z-index: 1; text-align: center; animation: fadeInLeft 0.8s ease;">
            <div style="margin-bottom: 30px;">
                <i class="fa fa-cutlery" style="font-size: 80px; color: white; margin-bottom: 20px; animation: bounce 2s infinite;"></i>
            </div>
            <h1 style="color: white; font-size: 48px; font-weight: 700; margin-bottom: 20px; text-shadow: 0 4px 10px rgba(0,0,0,0.2);">Quick Bites</h1>
            <p style="color: rgba(255,255,255,0.95); font-size: 20px; line-height: 1.6; max-width: 400px; margin: 0 auto;">Your favorite food delivered fast and fresh to your doorstep</p>
            <div style="margin-top: 40px; display: flex; gap: 30px; justify-content: center; flex-wrap: wrap;">
                <div style="text-align: center;">
                    <div style="width: 60px; height: 60px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; backdrop-filter: blur(10px);">
                        <i class="fa fa-clock-o" style="font-size: 24px; color: white;"></i>
                    </div>
                    <p style="color: white; font-size: 14px; margin: 0;">Fast Delivery</p>
                </div>
                <div style="text-align: center;">
                    <div style="width: 60px; height: 60px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; backdrop-filter: blur(10px);">
                        <i class="fa fa-star" style="font-size: 24px; color: white;"></i>
                    </div>
                    <p style="color: white; font-size: 14px; margin: 0;">Top Rated</p>
                </div>
                <div style="text-align: center;">
                    <div style="width: 60px; height: 60px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; backdrop-filter: blur(10px);">
                        <i class="fa fa-shield" style="font-size: 24px; color: white;"></i>
                    </div>
                    <p style="color: white; font-size: 14px; margin: 0;">Secure</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Side - Login Form -->
    <div style="flex: 1; display: flex; align-items: center; justify-content: center; padding: 60px 40px; background: white;" class="login-right-side">
        <div style="width: 100%; max-width: 450px; animation: fadeInRight 0.8s ease;">
            <div style="text-align: center; margin-bottom: 40px;">
                <h2 style="color: #333; font-size: 36px; font-weight: 700; margin-bottom: 10px;">Welcome Back!</h2>
                <p style="color: #666; font-size: 16px;">Login to continue your food journey</p>
            </div>

            <?php if(!empty($message)): ?>
            <div style="background: #fee; border-left: 4px solid #dc3545; padding: 15px; border-radius: 8px; margin-bottom: 25px; animation: shake 0.5s;">
                <i class="fa fa-exclamation-circle" style="color: #dc3545; margin-right: 8px;"></i>
                <span style="color: #dc3545; font-weight: 600;"><?php echo $message; ?></span>
            </div>
            <?php endif; ?>

            <?php if(!empty($success)): ?>
            <div style="background: #d4edda; border-left: 4px solid #28a745; padding: 15px; border-radius: 8px; margin-bottom: 25px;">
                <i class="fa fa-check-circle" style="color: #28a745; margin-right: 8px;"></i>
                <span style="color: #28a745; font-weight: 600;"><?php echo $success; ?></span>
            </div>
            <?php endif; ?>

            <form action="" method="post">
                <div style="margin-bottom: 25px;">
                    <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 600; font-size: 14px;">
                        <i class="fa fa-user" style="margin-right: 8px; color: #667eea;"></i>Username
                    </label>
                    <div style="position: relative;">
                        <input type="text" name="username" placeholder="Enter your username" required style="width: 100%; padding: 15px 20px 15px 50px; border: 2px solid #e0e0e0; border-radius: 12px; font-size: 15px; transition: all 0.3s ease; background: #f8f9fa;" onfocus="this.style.borderColor='#667eea'; this.style.background='white'; this.style.boxShadow='0 0 0 4px rgba(102, 126, 234, 0.1)'" onblur="this.style.borderColor='#e0e0e0'; this.style.background='#f8f9fa'; this.style.boxShadow='none'"/>
                        <i class="fa fa-user" style="position: absolute; left: 18px; top: 50%; transform: translateY(-50%); color: #999; font-size: 16px;"></i>
                    </div>
                </div>

                <div style="margin-bottom: 25px;">
                    <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 600; font-size: 14px;">
                        <i class="fa fa-lock" style="margin-right: 8px; color: #667eea;"></i>Password
                    </label>
                    <div style="position: relative;">
                        <input type="password" name="password" placeholder="Enter your password" required style="width: 100%; padding: 15px 20px 15px 50px; border: 2px solid #e0e0e0; border-radius: 12px; font-size: 15px; transition: all 0.3s ease; background: #f8f9fa;" onfocus="this.style.borderColor='#667eea'; this.style.background='white'; this.style.boxShadow='0 0 0 4px rgba(102, 126, 234, 0.1)'" onblur="this.style.borderColor='#e0e0e0'; this.style.background='#f8f9fa'; this.style.boxShadow='none'"/>
                        <i class="fa fa-lock" style="position: absolute; left: 18px; top: 50%; transform: translateY(-50%); color: #999; font-size: 16px;"></i>
                    </div>
                </div>

                <button type="submit" name="submit" style="width: 100%; padding: 16px; background: url('images/footer_pattern.png'); background-size: cover; color: white; border: none; border-radius: 12px; font-size: 16px; font-weight: 700; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.4); margin-top: 10px;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(0, 0, 0, 0.6)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(0, 0, 0, 0.4)'">
                    <i class="fa fa-sign-in" style="margin-right: 8px;"></i>Login to Account
                </button>
            </form>

            <div style="text-align: center; margin-top: 30px; padding-top: 30px; border-top: 1px solid #e0e0e0;">
                <p style="color: #666; font-size: 15px; margin: 0;">
                    Don't have an account? 
                    <a href="registration.php" style="color: #667eea; font-weight: 700; text-decoration: none; transition: all 0.3s;" onmouseover="this.style.color='#764ba2'" onmouseout="this.style.color='#667eea'">
                        Create Account <i class="fa fa-arrow-right" style="margin-left: 5px;"></i>
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes shake {
    0%, 100% { transform: translateX(0); }
    10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
    20%, 40%, 60%, 80% { transform: translateX(5px); }
}

@media (max-width: 991px) {
    .login-left-side {
        display: none !important;
    }
    .login-right-side {
        flex: 1;
        padding: 40px 20px !important;
    }
}
</style>

<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>


   
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
                                    <h5>Phone: +977 9844084827  </a></h5> </div>
                        <div class="col-xs-12 col-sm-5 additional-info color-gray">
                            <h5>Addition informations</h5>
                           <p>Join thousands of other restaurants who benefit from having partnered with us.</p>
                        </div>
                    </div>
                </div>
            
            </div>
        </footer>
       


</body>

</html>

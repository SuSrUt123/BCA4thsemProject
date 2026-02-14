<!DOCTYPE html>
<html lang="en">
<?php
include("connection/connect.php");
error_reporting(0);
session_start();

if(empty($_SESSION['user_id']))  
{
    header('location:login.php');
    exit();
}

// Get order ID from URL
if(!isset($_GET['order_id']) || empty($_GET['order_id'])) {
    header('location:your_orders.php');
    exit();
}

$order_id = mysqli_real_escape_string($db, $_GET['order_id']);

// Fetch order details
$order_query = mysqli_query($db, "SELECT uo.*, u.username, u.f_name, u.l_name, u.email, u.phone, u.address 
                                   FROM users_orders uo 
                                   LEFT JOIN users u ON uo.u_id = u.u_id 
                                   WHERE uo.o_id = '$order_id' AND uo.u_id = '".$_SESSION['user_id']."'");

if(mysqli_num_rows($order_query) == 0) {
    header('location:your_orders.php');
    exit();
}

$order = mysqli_fetch_array($order_query);
?>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Order Invoice #<?php echo $order['o_id']; ?></title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <style>
        body {
            background: #f5f5f5;
            font-family: 'Arial', sans-serif;
        }
        
        .invoice-container {
            max-width: 900px;
            margin: 30px auto;
            background: white;
            padding: 40px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        
        .invoice-header {
            border-bottom: 3px solid #667eea;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .company-logo {
            max-width: 200px;
            height: auto;
        }
        
        .invoice-title {
            font-size: 32px;
            font-weight: 700;
            color: #333;
            margin: 0;
        }
        
        .invoice-number {
            font-size: 16px;
            color: #666;
            margin-top: 5px;
        }
        
        .billing-section {
            background: #f8f9ff;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            border-left: 4px solid #667eea;
        }
        
        .section-title {
            font-size: 18px;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }
        
        .section-title i {
            margin-right: 10px;
        }
        
        .info-row {
            margin-bottom: 10px;
            color: #555;
        }
        
        .info-label {
            font-weight: 600;
            color: #333;
            display: inline-block;
            min-width: 140px;
        }
        
        .order-table {
            width: 100%;
            margin-bottom: 30px;
        }
        
        .order-table thead {
            background: #667eea;
            color: white;
        }
        
        .order-table th {
            padding: 15px;
            font-weight: 600;
            text-align: left;
        }
        
        .order-table td {
            padding: 15px;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .order-table tbody tr:hover {
            background: #f8f9ff;
        }
        
        .total-section {
            background: #f8f9ff;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            font-size: 16px;
        }
        
        .total-row.grand-total {
            border-top: 2px solid #667eea;
            margin-top: 10px;
            padding-top: 15px;
            font-size: 24px;
            font-weight: 700;
            color: #667eea;
        }
        
        .status-badge {
            display: inline-block;
            padding: 8px 20px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 14px;
        }
        
        .status-dispatch {
            background: #d1ecf1;
            color: #0c5460;
        }
        
        .status-process {
            background: #fff3cd;
            color: #856404;
        }
        
        .status-delivered {
            background: #d4edda;
            color: #155724;
        }
        
        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
        }
        
        .print-button {
            background: #667eea;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            margin-right: 10px;
            transition: all 0.3s ease;
        }
        
        .print-button:hover {
            background: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }
        
        .back-button {
            background: #6c757d;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }
        
        .back-button:hover {
            background: #5a6268;
            color: white;
            text-decoration: none;
            transform: translateY(-2px);
        }
        
        .footer-note {
            text-align: center;
            color: #999;
            font-size: 14px;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
        }
        
        @media print {
            body {
                background: white;
            }
            
            .invoice-container {
                box-shadow: none;
                margin: 0;
                padding: 20px;
            }
            
            .no-print {
                display: none !important;
            }
            
            .invoice-header {
                border-bottom: 2px solid #333;
            }
        }
    </style>
</head>

<body>
    <div class="invoice-container">
        <!-- Header -->
        <div class="invoice-header">
            <div class="row">
                <div class="col-md-6">
                    <img src="images/quick bites.png" alt="Quick Bites" class="company-logo">
                </div>
                <div class="col-md-6 text-right">
                    <h1 class="invoice-title">INVOICE</h1>
                    <p class="invoice-number">Order #<?php echo $order['o_id']; ?></p>
                    <p class="invoice-number">Date: <?php echo date('F d, Y', strtotime($order['date'])); ?></p>
                </div>
            </div>
        </div>
        
        <!-- Billing Information -->
        <div class="row">
            <div class="col-md-6">
                <div class="billing-section">
                    <h3 class="section-title"><i class="fa fa-building"></i>Company Details</h3>
                    <div class="info-row">
                        <span class="info-label">Business Name:</span> Quick Bites
                    </div>
                    <div class="info-row">
                        <span class="info-label">Registration No:</span> QB-2025-001
                    </div>
                    <div class="info-row">
                        <span class="info-label">Tax ID:</span> 123456789
                    </div>
                    <div class="info-row">
                        <span class="info-label">Email:</span> billing@quickbites.com
                    </div>
                    <div class="info-row">
                        <span class="info-label">Phone:</span> +977-1-4567890
                    </div>
                    <div class="info-row">
                        <span class="info-label">Address:</span> Kathmandu, Nepal
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="billing-section">
                    <h3 class="section-title"><i class="fa fa-user"></i>Customer Details</h3>
                    <div class="info-row">
                        <span class="info-label">Name:</span> <?php echo $order['f_name'].' '.$order['l_name']; ?>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Username:</span> <?php echo $order['username']; ?>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Email:</span> <?php echo $order['email']; ?>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Phone:</span> <?php echo $order['phone']; ?>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Delivery Address:</span> <?php echo $order['address']; ?>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Order Status:</span> 
                        <?php 
                        $status = $order['status'];
                        if($status == "" || $status == "NULL") {
                            echo '<span class="status-badge status-dispatch"><i class="fa fa-bars"></i> Dispatch</span>';
                        } elseif($status == "in process") {
                            echo '<span class="status-badge status-process"><i class="fa fa-cog fa-spin"></i> On The Way</span>';
                        } elseif($status == "closed") {
                            echo '<span class="status-badge status-delivered"><i class="fa fa-check-circle"></i> Delivered</span>';
                        } elseif($status == "rejected") {
                            echo '<span class="status-badge status-cancelled"><i class="fa fa-times-circle"></i> Cancelled</span>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Order Details -->
        <div class="section-title" style="margin-top: 30px;"><i class="fa fa-shopping-cart"></i>Order Details</div>
        <table class="order-table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th style="text-align: center;">Quantity</th>
                    <th style="text-align: right;">Unit Price</th>
                    <th style="text-align: right;">Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong><?php echo $order['title']; ?></strong></td>
                    <td style="text-align: center;"><?php echo $order['quantity']; ?></td>
                    <td style="text-align: right;">Rs. <?php echo number_format($order['price'] / $order['quantity'], 2); ?></td>
                    <td style="text-align: right;"><strong>Rs. <?php echo number_format($order['price'], 2); ?></strong></td>
                </tr>
            </tbody>
        </table>
        
        <!-- Total Section -->
        <div class="row">
            <div class="col-md-6 offset-md-6">
                <div class="total-section">
                    <div class="total-row">
                        <span>Subtotal:</span>
                        <span>Rs. <?php echo number_format($order['price'], 2); ?></span>
                    </div>
                    <div class="total-row">
                        <span>Delivery Charges:</span>
                        <span style="color: #28a745; font-weight: 600;">Free</span>
                    </div>
                    <div class="total-row grand-total">
                        <span>Grand Total:</span>
                        <span>Rs. <?php echo number_format($order['price'], 2); ?></span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Payment Information -->
        <div class="billing-section" style="margin-top: 30px;">
            <h3 class="section-title"><i class="fa fa-credit-card"></i>Payment Information</h3>
            <div class="info-row">
                <span class="info-label">Payment Method:</span> Cash on Delivery (COD)
            </div>
            <div class="info-row">
                <span class="info-label">Bank Name:</span> Nepal Bank Limited
            </div>
            <div class="info-row">
                <span class="info-label">Account Number:</span> 0123456789
            </div>
            <div class="info-row">
                <span class="info-label">Account Name:</span> Quick Bites Pvt. Ltd.
            </div>
        </div>
        
        <!-- Action Buttons -->
        <div class="text-center no-print" style="margin-top: 40px;">
            <button onclick="window.print()" class="print-button">
                <i class="fa fa-print"></i> Print Invoice
            </button>
            <a href="your_orders.php" class="back-button">
                <i class="fa fa-arrow-left"></i> Back to Orders
            </a>
        </div>
        
        <!-- Footer Note -->
        <div class="footer-note">
            <p><strong>Thank you for your order!</strong></p>
            <p>For any queries, please contact us at billing@quickbites.com or call +977-1-4567890</p>
            <p style="margin-top: 10px; font-size: 12px;">This is a computer-generated invoice and does not require a signature.</p>
        </div>
    </div>
    
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>

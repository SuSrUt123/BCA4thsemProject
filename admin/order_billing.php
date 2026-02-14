<!DOCTYPE html>
<html lang="en">
<?php
session_start();
include("../connection/connect.php");
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if admin is logged in - match dashboard.php authentication
if(empty($_SESSION["user_id"]) || empty($_SESSION["role"]) || $_SESSION["role"] !== 'admin') {
    // Clear session data
    $_SESSION = array();
    session_destroy();
    
    // Redirect to admin login
    header('Location: index.php');
    exit();
}

// Get order ID from URL
if(!isset($_GET['order_id']) || empty($_GET['order_id'])) {
    echo "Error: No order ID provided";
    exit();
}

$order_id = mysqli_real_escape_string($db, $_GET['order_id']);

// Fetch order details with user information
$order_query = mysqli_query($db, "SELECT uo.*, u.username, u.f_name, u.l_name, u.email, u.phone, u.address
                                   FROM users_orders uo 
                                   LEFT JOIN users u ON uo.u_id = u.u_id 
                                   WHERE uo.o_id = '$order_id'");

if(!$order_query) {
    echo "Database Error: " . mysqli_error($db);
    exit();
}

if(mysqli_num_rows($order_query) == 0) {
    echo "Error: Order not found (Order ID: $order_id)";
    exit();
}

$order = mysqli_fetch_array($order_query);
?>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Order Billing - #<?php echo $order['o_id']; ?></title>
    <link href="css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="../css/font-awesome.min.css" rel="stylesheet">
    <style>
        body {
            background: #f5f7fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .billing-panel {
            max-width: 1000px;
            margin: 30px auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .panel-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .panel-header h2 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        
        .panel-header .order-number {
            font-size: 14px;
            opacity: 0.9;
            margin-top: 5px;
        }
        
        .panel-body {
            padding: 30px;
        }
        
        .info-section {
            margin-bottom: 30px;
        }
        
        .section-title {
            font-size: 16px;
            font-weight: 600;
            color: #333;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #667eea;
            display: flex;
            align-items: center;
        }
        
        .section-title i {
            margin-right: 10px;
            color: #667eea;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }
        
        .info-item {
            display: flex;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 5px;
        }
        
        .info-label {
            font-weight: 600;
            color: #555;
            min-width: 120px;
        }
        
        .info-value {
            color: #333;
        }
        
        .order-details-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        
        .order-details-table thead {
            background: #667eea;
            color: white;
        }
        
        .order-details-table th {
            padding: 12px;
            text-align: left;
            font-weight: 600;
        }
        
        .order-details-table td {
            padding: 12px;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .order-details-table tbody tr:hover {
            background: #f8f9fa;
        }
        
        .total-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            font-size: 15px;
        }
        
        .total-row.grand-total {
            border-top: 2px solid #667eea;
            margin-top: 10px;
            padding-top: 15px;
            font-size: 20px;
            font-weight: 700;
            color: #667eea;
        }
        
        .status-badge {
            display: inline-block;
            padding: 6px 15px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 13px;
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
        
        .action-buttons {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
        }
        
        .btn-custom {
            padding: 12px 30px;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-print {
            background: #667eea;
            color: white;
        }
        
        .btn-print:hover {
            background: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
            color: white;
        }
        
        .btn-back {
            background: #6c757d;
            color: white;
        }
        
        .btn-back:hover {
            background: #5a6268;
            transform: translateY(-2px);
            color: white;
        }
        
        .company-logo {
            max-width: 150px;
            margin-bottom: 20px;
        }
        
        @media print {
            body {
                background: white;
            }
            
            .billing-panel {
                box-shadow: none;
                margin: 0;
            }
            
            .panel-header {
                background: #667eea !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .no-print {
                display: none !important;
            }
            
            .action-buttons {
                display: none !important;
            }
        }
        
        @media (max-width: 768px) {
            .info-grid {
                grid-template-columns: 1fr;
            }
            
            .panel-body {
                padding: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="billing-panel">
        <!-- Header -->
        <div class="panel-header">
            <div>
                <h2>Order Billing Invoice</h2>
                <div class="order-number">Order #<?php echo $order['o_id']; ?> | <?php echo date('F d, Y', strtotime($order['date'])); ?></div>
            </div>
            <div>
                <img src="images/quick bites 1.png" alt="Quick Bites" style="max-width: 150px;">
            </div>
        </div>
        
        <!-- Body -->
        <div class="panel-body">
            
            <!-- Company Information -->
            <div class="info-section">
                <div class="section-title">
                    <i class="fa fa-building"></i>
                    Company Information
                </div>
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Business Name:</span>
                        <span class="info-value">Quick Bites</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Registration No:</span>
                        <span class="info-value">QB-2025-001</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Tax ID:</span>
                        <span class="info-value">123456789</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Email:</span>
                        <span class="info-value">billing@quickbites.com</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Phone:</span>
                        <span class="info-value">+977 9844084827
                    </div>
                    <div class="info-item">
                        <span class="info-label">Address:</span>
                        <span class="info-value">Itahari, Nepal</span>
                    </div>
                </div>
            </div>
            
            <!-- Customer Information -->
            <div class="info-section">
                <div class="section-title">
                    <i class="fa fa-user"></i>
                    Customer Information
                </div>
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Name:</span>
                        <span class="info-value"><?php echo $order['f_name'].' '.$order['l_name']; ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Username:</span>
                        <span class="info-value"><?php echo $order['username']; ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Email:</span>
                        <span class="info-value"><?php echo $order['email']; ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Phone:</span>
                        <span class="info-value"><?php echo $order['phone']; ?></span>
                    </div>
                    <div class="info-item" style="grid-column: span 2;">
                        <span class="info-label">Delivery Address:</span>
                        <span class="info-value"><?php echo $order['address']; ?></span>
                    </div>
                </div>
            </div>
            
            <!-- Order Details -->
            <div class="info-section">
                <div class="section-title">
                    <i class="fa fa-shopping-cart"></i>
                    Order Details
                </div>
                
                <table class="order-details-table">
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
                <div class="total-section">
                    <div class="total-row">
                        <span>Subtotal:</span>
                        <span>Rs. <?php echo number_format($order['price'], 2); ?></span>
                    </div>
                    <div class="total-row">
                        <span>Delivery Charges:</span>
                        <span style="color: #28a745; font-weight: 600;">Free</span>
                    </div>
                    <div class="total-row">
                        <span>Tax (0%):</span>
                        <span>Rs. 0.00</span>
                    </div>
                    <div class="total-row grand-total">
                        <span>Grand Total:</span>
                        <span>Rs. <?php echo number_format($order['price'], 2); ?></span>
                    </div>
                </div>
            </div>
            
            <!-- Order Status & Payment -->
            <div class="info-section">
                <div class="section-title">
                    <i class="fa fa-info-circle"></i>
                    Order Status & Payment
                </div>
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Order Status:</span>
                        <span class="info-value">
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
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Payment Method:</span>
                        <span class="info-value">Cash on Delivery (COD)</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Order Date:</span>
                        <span class="info-value"><?php echo date('F d, Y h:i A', strtotime($order['date'])); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Order ID:</span>
                        <span class="info-value">#<?php echo $order['o_id']; ?></span>
                    </div>
                </div>
            </div>
            
            <!-- Payment Information -->
            <div class="info-section">
                <div class="section-title">
                    <i class="fa fa-credit-card"></i>
                    Payment Information
                </div>
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Bank Name:</span>
                        <span class="info-value">Nic Asia bank</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Account Number:</span>
                        <span class="info-value">0123456789</span>
                    </div>
                    <div class="info-item" style="grid-column: span 2;">
                        <span class="info-label">Account Name:</span>
                        <span class="info-value">Quick Bites Pvt. Ltd.</span>
                    </div>
                </div>
            </div>
            
            <!-- Footer Note -->
            <div style="text-align: center; color: #999; font-size: 13px; margin-top: 30px; padding-top: 20px; border-top: 1px solid #e0e0e0;">
                <p><strong>Thank you for your order!</strong></p>
                <p>For any queries, please contact us at billing@quickbites.com or call +977 9844084827</p>
                <p style="margin-top: 10px; font-size: 11px;"</p>
            </div>
            
            <!-- Action Buttons -->
            <div class="action-buttons no-print">
                <button onclick="window.print()" class="btn-custom btn-print">
                    <i class="fa fa-print"></i> Print Invoice
                </button>
                <a href="all_orders.php" class="btn-custom btn-back">
                    <i class="fa fa-arrow-left"></i> Back to Orders
                </a>
            </div>
        </div>
    </div>
    
    <script src="js/lib/jquery/jquery.min.js"></script>
</body>
</html>

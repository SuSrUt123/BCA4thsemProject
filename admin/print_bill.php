<!DOCTYPE html>
<html lang="en">
<?php
include("../connection/connect.php");
error_reporting(0);
session_start();

// Check if user is logged in
if(strlen($_SESSION['user_id'])==0) { 
    header('location:../login.php');
    exit();
}

// Get order ID from URL
$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

if($order_id == 0) {
    echo "<script>alert('Invalid order ID'); window.close();</script>";
    exit();
}

// Get order details
$sql = "SELECT u.*, uo.* FROM users u 
        INNER JOIN users_orders uo ON u.u_id = uo.u_id 
        WHERE uo.o_id = ? AND uo.status = 'closed'";

$stmt = mysqli_prepare($db, $sql);
mysqli_stmt_bind_param($stmt, "i", $order_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if(mysqli_num_rows($result) == 0) {
    echo "<script>alert('Order not found or not delivered yet'); window.close();</script>";
    exit();
}

$order = mysqli_fetch_array($result);
$total = $order['price'] * $order['quantity'];
?>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Order Bill - #<?php echo $order['o_id']; ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: white;
        }
        
        .bill-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border: 2px solid #333;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        
        .header h1 {
            margin: 0;
            font-size: 28px;
            color: #333;
        }
        
        .header p {
            margin: 5px 0;
            color: #666;
        }
        
        .bill-info {
            display: flex;
            justify-content: space-between;
            margin: 20px 0;
        }
        
        .customer-info, .order-info {
            width: 48%;
        }
        
        .customer-info h3, .order-info h3 {
            margin-bottom: 10px;
            color: #333;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
        }
        
        .info-row {
            margin: 8px 0;
            display: flex;
            justify-content: space-between;
        }
        
        .info-label {
            font-weight: bold;
            color: #333;
        }
        
        .order-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        .order-table th, .order-table td {
            border: 1px solid #333;
            padding: 12px;
            text-align: left;
        }
        
        .order-table th {
            background: #f0f0f0;
            font-weight: bold;
        }
        
        .total-section {
            text-align: right;
            margin-top: 20px;
            font-size: 18px;
            font-weight: bold;
        }
        
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ccc;
            color: #666;
            font-size: 12px;
        }
        
        .print-btn {
            background: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 10px;
        }
        
        @media print {
            .no-print {
                display: none;
            }
            
            .bill-container {
                border: none;
                padding: 0;
            }
        }
    </style>
</head>

<body>
    <div class="bill-container">
        <!-- Print Buttons -->
        <div class="no-print" style="text-align: center; margin-bottom: 20px;">
            <button class="print-btn" onclick="window.print()">Print Bill</button>
            <button class="print-btn" onclick="window.close()" style="background: #6c757d;">Close</button>
        </div>
        
        <!-- Header -->
        <div class="header">
            <h1>Quick Bites</h1>
            <p>Online Food Ordering System</p>
            <p>Order Bill</p>
        </div>
        
        <!-- Bill Information -->
        <div class="bill-info">
            <div class="customer-info">
                <h3>Customer Details</h3>
                <div class="info-row">
                    <span class="info-label">Name:</span>
                    <span><?php echo $order['f_name'] . ' ' . $order['l_name']; ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email:</span>
                    <span><?php echo $order['email']; ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Phone:</span>
                    <span><?php echo $order['phone']; ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Address:</span>
                    <span><?php echo $order['address']; ?></span>
                </div>
            </div>
            
            <div class="order-info">
                <h3>Order Details</h3>
                <div class="info-row">
                    <span class="info-label">Order ID:</span>
                    <span>#<?php echo $order['o_id']; ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Order Date:</span>
                    <span><?php echo date('M d, Y', strtotime($order['date'])); ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Status:</span>
                    <span style="color: green; font-weight: bold;">Delivered</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Payment:</span>
                    <span>Cash on Delivery</span>
                </div>
            </div>
        </div>
        
        <!-- Order Items Table -->
        <table class="order-table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo $order['title']; ?></td>
                    <td><?php echo $order['quantity']; ?></td>
                    <td>Rs. <?php echo number_format($order['price'], 2); ?></td>
                    <td>Rs. <?php echo number_format($total, 2); ?></td>
                </tr>
            </tbody>
        </table>
        
        <!-- Total Section -->
        <div class="total-section">
            <div style="margin: 10px 0;">
                Subtotal: Rs. <?php echo number_format($total, 2); ?>
            </div>
            <div style="margin: 10px 0;">
                Delivery Charge: FREE
            </div>
            <div style="margin: 10px 0; font-size: 20px; border-top: 2px solid #333; padding-top: 10px;">
                Total Amount: Rs. <?php echo number_format($total, 2); ?>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p><strong>Thank you for your order!</strong></p>
            <p>Bill generated on <?php echo date('M d, Y H:i:s'); ?></p>
            <p>Quick Bites - Online Food Ordering System</p>
        </div>
    </div>
    
    <script>
        // Auto print when page loads (optional)
        // window.onload = function() { window.print(); };
    </script>
</body>
</html>
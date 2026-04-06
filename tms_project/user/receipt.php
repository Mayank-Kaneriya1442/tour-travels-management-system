<?php 
include('../config/db.php'); 
session_start();

if(!isset($_SESSION['user_id'])) { header("location:login.php"); }
$uid = $_SESSION['user_id'];

if(!isset($_GET['bid'])) { header("location:my_bookings.php"); }
$bid = mysqli_real_escape_string($conn, $_GET['bid']);

// Fetch Booking, Package, User, and Payment Details
$query = "SELECT bookings.*, packages.p_name, packages.p_location, users.full_name, users.email, users.mobile, payments.transaction_id, payments.method 
          FROM bookings 
          JOIN packages ON bookings.p_id = packages.p_id 
          JOIN users ON bookings.user_id = users.id 
          LEFT JOIN payments ON bookings.b_id = payments.booking_id
          WHERE bookings.b_id='$bid' AND bookings.user_id='$uid'";
$res = mysqli_query($conn, $query);

if(mysqli_num_rows($res) == 0) {
    echo "<script>alert('Invalid Receipt Access'); window.location='my_bookings.php';</script>";
    exit();
}

$data = mysqli_fetch_assoc($res);

// Determine Total Price
$total_amount = ($data['total_price'] > 0) ? $data['total_price'] : ($data['p_price'] * $data['travelers']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Receipt - #BK-<?php echo $data['b_id']; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: #2c3e50;
            --accent: #e67e22;
            --text-dark: #2d3436;
            --text-light: #636e72;
            --bg-light: #f5f6fa;
            --white: #ffffff;
            --success: #00b894;
            --pending: #fdcb6e;
        }

        body { 
            background: #dfe6e9; 
            font-family: 'Poppins', sans-serif; 
            padding: 40px 20px; 
            color: var(--text-dark);
        }
        
        .receipt-container {
            max-width: 800px;
            margin: 0 auto;
            background: var(--white);
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.1);
            overflow: hidden;
            position: relative;
        }

        .receipt-header {
            background: var(--primary);
            color: var(--white);
            padding: 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .brand-section h1 { margin: 0; font-size: 1.8rem; font-weight: 700; letter-spacing: 1px; }
        .brand-section span { color: var(--accent); }
        .brand-section p { margin: 5px 0 0; opacity: 0.8; font-size: 0.9rem; }

        .invoice-details { text-align: right; }
        .invoice-details h2 { margin: 0; font-size: 2.5rem; opacity: 0.1; text-transform: uppercase; line-height: 1; }
        .invoice-number { font-size: 1.1rem; font-weight: 600; margin-top: -10px; display: block; }

        .receipt-body { padding: 40px; }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-bottom: 40px;
        }

        .info-group h3 { font-size: 0.9rem; text-transform: uppercase; color: var(--text-light); margin-bottom: 15px; letter-spacing: 1px; }
        .info-content { font-size: 1rem; line-height: 1.6; }
        .info-content strong { display: block; color: var(--text-dark); font-size: 1.1rem; margin-bottom: 5px; }

        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .status-paid { background: rgba(0, 184, 148, 0.15); color: var(--success); }
        .status-pending { background: rgba(253, 203, 110, 0.15); color: #d35400; }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .items-table th { text-align: left; padding: 15px; background: var(--bg-light); color: var(--text-light); font-weight: 600; font-size: 0.9rem; }
        .items-table td { padding: 20px 15px; border-bottom: 1px solid #eee; }
        .items-table tr:last-child td { border-bottom: none; }
        
        .amount-column { text-align: right; font-weight: 600; }

        .total-section {
            background: var(--bg-light);
            padding: 30px;
            border-radius: 12px;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 30px;
        }
        
        .total-label { font-size: 1.1rem; color: var(--text-light); }
        .total-value { font-size: 2rem; font-weight: 700; color: var(--primary); }

        .receipt-footer {
            text-align: center;
            padding: 30px;
            border-top: 1px solid #eee;
            color: var(--text-light);
            font-size: 0.9rem;
        }

        .actions-bar {
            padding: 20px 40px 40px;
            display: flex;
            gap: 15px;
            justify-content: center;
        }

        .print-btn {
            padding: 12px 30px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 50px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .print-btn:hover { background: #34495e; transform: translateY(-2px); }
        
        .back-btn {
            padding: 12px 30px;
            background: white;
            color: var(--text-light);
            border: 1px solid #ddd;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            transition: 0.3s;
        }
        .back-btn:hover { background: #f8f9fa; color: var(--text-dark); }

        @media print {
            body { background: white; padding: 0; }
            .receipt-container { box-shadow: none; border: 1px solid #ddd; }
            .actions-bar, .back-btn { display: none !important; }
            .receipt-header { -webkit-print-color-adjust: exact; }
        }
    </style>
</head>
<body>

<div class="receipt-container">
    <div class="receipt-header">
        <div class="brand-section">
            <h1>Travel<span>Ease</span></h1>
            <p>Explore the world with us</p>
        </div>
        <div class="invoice-details">
            <h2>INVOICE</h2>
            <span class="invoice-number">#BK-<?php echo $data['b_id']; ?></span>
            <div style="margin-top: 10px; font-size: 0.9rem; opacity: 0.9;">
                Date: <?php echo date('d M Y'); ?>
            </div>
        </div>
    </div>

    <div class="receipt-body">
        <div class="info-grid">
            <div class="info-group">
                <h3>Billed To</h3>
                <div class="info-content">
                    <strong><?php echo $data['full_name']; ?></strong>
                    <?php echo $data['email']; ?><br>
                    <?php echo $data['mobile']; ?>
                </div>
            </div>
            <div class="info-group" style="text-align: right;">
                <h3>Payment Status</h3>
                <span class="status-badge <?php echo ($data['payment_status'] == 'Paid' || $data['payment_status'] == 'Pending Verification') ? 'status-paid' : 'status-pending'; ?>">
                    <?php echo $data['payment_status']; ?>
                </span>
                <?php if($data['transaction_id']): ?>
                    <div style="margin-top: 10px; font-size: 0.9rem; color: #666;">
                        Txn ID: <?php echo $data['transaction_id']; ?><br>
                        Method: <?php echo $data['method']; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <table class="items-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Travel Date</th>
                    <th>Travelers</th>
                    <th class="amount-column">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <strong><?php echo $data['p_name']; ?></strong><br>
                        <span style="font-size: 0.85rem; color: #888;"><?php echo $data['p_location']; ?></span>
                    </td>
                    <td><?php echo date('d M Y', strtotime($data['from_date'])); ?></td>
                    <td><?php echo $data['travelers']; ?> Person(s)</td>
                    <td class="amount-column">₹<?php echo number_format($total_amount); ?></td>
                </tr>
            </tbody>
        </table>

        <div class="total-section">
            <span class="total-label">Total Amount</span>
            <span class="total-value">₹<?php echo number_format($total_amount); ?></span>
        </div>
    </div>

    <div class="receipt-footer">
        <p>Thank you for choosing TravelEase! We wish you a safe and happy journey.</p>
        <p style="margin-top: 5px;">For support, contact us at: <strong>support@travelease.com</strong></p>
    </div>

    <div class="actions-bar">
        <a href="my_bookings.php" class="back-btn">Back to Bookings</a>
        <button onclick="window.print()" class="print-btn"><i class="fas fa-print"></i> Print Receipt</button>
    </div>
</div>

</body>
</html>
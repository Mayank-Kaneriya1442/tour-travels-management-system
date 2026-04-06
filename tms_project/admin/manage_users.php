<?php 
include('../config/db.php'); 
session_start();
if(!isset($_SESSION['admin'])) { header("location:admin_login.php"); }

// Delete User Logic
if(isset($_GET['del_id'])) {
    $uid = intval($_GET['del_id']);
    // Delete user and their bookings to maintain data integrity
    mysqli_query($conn, "DELETE FROM bookings WHERE user_id='$uid'");
    mysqli_query($conn, "DELETE FROM users WHERE id='$uid'");
    echo "<script>alert('User Deleted Successfully'); window.location='manage_users.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        
        body {
            background-color: #f0f2f5;
            min-height: 100vh;
            overflow-x: hidden;
            color: #333;
        }

        /* Sidebar */
        .sidebar {
            width: 280px;
            height: 100vh;
            background: #fff;
            position: fixed;
            top: 0;
            left: 0;
            padding: 30px 20px;
            display: flex;
            flex-direction: column;
            box-shadow: 4px 0 20px rgba(0,0,0,0.05);
            z-index: 1000;
            transition: 0.3s;
        }

        .brand {
            font-size: 1.6rem;
            font-weight: 700;
            color: #2c3e50;
            text-align: center;
            margin-bottom: 50px;
            text-transform: uppercase;
            letter-spacing: 2px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        .brand i { color: #e73c7e; font-size: 1.8rem; }
        .brand span { color: #e73c7e; }

        .nav-links { list-style: none; flex: 1; }
        .nav-links li { margin-bottom: 10px; }
        .nav-links a { display: flex; align-items: center; padding: 15px 25px; color: #7f8c8d; text-decoration: none; border-radius: 15px; transition: all 0.3s ease; font-weight: 500; position: relative; overflow: hidden; }
        .nav-links a:hover, .nav-links a.active { background: linear-gradient(45deg, #e73c7e, #ee7752); color: white; box-shadow: 0 5px 15px rgba(231, 60, 126, 0.3); transform: translateX(5px); }
        .nav-links a i { margin-right: 15px; width: 25px; text-align: center; font-size: 1.1rem; }
        
        .logout-btn { margin-top: auto; background: #ffebee !important; color: #e74c3c !important; }
        .logout-btn:hover { background: #e74c3c !important; color: white !important; }

        /* Main Content */
        .main-content { margin-left: 280px; padding: 40px 50px; width: calc(100% - 280px); }
        
        .page-header { margin-bottom: 30px; animation: fadeInDown 0.8s ease; }
        .page-header h2 { font-size: 2rem; color: #2c3e50; font-weight: 700; }
        .page-header p { color: #7f8c8d; }

        /* Table Styles */
        .table-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            overflow: hidden;
            animation: fadeInUp 0.8s ease-out;
        }

        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 15px 20px; text-align: left; border-bottom: 1px solid #eee; color: #555; }
        th { background: #f8f9fa; color: #2c3e50; font-weight: 600; text-transform: uppercase; font-size: 0.9rem; }
        tr:hover { background: #fafafa; }

        .btn-delete { color: #e74c3c; text-decoration: none; font-weight: bold; transition: 0.3s; }
        .btn-delete:hover { text-decoration: underline; }

        .search-container {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            margin-bottom: 20px;
            animation: fadeInDown 0.8s ease;
        }
        .search-input {
            flex: 1;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 10px;
            outline: none;
            transition: 0.3s;
        }
        .search-input:focus { border-color: #e73c7e; }
        .btn-search {
            background: linear-gradient(to right, #e73c7e, #ee7752);
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
            gap: 10px;
        }
        .pagination a {
            padding: 8px 15px;
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            text-decoration: none;
            color: #333;
            transition: 0.3s;
        }
        .pagination a.active, .pagination a:hover {
            background: linear-gradient(to right, #e73c7e, #ee7752);
            color: white;
            border-color: transparent;
        }

        @keyframes fadeInDown { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="brand"><i class="fas fa-paper-plane"></i> <span>TravelEase</span></div>
        <ul class="nav-links">
            <li><a href="admin_dashboard.php"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a></li>
            <li><a href="manage_packages.php"><i class="fas fa-globe-americas"></i> <span>Packages</span></a></li>
            <li><a href="manage_users.php" class="active"><i class="fas fa-users"></i> <span>Users</span></a></li>
            <li><a href="manage_bookings.php"><i class="fas fa-bookmark"></i> <span>Bookings</span></a></li>
            <li><a href="manage_payments.php"><i class="fas fa-credit-card"></i> <span>Payments</span></a></li>
            <li><a href="manage_enquiries.php"><i class="fas fa-envelope"></i> <span>Enquiries</span></a></li>
            <li><a href="manage_coupons.php"><i class="fas fa-tags"></i> <span>Coupons</span></a></li>
            <li><a href="manage_gallery.php"><i class="fas fa-images"></i> <span>Gallery</span></a></li>
            <li><a href="../admin/logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="page-header">
            <h2>Registered Users</h2>
            <p>Manage client accounts and details.</p>
        </div>

        <div class="search-container">
            <form method="GET" style="display:flex; width:100%; gap:10px;">
                <input type="text" name="search" class="search-input" placeholder="Search by Name or Email..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                <button type="submit" class="btn-search"><i class="fas fa-search"></i> Search</button>
                <?php if(isset($_GET['search'])): ?>
                    <a href="manage_users.php" class="btn-search" style="background: #95a5a6;">Reset</a>
                <?php endif; ?>
            </form>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Mobile No</th>
                        <th>Joined Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $limit = 10;
                    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                    if($page < 1) $page = 1;
                    $offset = ($page - 1) * $limit;

                    $where = "";
                    $search_param = "";
                    if(isset($_GET['search']) && !empty($_GET['search'])) {
                        $search = mysqli_real_escape_string($conn, $_GET['search']);
                        $where = "WHERE full_name LIKE '%$search%' OR email LIKE '%$search%'";
                        $search_param = "&search=" . urlencode($_GET['search']);
                    }
                    
                    $res = mysqli_query($conn, "SELECT * FROM users $where ORDER BY id DESC");
                    $count_res = mysqli_query($conn, "SELECT COUNT(*) as total FROM users $where");
                    $total_records = mysqli_fetch_assoc($count_res)['total'];
                    $total_pages = ceil($total_records / $limit);

                    $res = mysqli_query($conn, "SELECT * FROM users $where ORDER BY id DESC LIMIT $offset, $limit");
                    if(mysqli_num_rows($res) > 0) {
                        while($row = mysqli_fetch_assoc($res)) {
                            $reg_date = isset($row['reg_date']) ? date('d M Y', strtotime($row['reg_date'])) : 'N/A';
                    ?>
                    <tr>
                        <td>#USR-<?php echo $row['id']; ?></td>
                        <td><div style="font-weight:bold;"><?php echo $row['full_name']; ?></div></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['mobile']; ?></td>
                        <td><?php echo $reg_date; ?></td>
                        <td><a href="manage_users.php?del_id=<?php echo $row['id']; ?>" class="btn-delete" onclick="return confirm('Delete this user? This will also delete their bookings.');"><i class="fas fa-trash"></i> Delete</a></td>
                    </tr>
                    <?php
                        }
                    } else {
                        echo "<tr><td colspan='6' style='text-align:center;'>No users found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <?php if($total_pages > 1): ?>
        <div class="pagination">
            <?php if($page > 1): ?>
                <a href="?page=<?php echo ($page-1) . $search_param; ?>">&laquo; Prev</a>
            <?php endif; ?>
            
            <?php for($i=1; $i<=$total_pages; $i++): ?>
                <a href="?page=<?php echo $i . $search_param; ?>" class="<?php echo ($i == $page) ? 'active' : ''; ?>"><?php echo $i; ?></a>
            <?php endfor; ?>
            
            <?php if($page < $total_pages): ?>
                <a href="?page=<?php echo ($page+1) . $search_param; ?>">Next &raquo;</a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>
<?php 
include('../config/db.php'); 
include('../includes/header.php'); 
?>

<style>
    /* Page Header */
    .page-header {
        background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('../assets/images/tour_packages.jpg');
        background-size: cover;
        background-position: center;
        height: 300px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        text-align: center;
        margin-bottom: 50px;
    }

    .page-header h1 {
        font-size: 3rem;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin: 0;
        animation: fadeInUp 1s ease-out;
    }

    /* Container & Grid */
    .container-custom {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px 60px;
    }

    .packages-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
    }

    /* Card Styles (Matching Index) */
    .package-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        transition: transform 0.3s, box-shadow 0.3s;
        position: relative;
        border: 1px solid #eee;
    }
    
    .package-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 30px rgba(0,0,0,0.15);
    }
    
    .pkg-img {
        height: 220px;
        width: 100%;
        object-fit: cover;
    }
    
    .pkg-content {
        padding: 25px;
    }
    
    .pkg-price {
        position: absolute;
        top: 20px;
        right: 20px;
        background: #e67e22;
        color: white;
        padding: 5px 15px;
        border-radius: 20px;
        font-weight: bold;
        font-size: 0.9rem;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }
    
    .pkg-title {
        font-size: 1.4rem;
        color: #2c3e50;
        margin-bottom: 10px;
        font-weight: 700;
    }
    
    .pkg-meta {
        color: #777;
        font-size: 0.9rem;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    
    .btn-card {
        display: block;
        text-align: center;
        background: #2c3e50;
        color: white;
        padding: 12px;
        border-radius: 8px;
        text-decoration: none;
        transition: background 0.3s;
        font-weight: 600;
    }
    
    .btn-card:hover { background: #34495e; }

    /* Search Filter Styles */
    .filter-bar {
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        margin-bottom: 40px;
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
        align-items: center;
    }

    .filter-input {
        flex: 1;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 5px;
        min-width: 200px;
    }

    .btn-filter {
        padding: 12px 25px;
        background: #e67e22;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-weight: bold;
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<div class="page-header">
    <div>
        <h1>Our Tour Packages</h1>
        <p style="font-size: 1.2rem; margin-top: 10px; opacity: 0.9;">Choose your next dream destination</p>
    </div>
</div>

<div class="container-custom">
    
    <!-- Search Filter -->
    <form method="GET" class="filter-bar">
        <input type="text" name="search" class="filter-input" placeholder="Search by Destination or Package Name..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
        <input type="number" name="budget" class="filter-input" placeholder="Max Budget (₹)" value="<?php echo isset($_GET['budget']) ? htmlspecialchars($_GET['budget']) : ''; ?>">
        <button type="submit" class="btn-filter">Filter Packages</button>
        <?php if(isset($_GET['search']) || isset($_GET['budget'])): ?>
            <a href="tour_packages.php" style="color: #e67e22; text-decoration: none; font-weight: 600;">Clear Filters</a>
        <?php endif; ?>
    </form>

    <div class="packages-grid">
        <?php
        $where = "WHERE 1=1";
        if(isset($_GET['search']) && !empty($_GET['search'])) {
            $s = mysqli_real_escape_string($conn, $_GET['search']);
            $where .= " AND (p_name LIKE '%$s%' OR p_location LIKE '%$s%')";
        }
        if(isset($_GET['budget']) && !empty($_GET['budget'])) {
            $b = mysqli_real_escape_string($conn, $_GET['budget']);
            $where .= " AND p_price <= '$b'";
        }

        $query = "SELECT * FROM packages $where ORDER BY p_id DESC";
        $res = mysqli_query($conn, $query);

        if(mysqli_num_rows($res) > 0) {
            while($row = mysqli_fetch_assoc($res)) {
        ?>
        <div class="package-card">
            <div class="pkg-price">₹<?php echo number_format($row['p_price']); ?></div>
            <img src="../assets/images/<?php echo $row['p_image']; ?>" alt="<?php echo $row['p_name']; ?>" class="pkg-img">
            <div class="pkg-content">
                <h3 class="pkg-title"><?php echo $row['p_name']; ?></h3>
                <div class="pkg-meta">
                    <i class="fas fa-map-marker-alt" style="color:#e67e22;"></i> <?php echo $row['p_location']; ?>
                </div>
                <div class="pkg-meta">
                    <i class="fas fa-tag" style="color:#e67e22;"></i> <?php echo $row['p_type']; ?>
                </div>
                <p style="color:#666; font-size:0.95rem; margin-bottom:20px; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.6;">
                    <?php echo $row['p_details']; ?>
                </p>
                <a href="package_details.php?id=<?php echo $row['p_id']; ?>" class="btn-card">View Details & Book</a>
            </div>
        </div>
        <?php
            }
        } else {
            echo "<div style='grid-column: 1/-1; text-align: center; padding: 50px;'>
                    <h3 style='color: #7f8c8d;'>No packages found matching your criteria.</h3>
                    <a href='tour_packages.php' style='color: #e67e22; font-weight: bold;'>View All Packages</a>
                  </div>";
        }
        ?>
    </div>
</div>

<?php include('../includes/footer.php'); ?>
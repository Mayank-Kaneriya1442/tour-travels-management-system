<?php 
include('../config/db.php'); 
include('../includes/header.php'); 
?>

<style>
    /* Gallery Page Styles */
    .gallery-hero {
        background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('../assets/images/gallery.jpg');
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

    .gallery-hero h1 {
        font-size: 3rem;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin: 0;
        animation: fadeInUp 1s ease-out;
    }

    .container-custom {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px 60px;
    }

    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 25px;
    }

    .gallery-item {
        position: relative;
        overflow: hidden;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        cursor: pointer;
        height: 280px;
    }

    .gallery-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .gallery-item:hover img {
        transform: scale(1.1);
    }

    .gallery-overlay {
        position: absolute;
        bottom: -100%;
        left: 0;
        width: 100%;
        background: linear-gradient(to top, rgba(0,0,0,0.9), transparent);
        padding: 20px;
        box-sizing: border-box;
        transition: bottom 0.3s ease;
        color: white;
        text-align: left;
    }

    .gallery-item:hover .gallery-overlay {
        bottom: 0;
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<div class="gallery-hero">
    <div>
        <h1>Destination Gallery</h1>
        <p style="font-size: 1.2rem; margin-top: 10px; opacity: 0.9;">Capturing Moments from Around the World</p>
    </div>
</div>

<div class="container-custom">
    <div class="gallery-grid">
        <?php
        $res = mysqli_query($conn, "SELECT p_image, p_name, p_location FROM packages");
        if(mysqli_num_rows($res) > 0) {
            while($row = mysqli_fetch_assoc($res)) {
        ?>
            <div class="gallery-item">
                <img src="../assets/images/<?php echo $row['p_image']; ?>" alt="<?php echo $row['p_name']; ?>">
                <div class="gallery-overlay">
                    <h3 style="margin: 0; font-size: 1.3rem;"><?php echo $row['p_name']; ?></h3>
                    <p style="margin: 5px 0 0; font-size: 0.9rem; color: #e67e22;">
                        <i class="fas fa-map-marker-alt"></i> <?php echo $row['p_location']; ?>
                    </p>
                </div>
            </div>
        <?php
            }
        } else {
            echo "<p style='text-align:center; width:100%; grid-column: 1/-1; color: #777;'>No images available in the gallery.</p>";
        }
        ?>
    </div>
</div>

<?php include('../includes/footer.php'); ?>
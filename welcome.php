<?php
include './functions.php';
//session_start();
if (!isset($_SESSION["admin_id"]) || $_SESSION["admin_id"] == 0) {
//   		p($_SESSION['admin_id']);
    header('Location: index.php');
}


?>
<?php include 'layout/header.php'; ?>	
<?php include 'layout/menu.php'; ?>
<!-- BEGIN PAGE -->
<style>
   .dashboard-stat .details .desc{
        font-size: 13px !important;
    }
</style>
<div class="page-content">
    <div id="welcome_container">
        <div class="row">
            <h2>Welcome </h2>
        </div>  
    </div>
</div>
<!-- END PAGE -->
</div>
<!-- END CONTAINER -->

<?php include 'layout/footer.php'; ?>
<?php
//include 'common.php';

include './functions.php';

if (!isset($_SESSION["admin_id"]) || $_SESSION["admin_id"] == 0) {
    header('Location: index.php');
}
?>
<?php
if (isset($_REQUEST['id']) && $_REQUEST['id'] != '') {

    $id = $_REQUEST['id'];
//p($id);
    $DeleteString = "DELETE FROM coupon_code WHERE cc_id = $id";

    $query = mysql_query($DeleteString);

    $_SESSION['success'] = "Coupon  deleted success";

    echo json_encode(['status' => 0]);
}
if (isset($_GET['status']) && $_GET['status'] != '') {
    $id = $_GET['cc_id'];
    $UpdateString = "UPDATE coupon_code SET cc_status = '" . $_GET['status'] . "' WHERE cc_id = '$id'";
    $query = mysql_query($UpdateString);
    $_SESSION['success'] = "Coupon Code Status Changed success";
}

include 'layout/header.php';
?>	

<script type="text/javascript" class="init">

    $(document).ready(function () {

        var id_table = 'sample_6';

        var url = "<?php echo 'couponcodepaginationList.php' ?>";

        var mdatarow = [
            
            {"mData": "cc_id"},
            
            {"mData": "cc_name"},
            
            {"mData": "cc_code"},
            
            {"mData": "cc_description"},
            
            {"mData": "cc_discount_type"},
            
             {"mData": "cc_discount_amount"},
            
            {"mData": "cc_start_date"},
            
            {"mData": "cc_end_date"},
            
            {"mData": "cc_status"},

            {"mData": "action"}
        
        ];

        ajaxresponsiveDataTable(id_table, url, mdatarow);

    });

</script>
<?php include 'layout/menu.php'; ?>

<!-- BEGIN PAGE -->

<div class="page-content">

    <div class="portlet ">

        <div class="portlet-title">

            <div class="caption">All COUPON</div>

        </div>



    </div>

    <a href="addcouponcode.php" class="btn btn-info">

        <i class="fa fa-plus"></i> New Coupon 

    </a>

    <div>

 <?php showFlash();?>

    </div>

    <div class="table-responsive">

        <table id="sample_6" class="table table-bordered table-striped js-dataTable-full">

            <thead>

                <tr>

                    <th class="text-center">#</th>

                    <th>Title</th>
                    
                    <th>Code</th>
                    
                    <th>Description</th>
                    
                    <th>Discount Type</th>

                    <th>Discount</th>
                    
                    <th>Start date</th>
                    
                    <th>End Date</th>
                    
                     <th>Status</th>

                    <th>Action</th>

                </tr>

            </thead>

        </table>

    </div>

</div>

<!-- END PAGE -->

</div>

<!-- END CONTAINER -->

<script type="text/javascript" src="assets/datatables/responsive/datatables.responsive.js"></script>

<script type="text/javascript" src="assets/ajaxresponsivedatatable.js"></script>

<script type="text/javascript" src="assets/datatables_r/media/js/jquery.dataTables.min.js"></script>

<script type="text/javascript" src="assets/datatables/media/assets/js/datatables.js"></script>

<script type="text/javascript" src="assets/datatables/extras/TableTools/media/js/TableTools.min.js"></script>

<script type="text/javascript" src="assets/datatables/extras/TableTools/media/js/ZeroClipboard.js"></script>

<script type="text/javascript" src="assets/underscore.min.js"></script>

<script>

    function deleteCoupon(id)

    {

        swal({
            title: "<?php echo 'Are you sure?'; ?>",
            text: "<?php echo 'Delete all data about this coupon after delete not recover!!'; ?>",
            confirmButtonText: "<?php echo 'Yes, delete it!'; ?>",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "<?= 'black'; ?>",
            cancelButtonText: "",
            closeOnConfirm: false,
            closeOnCancel: true

        },
        function (isConfirm) {

            if (isConfirm) {

                swal({title: "<?php echo 'Processing...'; ?>", html: true, showConfirmButton: false});

                /* Ajax call start here */

                $.ajax({
                    type: 'POST',
                    url: '?id=' + id,
                    success: function (response)

                    {

                        window.location.href = '?type=s&msg=Coupon Deleted Success';

                    }

                });

            }

        });

    }
    function changeStatus(id, status) {
        swal({
            title: "<?php echo 'Are you sure?'; ?>",
            text: "<?php echo 'Are You Sure You want to Change the Status..!!'; ?>",
            confirmButtonText: "<?php echo 'Yes, Change it!'; ?>",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "<?= 'black'; ?>",
            cancelButtonText: "",
            closeOnConfirm: false,
            closeOnCancel: true
        },
        function (isConfirm) {
            if (isConfirm) {
                swal({title: "<?php echo 'Processing...'; ?>", html: true, showConfirmButton: false});
                / Ajax call start here /
                $.ajax({
                    type: 'GET',
                    url: '?cc_id=' + id + '&status=' + status,
                    success: function (response)
                    {
                        window.location = '?type=s&msg=Coupon Changed Success';
                    }
                });
            }
        });
    }

</script>

<?php
include 'layout/footer.php';
?>




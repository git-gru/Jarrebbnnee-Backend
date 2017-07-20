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
    $DeleteString = "DELETE FROM product_category WHERE pc_id = $id";

    $query = mysql_query($DeleteString);

    $_SESSION['success'] = "Category deleted success";

    echo json_encode(['status' => 0]);
}


include 'layout/header.php';
?>	

<script type="text/javascript" class="init">

    $(document).ready(function () {

        var id_table = 'sample_6';

        var url = "<?php echo 'categorypaginationList.php' ?>";

        var mdatarow = [
            
            {"mData": "pc_id"},
            
            {"mData": "pc_name"},
            
//            {"mData": "c_images"},
//            
//            {"mData": "c_is_parent_id"},
//            
//            {"mData": "c_total_services"},
            
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

            <div class="caption">All CATEGORY</div>

        </div>



    </div>

    <a href="addcategory.php" class="btn btn-info">

        <i class="fa fa-plus"></i> New Category 

    </a>

    <div>

 <?php showFlash();?>

    </div>

    <div class="table-responsive">

        <table id="sample_6" class="table table-bordered table-striped js-dataTable-full">

            <thead>

                <tr>

                    <th class="text-center">#</th>

                    <th>Category</th>
                    
<!--                    <th>Images</th>

                    <th>Parent Category</th>

                    <th>Total Services</th>-->
                    
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

    function deleteUser(id)

    {

        swal({
            title: "<?php echo 'Are you sure?'; ?>",
            text: "<?php echo 'Delete all data about this service after delete not recover!!'; ?>",
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

                        window.location.href = '?type=s&msg=Category Deleted Success';

                    }

                });

            }

        });

    }
    function changeStatus(id, status) {
        swal({
            title: "<?php echo 'Are you sure?'; ?>",
            text: "<?php echo 'Are You Sure You want to Change the Type..!!'; ?>",
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
                    url: '?uid=' + id + '&status=' + status,
                    success: function (response)
                    {
                        window.location = '?type=s&msg=Category Type Changed Success';
                    }
                });
            }
        });
    }

</script>

<?php
include 'layout/footer.php';
?>




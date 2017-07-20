<?php
//include 'common.php';

include './functions.php';

if (!isset($_SESSION["admin_id"]) || $_SESSION["admin_id"] == 0) {
    header('Location: index.php');
}
?>
<?php
if (isset($_GET['pd_id']) && $_GET['pd_id'] != '') {
    $pd_id = $_GET['pd_id'];
    $SelectString = "SELECT * FROM product_details WHERE pd_id = $pd_id";
    $query = mysql_query($SelectString);
    $productData = mysql_fetch_assoc($query);
} else {
    header('location:product_list.php?type=e&msg=Invalid Product selection. Select exact product to see details.');
    exit;
}
include 'layout/header.php';
?>	
<script type="text/javascript" class="init">
    $(document).ready(function () {
        var id_table = 'sample_6';
        var url = "<?php echo 'productimgpaginationList.php?product_ref_id='.$productData['pd_refer_id'] ?>";
        var mdatarow = [
            {"mData": "pir_id"},
            {"mData": "pir_product_id"},
            {"mData": "pir_image"},
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
            <div class="caption">PRODUCT DETAILS</div>
        </div>
    </div>
  
    <div>
        <?php if (isset($_GET['type']) && $_GET['type'] == 's' && isset($_GET['msg'])): ?>
            <div class="alert alert-success">
                <button class="close" data-close="alert"></button>
                <h4><?php echo 'Success'; ?></h4>
                <?php
                echo $_GET['msg'];
                ?>
            </div>
            <div class="clear"></div>
        <?php endif; ?>
        <?php if (isset($_GET['type']) && $_GET['type'] == 'e' && isset($_GET['msg'])): ?>
            <div class="alert alert-danger">
                <button class="close" data-close="alert"></button>
                <h4><?php echo 'Error'; ?></h4>
                <span>  <?php
                    echo $_GET['msg'];
                    ?>
                </span>
            </div>
        <?php endif; ?>
    </div>
    <div class="table-responsive">
        <table id="sample_6" class="table table-bordered table-striped js-dataTable-full">
            <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th style="width: 15%">Product Ref Id</th>
                    <th>Product Image</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<!-- END PAGE -->
<!-- END CONTAINER -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content" id="submitQuotationpopup">
            <form name="myQuotationForm" role="form" action="controller.php?action=saveProductImport" method="post" enctype="multipart/form-data" novalidate>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><strong>Add Product CSV File </strong></h4>
                </div>
                <div class="modal-body">
                    <div class="row form-group" style=" margin-left: 0px;">
                        <label class="control-label">CSV Files</label>
                        <input type="file" class="form-control input-xlarge" name="product_file" required="true"/>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="save" class="btn btn-success">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div id="myModal-img" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content" id="submitQuotationpopup">
            <form name="myQuotationForm" role="form" action="controller.php?action=saveProductImportImg" method="post" enctype="multipart/form-data" novalidate>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><strong>Add Product CSV File of Images </strong></h4>
                </div>
                <div class="modal-body">
                    <div class="row form-group" style=" margin-left: 0px;">
                        <label class="control-label">CSV Files</label>
                        <input type="file" class="form-control input-xlarge" name="product_images" required="true"/>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="save" class="btn btn-success">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
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




<?php
//include 'common.php';

include './functions.php';

if (!isset($_SESSION["admin_id"]) || $_SESSION["admin_id"] == 0) {
    header('Location: index.php');
}
?>
<?php
//p($_REQUEST);
if (isset($_REQUEST['id']) && $_REQUEST['id'] != '') {
    $id = $_REQUEST['id'];
    if (isset($_REQUEST['pd_refer_id']) && $_REQUEST['pd_refer_id'] != '') {
        $pd_refer_id = $_REQUEST['pd_refer_id'];
        $DeleteString1 = "DELETE FROM product_details WHERE pd_id = $id";
        $query = mysql_query($DeleteString1);
        $DeleteString2 = "DELETE FROM product_img_rel WHERE pir_product_id = $pd_refer_id";
        $query2 = mysql_query($DeleteString2);
        $_SESSION['success'] = "Product deleted success";
        echo json_encode(['status' => 0]);
    }
}
include 'layout/header.php';
?>	
<script type="text/javascript" class="init">
    $(document).ready(function () {
        var id_table = 'sample_6';
        var url = "<?php echo 'productpaginationList.php' ?>";
        var mdatarow = [
            {"mData": "pd_id"},
            {"mData": "pd_name"},
            {"mData": "pd_price"},
            {"mData": "pd_weight"},
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
            <div class="caption">All PRODUCTS</div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3 pull-right">
            <a data-toggle="modal" data-target="#myModal-img" href="javascript:;" class="btn btn-info pull-right" style="margin-bottom: 10px;">
                <i class="fa fa-plus"></i> Import CSV for Product Images
            </a>
        </div>
        <div class="col-md-3 pull-right">
            <a data-toggle="modal" data-target="#myModal" href="javascript:;" class="btn btn-info pull-right" style="margin-bottom: 10px;">
                <i class="fa fa-plus"></i> Import CSV for Product
            </a>
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
                    <th style="width: 55%">Product name</th>
                    <th>Product Price</th>
                    <th>Product Weight</th>
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
    function deleteUser(id, pd_refer_id)
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
                    url: '?id=' + id + '&pd_refer_id=' + pd_refer_id,
                    success: function (response)
                    {
                        window.location.href = '?type=s&msg=Product Deleted Success';
                    }
                });
            }
        });
    }


</script>
<?php
include 'layout/footer.php';
?>




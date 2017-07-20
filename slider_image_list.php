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
    $DeleteString = "DELETE FROM slider_img_relation WHERE sir_id = '$id'";
    $query = mysql_query($DeleteString);
    $_SESSION['success'] = "Slider Image deleted success";
    echo json_encode(['status' => 0]);
}
$s_id = '';
if (isset($_GET['s_id']) && $_GET['s_id'] != ''){
      $s_id = $_GET['s_id'];
}
  


include 'layout/header.php';
?>	

<script type="text/javascript" class="init">

    $(document).ready(function () {

        var id_table = 'sample_6';

        var url = "<?php echo 'sliderimagepaginationList.php?' ?>";

<?php
if (isset($_GET['s_id']) && $_GET['s_id'] != '') {
    ?>
            url += 's_id=<?php echo $_GET['s_id']; ?>';
    <?php
}
?>
        var mdatarow = [
            {"mData": "srno"},
            {"mData": "sir_id"},
            {"mData": "sir_img"},
            {"mData": "sir_heading_text"},
            {"mData": "sir_sub_heading_text"},
            {"mData": "sir_created"},
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

            <div class="caption">All SLIDER DATA</div>

        </div>



    </div>
    <a href="addslider_image.php?s_id=<?php echo $s_id;?>" class="btn btn-info">

        <i class="fa fa-plus"></i> New Slider Image 

    </a>

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

                    <th class="text-center">Srno</th>
                    <th class="text-center">#</th>
                    <th>Image</th>
                    <th>Slider Heading</th>
                    <th>Slider Sub Heading</th>
                    <th>date</th>

                    <th>Action</th>

                </tr>

            </thead>

        </table>

    </div>

</div>

<!-- END PAGE -->

</div>
<!-- POPUP MODEL START -->

<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content" id="submitQuotationpopup">
            <form name="myQuotationForm" role="form" action="" method="post" enctype="multipart/form-data" novalidate>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><strong>Add Slider Details  </strong></h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label">MAIN TITLE<span class="required">*</span></label>
                        <input required="true" type="text" class="form-control input-xlarge" value="<?php echo $slider_mtitle ?>" name="slider_mtitle" id="c_title"/>
                    </div>
                    <div class="form-group">
                        <label class="control-label">SUB TITLE<span class="required">*</span></label>
                        <input required="true" type="text" class="form-control input-xlarge" value="<?php echo $slider_stitle ?>" name="slider_stitle" id="c_title"/>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Slider Image</label>
                        <input type="file" class="form-control input-xlarge" value="<?php echo $c_images; ?>" name="slider_img"/>
                        <?php
                        if (isset($sliderData['slider_id']) && $sliderData['slider_id'] != '') {
                            if (isset($slider_images) && $slider_images != '') {
                                ?>
                                <img src="uploads/slider/<?php echo $c_images ?>" width="150" height="150"> <?php } else {
                                ?>
                                <img src="uploads/slider/no-image.png"width="150" height="150">
                                <?php
                            }
                        }
                        ?>
                    </div>
                    <div class="margin-top-20">
                        <button class="btn blue" type="submit" name="submit"> Save Changes </button>
                        <a href="slider_image_list.php" class="btn default"> Cancel </a>
                    </div>
                </div>
                <!--                <div class="modal-footer">
                                    <button type="submit" name="save" class="btn btn-success">Save</button>
                                </div>-->
            </form>
        </div>
    </div>
</div>
<!-- POPUP MODEL END -->

<!-- END CONTAINER -->

<script type="text/javascript" src="assets/datatables/responsive/datatables.responsive.js"></script>

<script type="text/javascript" src="assets/ajaxresponsivedatatable.js"></script>

<script type="text/javascript" src="assets/datatables_r/media/js/jquery.dataTables.min.js"></script>

<script type="text/javascript" src="assets/datatables/media/assets/js/datatables.js"></script>

<script type="text/javascript" src="assets/datatables/extras/TableTools/media/js/TableTools.min.js"></script>

<script type="text/javascript" src="assets/datatables/extras/TableTools/media/js/ZeroClipboard.js"></script>

<script type="text/javascript" src="assets/underscore.min.js"></script>

<script>
    function deleteSliderImage(id, s_id) {
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
                $.ajax({
                    type: 'POST',
                    url: '?id=' + id,
                    success: function (response) {
                        window.location.href = '?s_id=' + s_id + '&type=s&msg=Slider Image Deleted Success';
                    }
                });
            }
        });
    }
</script>

<?php
include 'layout/footer.php';
?>




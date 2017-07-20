<?php
//include 'common.php';

include './functions.php';

if (!isset($_SESSION["admin_id"]) || $_SESSION["admin_id"] == 0) {
    header('Location: index.php');
}
?>
<?php
include 'layout/header.php';
?>	

<script type="text/javascript" class="init">

    $(document).ready(function () {

        var id_table = 'sample_6';

        var url = "<?php echo 'servicepaginationList.php' ?>";

        var mdatarow = [
            {"mData": "s_id"},
            {"mData": "s_name"},
            {"mData": "s_status"},
            {"mData": "s_create"},
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

            <div class="caption">My Settings</div>

        </div>



    </div>




    <div class="row">
        <div class="col-md-12">
            <div class="tabbable-line">
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a href="#tab_1_1" data-toggle="tab"><i class="fa fa-anchor"></i> Statistics</a>
                    </li>
                    <li>
                        <a href="#tab_1_2" data-toggle="tab"><i class="fa fa-cogs"></i> Other Settings
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab_1_1">
                        <div class="row">
                            <div class="col-md-12" style="text-align: center;">
                                <h3>Under development</h3>
                                <h4>Thanks For visit :)</h4>
                            </div>
                        </div>
<!--                        <div class="row">
                            <div class="col-md-12">
                                <div class="portlet light ">
                                    <div class="portlet-body">
                                        <form role="form" action="" method="post" enctype="multipart/form-data">
                                            <div class="form-group">
                                                <label class="control-label">TOTAL NUMBER OF SUBSCRIBER<span class="required">*</span></label>
                                                <input required="true" type="text" class="form-control input-xlarge" value="" name="s_name" id="u_name"/>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label">TOTAL NUMBER OF USERS<span class="required">*</span></label>
                                                <input required="true" type="text" class="form-control input-xlarge" value="" name="s_name" id="u_name"/>
                                            </div>

                                            <div class="margin-top-20">
                                                <button class="btn blue" type="submit" name="submit"> Save Changes </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>-->
                    </div>
                    <div class="tab-pane" id="tab_1_2">
                        <div class="row">
                            <div class="col-md-12" style="text-align: center;">
                                <h3>Under development</h3>
                                <h4>Thanks For visit :)</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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

                        window.location.href = '?type=s&msg=Service Deleted Success';

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
                        window.location = '?type=s&msg=Service Type Changed Success';
                    }
                });
            }
        });
    }

</script>

<?php
include 'layout/footer.php';
?>




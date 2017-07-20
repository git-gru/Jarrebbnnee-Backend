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
    $DeleteString = "DELETE FROM user WHERE u_id = $id";
    $query = mysql_query($DeleteString);
    $_SESSION['success'] = "User deleted success";
    echo json_encode(['status' => 0]);
}
if (isset($_GET['status']) && $_GET['status'] != '') {
    $id = $_GET['uid'];
    $UpdateString = "UPDATE user SET u_status = '" . $_GET['status'] . "' WHERE u_id = '$id'";
    $query = mysql_query($UpdateString);
    $_SESSION['success'] = "User Status Changed success";
}

include 'layout/header.php';
?>	

<script type="text/javascript" class="init">

    $(document).ready(function () {

        var id_table = 'sample_6';

        var url = "<?php echo 'userpaginationList.php' ?>";

        var mdatarow = [

            {"mData": "u_id"},
            {"mData": "u_first_name"},
            {"mData": "u_email"},
            //{"mData": "u_status"},
            //{"mData": "u_created"},
            {"mData": "action"}
        ];

        ajaxresponsiveDataTable(id_table, url, mdatarow);

    });

</script>
<?php 



include 'layout/menu.php'; ?>

<!-- BEGIN PAGE -->

<div class="page-content">

    <div class="portlet ">

        <div class="portlet-title">

            <div class="caption">All USERS</div>

        </div>



    </div>

    <a href="adduser.php" class="btn btn-info">

         <i class="fa fa-plus"></i> New User 

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
                
                <th class="text-center">#</th>

                <th>Name</th>

                <th>Email</th>

<!--                <th>Status</th>
                
                <th>Date</th>-->

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

            text: "<?php echo 'Delete all data about this user after delete not recover!!'; ?>",

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

                        window.location.href='?type=s&msg=User Deleted Success';

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




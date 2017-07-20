<?php
include './functions.php';
if (!isset($_SESSION["admin_id"]) || $_SESSION["admin_id"] == 0) {

    header('Location: index.php');
}
include 'layout/header.php';
?>
<!-- BEGIN PAGE -->

<style>
    .padleftright{
        padding-left: 15px;
        padding-right: 15px;
    }
    #myImg {
        border-radius: 5px;
        cursor: pointer;
        transition: 0.3s;
    }

    #myImg:hover {opacity: 0.7;}

    /* The Modal (background) */
    .modal {
        display: none; /* Hidden by default */
        position: fixed; /* Stay in place */
        z-index: 1; /* Sit on top */
        padding-top: 100px; /* Location of the box */
        left: 0;
        top: 0;
        width: 100%; /* Full width */
        height: 100%; /* Full height */
        overflow: auto; /* Enable scroll if needed */
        background-color: rgb(0,0,0); /* Fallback color */
        background-color: rgba(0,0,0,0.9); /* Black w/ opacity */
    }

    /* Modal Content (image) */
    .modal-content {
        margin: auto;
        display: block;
        width: 80%;
        max-width: 700px;
    }

    /* Caption of Modal Image */
    #caption {
        margin: auto;
        display: block;
        width: 80%;
        max-width: 700px;
        text-align: center;
        color: #ccc;
        padding: 10px 0;
        height: 150px;
    }

    /* Add Animation */
    .modal-content, #caption {    
        -webkit-animation-name: zoom;
        -webkit-animation-duration: 0.6s;
        animation-name: zoom;
        animation-duration: 0.6s;
    }

    @-webkit-keyframes zoom {
        from {-webkit-transform:scale(0)} 
        to {-webkit-transform:scale(1)}
    }

    @keyframes zoom {
        from {transform:scale(0)} 
        to {transform:scale(1)}
    }

    /* The Close Button */
    .close-p {
        position: absolute;
        top: 15px;
        right: 35px;
        color: #000;
        font-weight: bold;
        transition: 0.3s;
    }

    .close-p:hover,
    .close-p:focus {
        color: #bbb;
        text-decoration: none;
        cursor: pointer;
    }

    /* 100% Image Width on Smaller Screens */
    @media only screen and (max-width: 700px){
        .modal-content {
            width: 100%;
        }
    }.pimages{
        cursor: pointer;
        height:120px !important;
        width:120px !important;
        margin: 5px !important;
    }
    .imageSec{
        width: 13%;
        float: left;
        text-align: center;
    }
    .padleft{
        padding-left: 0px;
    }
    .supDetails{
        padding: 10px;
        border: 1px #d8d8d8 solid;
        background: #b1defb;
    }
    .supDetails h4{
        font-weight: 600 !important;
    }
    .custDetails{
        padding: 10px;
        border: 1px #d8d8d8 solid;
        background: #edc07f;
    }
    .custDetails h4{
        font-weight: 600 !important;
    }
    .supDetail>tbody>tr>td{
        border-top: 1px solid #545454 !important;
    }
    .custDetail>tbody>tr>td{
        border-top: 1px solid #545454 !important;
    }
</style>
<?php include 'layout/menu.php'; ?>

<div class="page-content">
    <div class="tab-content">
        <div class="tab-pane active" id="tab4">
            <?php
            $product_id = isset($_GET['pd_id']) ? $_GET['pd_id'] : "";
            $selectProd = "SELECT * FROM product_details WHERE pd_id = '" . $product_id . "'";
            $queryProd = mysql_query($selectProd);
            $rowProd = mysql_fetch_assoc($queryProd);

            $selectImg = "SELECT * FROM product_img_rel WHERE pir_product_id = '" . $rowProd['pd_refer_id'] . "'";
            $queryImg = mysql_query($selectImg);
            $dataImg = [];
            $i = 0;
            while ($rowImg = mysql_fetch_assoc($queryImg)) {
                $dataImg[$i] = $rowImg;
                $i++;
            }
            // image delete 
            if (isset($_REQUEST['id']) && $_REQUEST['id'] != '') {
                $id = $_REQUEST['id'];
                $DeleteImage = "DELETE FROM product_img_rel WHERE pir_id = $id";
                $query = mysql_query($DeleteImage);
                $_SESSION['success'] = "Product Image deleted success";
                echo json_encode(['status' => 0]);
            }
            ?>	

            <div class="form-group" style="margin-top: -10px;margin-bottom: -10px;">
                <a href="product_list.php" class="btn btn-info" style="margin-bottom: 10px; float: right; margin-right: 10px;"> <i class="fa fa-backward"></i> Back</a>
                <div>
                    <p class="ch_title" style="font-size:20px !important; margin-bottom:0px;">
                        Product Name : <?php echo isset($rowProd['pd_name']) ? $rowProd['pd_name'] : ""; ?> 
                    </p>
                </div>
            </div>
            <hr>
            <div class="row col-md-12">
                <table class="table table-bordered"  style="width:50%">
                    <tr>
                        <td style="width: 20%;"><h5>Product Options</h5></td>
                        <td style="width: 20%;"><?php echo $rowProd['pd_option']; ?></td>
                    </tr>
                    <tr>
                        <td style="width: 20%;"><h5>Product Weight</h5></td>
                        <td style="width: 20%;"><?php echo $rowProd['pd_weight']; ?></td>
                    </tr>
                    <tr>
                        <td style="width: 20%;"><h5>Product Price</h5></td>
                        <td style="width: 20%;"><?php echo "$".$rowProd['pd_price']; ?></td>
                    </tr>
                </table>
                <h4 style="font-weight:600 !important">Product Image</h4>
                <?php
                foreach ($dataImg as $image) {
                    //p($image['pir_id']);
                    ?>
                    <div class="imageSec">
                        <img class="pimages" id="myImg-<?= $image['pir_id'] ?>" src="uploads/products/<?php if (isset($image['pir_image'])) echo $image['pir_image']; ?>" alt="" width="" height="140" style="width: 140px;"/>
                        <a class="btn btn-xs btn-danger" id="removeButton_1" onclick="removeProductImage('<?php echo $image['pir_id']; ?>')" style="margin-top:4px;">Delete</a>
                    </div>
                    <div id="myModal-<?= $image['pir_id'] ?>" class="modal">
                        <span class="close-<?= $image['pir_id'] ?> close-p btn btn-default btn-xs">&times;</span>
                        <img class="modal-content" id="img01-<?= $image['pir_id'] ?>">
                        <div id="caption-<?= $image['pir_id'] ?>"></div>
                    </div>
                    <script>
                        // Get the modal
                        var modal = document.getElementById('myModal-<?= $image['pir_id'] ?>');
                        // Get the image and insert it inside the modal - use its "alt" text as a caption
                        var img = document.getElementById('myImg-<?= $image['pir_id'] ?>');
                        var modalImg = document.getElementById("img01-<?= $image['pir_id'] ?>");
                        var captionText = document.getElementById("caption-<?= $image['pir_id'] ?>");
                        img.onclick = function () {
                            modal.style.display = "block";
                            modalImg.src = this.src;
                            captionText.innerHTML = this.alt;
                        }
                        // Get the <span> element that closes the modal
                        var span = document.getElementsByClassName("close-<?= $image['pir_id'] ?>")[0];
                        // When the user clicks on <span> (x), close the modal
                        span.onclick = function () {
                            modal.style.display = "none";
                        }
                    </script>


                <?php } ?>
            </div>
        </div>
    </div>
</div>
<!-- END PAGE -->
</div>
<script>
    function removeProductImage(id)
    {
        var pd_id = '<?php echo $_GET['pd_id']; ?>';
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
                        window.location.href = '?pd_id=' + pd_id + '&product_id&type=s&msg=Product Image Deleted Success';
                    }
                });
            }
        });
    }


</script>
<!-- END CONTAINER -->

<?php include 'layout/footer.php'; ?>
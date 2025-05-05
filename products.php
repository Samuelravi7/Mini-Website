<?php
include_once 'products_crud.php';
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>ScanMaster : Products</title>
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">

    <link rel="shortcut icon" type="image/jpg" href="favicon.ico"/>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>
        input[type="file"] {
            display: none;
        }
    </style>
</head>
<body>
<?php include_once 'nav_bar.php'; ?>
<?php
// Shows form if the user is logged in AND have admin role.
//if (isset($_SESSION['user']) && $_SESSION['user']['FLD_STAFF_ROLE'] == 'admin') {
?>
<div class="container-fluid dark" style="padding-bottom: 30px;">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="page-header">
                    <?php
                    if (isset($editrow) && count($editrow) > 0) {
                        echo "<h2>Editing #" . $fID . "</h2>";
                    } else {
                        echo "<h2>Create New Product</h2>";
                    }
                    ?>
                </div>

                <?php
                if (isset($_SESSION['error'])) {
                    echo "<p class='text-danger text-center'>{$_SESSION['error']}</p>";
                    unset($_SESSION['error']);
                }
                ?>
            </div>

            <form action="products.php" method="post" class="form-horizontal">
              <div class="form-group">
                  <label for="productid" class="col-sm-3 control-label">ID</label>
                  <div class="col-sm-9">
                  <input name="pid" type="text" class="form-control" id="productid" placeholder="Product ID" value="<?php if(isset($_GET['edit'])) echo $editrow['fld_product_num']; ?>" required>
                </div>
                </div>
                    <div class="form-group">
                        <label for="productname" class="col-sm-3 control-label">Name</label>
                        <div class="col-sm-9">
                            <input name="name" type="text" class="form-control" id="productname"
                                   placeholder="Product Name"
                                   value="<?php if (isset($_GET['edit'])) echo $editrow['fld_product_name']; ?>"
                                   required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="productprice" class="col-sm-3 control-label">Price</label>
                        <div class="col-sm-9">
                            <input name="price" type="number" class="form-control" id="productprice"
                                   placeholder="Product Price"
                                   value="<?php if (isset($_GET['edit'])) echo number_format($editrow['fld_product_price'], 2); ?>"
                                   min="0.0" step="0.01" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="productbrand" class="col-sm-3 control-label">Brand</label>
                        <div class="col-sm-9">
                            <select name="brand" class="form-control" id="productbrand" required>
                                <option value="">Please select</option>
                                <option value="CANON" <?php if (isset($_GET['edit'])) if ($editrow['fld_product_brand'] == "CANON") echo "selected"; ?>>
                                    CANON
                                </option>
                                <option value="HP" <?php if (isset($_GET['edit'])) if ($editrow['fld_product_brand'] == "HP") echo "selected"; ?>>
                                    HP
                                </option>
                                <option value="BROTHER" <?php if (isset($_GET['edit'])) if ($editrow['fld_product_brand'] == "BROTHER") echo "selected"; ?>>
                                    BROTHER
                                </option>
                                <option value="A4 MASTER" <?php if (isset($_GET['edit'])) if ($editrow['fld_product_brand'] == "A4 MASTER") echo "selected"; ?>>
                                    A4 MASTER
                                </option>
                                <option value="EPSON" <?php if (isset($_GET['edit'])) if ($editrow['fld_product_brand'] == "EPSON") echo "selected"; ?>>
                                    EPSON
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
          <label for="productsize" class="col-sm-3 control-label">Size</label>
          <div class="col-sm-9">
          <div class="radio">
              <label>
              <input name="size" type="radio" id="productcond" value="SMALL" <?php if(isset($_GET['edit'])) if($editrow['fld_product_size']=="SMALL") echo "checked"; ?> required> SMALL
            </label>
          </div>
          <div class="radio">
              <label>
                <input name="size" type="radio" id="productcond" value="MEDIUM" <?php if(isset($_GET['edit'])) if($editrow['fld_product_size']=="MEDIUM") echo "checked"; ?>> MEDIUM
            </label>
            </div>
            <div class="radio">
              <label>
                <input name="size" type="radio" id="productcond" value="BIG" <?php if(isset($_GET['edit'])) if($editrow['fld_product_size']=="BIG") echo "checked"; ?>> BIG
            </label>
            </div>
          </div>
      </div>

        <div class="form-group">
          <label for="productyear" class="col-sm-3 control-label">Warranty Period</label>
          <div class="col-sm-9">
          <select name="warranty" class="form-control" id="productyear" required>
            <option value="0" <?php if(isset($_GET['edit'])) if($editrow['fld_product_warranty']=="0") echo "selected"; ?>>0</option>
            <option value="1" <?php if(isset($_GET['edit'])) if($editrow['fld_product_warranty']=="1") echo "selected"; ?>>1</option>
            <option value="2" <?php if(isset($_GET['edit'])) if($editrow['fld_product_warranty']=="2") echo "selected"; ?>>2</option>
            <option value="3" <?php if(isset($_GET['edit'])) if($editrow['fld_product_warranty']=="3") echo "selected"; ?>>3</option>
            <option value="4" <?php if(isset($_GET['edit'])) if($editrow['fld_product_warranty']=="4") echo "selected"; ?>>4</option>
            <option value="5" <?php if(isset($_GET['edit'])) if($editrow['fld_product_warranty']=="5") echo "selected"; ?>>5</option>
            <option value="6" <?php if(isset($_GET['edit'])) if($editrow['fld_product_warranty']=="6") echo "selected"; ?>>6</option>
            <option value="10" <?php if(isset($_GET['edit'])) if($editrow['fld_product_warranty']=="10") echo "selected"; ?>>10</option>
          </select>
        </div>
        </div>  
        <div class="form-group">
          <label for="productq" class="col-sm-3 control-label">Quantity</label>
          <div class="col-sm-9">
          <input name="quantity" type="number" class="form-control" id="productq" placeholder="Product Quantity" value="<?php if(isset($_GET['edit'])) echo $editrow['fld_product_quantity']; ?>"  min="0" required>
        </div>
        </div>

                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-9">
                            <?php if (isset($_GET['edit'])) { ?>
                                <button class="btn btn-default" type="submit" name="update"><span
                                            class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Update
                                </button>
                            <?php } else { ?>
                                <button class="btn btn-default" type="submit" name="create"><span
                                            class="glyphicon glyphicon-plus" aria-hidden="true"></span> Create
                                </button>
                            <?php } ?>
                            <button class="btn btn-default" type="reset"><span class="glyphicon glyphicon-erase"
                                                                               aria-hidden="true"></span> Clear
                            </button>
                        </div>
                    </div>

                </div>

                <div class="col-md-4" style="height: 100%">
                    <div class="thumbnail dark-1">
                        <img src="products/<?php echo(isset($_GET['edit']) ? $editrow['fld_product_image'] : '') ?>"
                             onerror="this.onerror=null;this.src='products/no_image.png';" id="productPhoto"
                             alt="Product Image" style="width: 100%;height: 225px;">
                        <div class="caption text-center">
                            <h3 id="productImageTitle" style="word-break: break-all;">Product Image</h3>
                            <p>
                                <label class="btn btn-primary">
                                    <input type="file" accept="image/*" name="fileToUpload" id="inputImage"
                                           onchange="loadFile(event);"/>
                                    <span class="glyphicon glyphicon-cloud" aria-hidden="true"></span> Browse
                                </label>
                            </p>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<?php // } ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
            <div class="page-header">
                <h2>Products List</h2>
            </div>
            <table class="table table-bordered">
                <tr style="background: #1E2C4E;color: #fff;">
                    <th>Product ID</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Brand</th>
                    <th></th>
                </tr>
                <?php
                // Read
                $per_page = 5;
                if (isset($_GET["page"]))
                    $page = $_GET["page"];
                else
                    $page = 1;
                $start_from = ($page - 1) * $per_page;
                try {
                    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $stmt = $conn->prepare("select * from tbl_products_a194594_pt2 LIMIT {$start_from}, {$per_page}");
                    $stmt->execute();
                    $result = $stmt->fetchAll();
                } catch (PDOException $e) {
                    echo "Error: " . $e->getMessage();
                }
                foreach ($result as $readrow) {
                    ?>
                    <tr style="color: #AAA;">
                        <td><?php echo $readrow['fld_product_num']; ?></td>
                        <td><?php echo $readrow['fld_product_name']; ?></td>
                        <td>RM <?php echo $readrow['fld_product_price']; ?></td>
                        <td><?php echo $readrow['fld_product_brand']; ?></td>
                        <td class="text-center">
                            <a href="products_details.php?pid=<?php echo $readrow['fld_product_num']; ?>"
                               class="btn btn-warning btn-xs" role="button">Details</a>
                            <?php
                            if (isset($_SESSION['username']) && $_SESSION['username']['fld_staff_role'] === 'Admin') {
                                ?>
                                <a href="products.php?edit=<?php echo $readrow['fld_product_num'];
                                echo(isset($_GET['page']) ? '&page=' . $_GET['page'] : ''); ?>"
                                   class="btn btn-success btn-xs" role="button"> Edit </a>
                                <a href="products.php?delete=<?php echo $readrow['fld_product_num']; ?>"
                                   onclick="return confirm('Are you sure to delete?');" class="btn btn-danger btn-xs"
                                   role="button">Delete</a>
                                <?php
                            }
                            ?>
                        </td>
                    </tr>
                <?php } ?>

            </table>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
            <nav>
                <ul class="pagination">
                    <?php
                    try {
                        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        $stmt = $conn->prepare("SELECT * FROM tbl_products_a194594_pt2");
                        $stmt->execute();
                        $result = $stmt->fetchAll();
                        $total_records = count($result);
                    } catch (PDOException $e) {
                        echo "Error: " . $e->getMessage();
                    }
                    $total_pages = ceil($total_records / $per_page);
                    ?>
                    <?php if ($page == 1) { ?>
                        <li class="disabled"><span aria-hidden="true">&laquo;</span></li>
                    <?php } else { ?>
                        <li><a href="products.php?page=<?php echo $page - 1 ?>" aria-label="Previous"><span
                                        aria-hidden="true">&laquo;</span></a></li>
                        <?php
                    }
                    for ($i = 1;
                         $i <= $total_pages;
                         $i++)
                        if ($i == $page)
                            echo "<li class=\"active\"><a href=\"products.php?page=$i\">$i</a></li>";
                        else
                            echo "<li><a href=\"products.php?page=$i\">$i</a></li>";
                    ?>
                    <?php if ($page == $total_pages) { ?>
                        <li class="disabled"><span aria-hidden="true">&raquo;</span></li>
                    <?php } else { ?>
                        <li><a href="products.php?page=<?php echo $page + 1 ?>" aria-label="Previous"><span
                                        aria-hidden="true">&raquo;</span></a></li>
                    <?php } ?>
                </ul>
            </nav>
        </div>

    </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    <script type="application/javascript">
        var loadFile = function (event) {
            var reader = new FileReader();
            reader.onload = function () {
                var output = document.getElementById('productPhoto');
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
            document.getElementById('productImageTitle').innerText = event.target.files[0]['name'];
        };
    </script>
</body>
</html>
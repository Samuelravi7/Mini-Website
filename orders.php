<?php
  include_once 'orders_crud.php';
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ScanMaster : Orders</title>
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body>
  <?php include_once 'nav_bar.php'; ?>

  <div class="container-fluid">
    <div class="row">
      <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
        <div class="page-header">
          <h2>Create/Edit Order</h2>
        </div>
        <form action="orders.php" method="post" class="form-horizontal">
          <div class="form-group">
            <label for="orderid" class="col-sm-3 control-label">Order ID</label>
            <div class="col-sm-9">
              <input name="oid" type="text" class="form-control" id="orderid" placeholder="Order ID" value="<?php if(isset($_GET['edit'])) echo $editrow['fld_order_num']; ?>" readonly>
            </div>
          </div>
          <div class="form-group">
            <label for="orderdate" class="col-sm-3 control-label">Order Date</label>
            <div class="col-sm-9">
              <input name="orderdate" type="text" class="form-control" id="orderdate" placeholder="Order Date" value="<?php if(isset($_GET['edit'])) echo $editrow['fld_order_date']; ?>" readonly>
            </div>
          </div>
          <div class="form-group">
            <label for="staffid" class="col-sm-3 control-label">Staff</label>
            <div class="col-sm-9">
              <select name="sid" class="form-control" id="staffid">
                <?php
                try {
                  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                  $stmt = $conn->prepare("SELECT * FROM tbl_staffs_a194594_pt2");
                  $stmt->execute();
                  $result = $stmt->fetchAll();
                }
                catch(PDOException $e){
                      echo "Error: " . $e->getMessage();
                }
                foreach($result as $staffrow) {
                ?>
                  <option value="<?php echo $staffrow['fld_staff_num']; ?>" <?php if((isset($_GET['edit'])) && ($editrow['fld_staff_num']==$staffrow['fld_staff_num'])) echo 'selected'; ?>>
                    <?php echo $staffrow['fld_staff_fname']; ?>
                  </option>
                <?php } $conn = null; ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label for="customerid" class="col-sm-3 control-label">Customer</label>
            <div class="col-sm-9">
              <select name="cid" class="form-control" id="customerid">
                <?php
                try {
                  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                  $stmt = $conn->prepare("SELECT * FROM tbl_customers_a194594_pt2");
                  $stmt->execute();
                  $result = $stmt->fetchAll();
                }
                catch(PDOException $e){
                      echo "Error: " . $e->getMessage();
                }
                foreach($result as $custrow) {
                ?>
                  <option value="<?php echo $custrow['fld_customer_num']; ?>" <?php if((isset($_GET['edit'])) && ($editrow['fld_customer_num']==$custrow['fld_customer_num'])) echo 'selected'; ?>>
                    <?php echo $custrow['fld_customer_fname']; ?>
                  </option>
                <?php } $conn = null; ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-offset-3 col-sm-9">
              <?php if (isset($_GET['edit'])) { ?>
              <button type="submit" name="update" class="btn btn-default"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Update</button>
              <?php } else { ?>
              <button type="submit" name="create" class="btn btn-default"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Create</button>
              <?php } ?>
              <button type="reset" class="btn btn-default"><span class="glyphicon glyphicon-erase" aria-hidden="true"></span> Clear</button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <div class="row">
      <div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="page-header">
          <h2>Orders List</h2>
        </div>
        <table class="table table-striped table-bordered">
          <tr>
            <th>Order ID</th>
            <th>Order Date</th>
            <th>Staff</th>
            <th>Customer</th>
            <th></th>
          </tr>
          <?php
          try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "SELECT * FROM tbl_orders_a194594, tbl_staffs_a194594_pt2, tbl_customers_a194594_pt2 WHERE ";
            $sql .= "tbl_orders_a194594.fld_staff_num = tbl_staffs_a194594_pt2.fld_staff_num AND ";
            $sql .= "tbl_orders_a194594.fld_customer_num = tbl_customers_a194594_pt2.fld_customer_num";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll();
          }
          catch(PDOException $e){
                echo "Error: " . $e->getMessage();
          }
          foreach($result as $orderrow) {
          ?>
          <tr>
            <td><?php echo $orderrow['fld_order_num']; ?></td>
            <td><?php echo $orderrow['fld_order_date']; ?></td>
            <td><?php echo $orderrow['fld_staff_fname']; ?></td>
            <td><?php echo $orderrow['fld_customer_fname']; ?></td>
            <td>
              <a href="orders_details.php?oid=<?php echo $orderrow['fld_order_num']; ?>" class="btn btn-info btn-xs" role="button">Details</a>
              <a href="orders.php?edit=<?php echo $orderrow['fld_order_num']; ?>" class="btn btn-success btn-xs" role="button">Edit</a>
              <a href="orders.php?delete=<?php echo $orderrow['fld_order_num']; ?>" onclick="return confirm('Are you sure to delete?');" class="btn btn-danger btn-xs" role="button">Delete</a>
            </td>
          </tr>
          <?php } ?>
        </table>
      </div>
    </div>
  </div>

  <!-- jQuery -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <!-- Bootstrap -->
  <script src="js/bootstrap.min.js"></script>
</body>
</html>

<?php

include_once 'database.php';

$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Create
if (isset($_POST['create'])) {
    if (isset($_SESSION['username']) && strtolower($_SESSION['username']['fld_staff_role'] === 'Admin')) {
    try {
        $stmt = $conn->prepare("INSERT INTO tbl_staffs_a194594_pt2(
            fld_staff_num, fld_staff_fname, fld_staff_gender, 
            fld_staff_phone, fld_staff_email, fld_staff_password, fld_staff_role
        ) VALUES(:sid, :fname, :gender, :phone, :email, :password, :role)");

        $stmt->bindParam(':sid', $sid, PDO::PARAM_STR);
        $stmt->bindParam(':fname', $fname, PDO::PARAM_STR);
        $stmt->bindParam(':gender', $gender, PDO::PARAM_STR);
        $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);
        $stmt->bindParam(':role', $role, PDO::PARAM_STR);

        $sid = $_POST['sid'];
        $fname = $_POST['fname'];
        $gender = $_POST['gender'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $role = $_POST['role'];

        $stmt->execute();
    } catch (PDOException $e) {
            $_SESSION['error'] = "Error while creating: " . $e->getMessage();
        }
    } else {
        $_SESSION['error'] = "Sorry, but you don't have permission to create a new staff.";
    }

    header("LOCATION: {$_SERVER['REQUEST_URI']}");
    exit();
  }

// Update
if (isset($_POST['update'])) {
  if (isset($_SESSION['username']) && strtolower($_SESSION['username']['fld_staff_role'] === 'Admin')) {
        try {
        $stmt = $conn->prepare("UPDATE tbl_staffs_a194594_pt2 SET
            fld_staff_num = :sid, fld_staff_fname = :fname, fld_staff_gender = :gender,
            fld_staff_phone = :phone, fld_staff_email = :email, fld_staff_password = :pass, 
            fld_staff_role = :role WHERE fld_staff_num = :oldsid");

        $stmt->bindParam(':sid', $sid, PDO::PARAM_STR);
        $stmt->bindParam(':fname', $fname, PDO::PARAM_STR);
        $stmt->bindParam(':gender', $gender, PDO::PARAM_STR);
        $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
         $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
        $stmt->bindParam(':role', $role, PDO::PARAM_STR);
        $stmt->bindParam(':oldsid', $oldsid, PDO::PARAM_STR);

        $sid = $_POST['sid'];
        $fname = $_POST['fname'];
        $gender = $_POST['gender'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $pass = $_POST['password'];
        $role = $_POST['role'];
        $oldsid = $_POST['oldsid'];

            $stmt->execute();
        } catch (PDOException $e) {
            $_SESSION['error'] = "Error while updating: " . $e->getMessage();
            header("LOCATION: {$_SERVER['REQUEST_URI']}");
            exit();
        }
    } else {
        $_SESSION['error'] = "Sorry, but you don't have permission to update staff.";
    }

    header("LOCATION: {$_SERVER['PHP_SELF']}");
    exit();
}

// Delete
if (isset($_GET['delete'])) {
   if (isset($_SESSION['username']) && strtolower($_SESSION['username']['fld_staff_role'] === 'Admin')) {
    try {
        $stmt = $conn->prepare("DELETE FROM tbl_staffs_a194594_pt2 WHERE fld_staff_num = :sid");
        $stmt->bindParam(':sid', $sid, PDO::PARAM_STR);

        $sid = $_GET['delete'];

        $stmt->execute();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        $_SESSION['error'] = "Sorry, but you don't have permission to delete staff.";
    }

    header("LOCATION: {$_SERVER['PHP_SELF']}");
    exit();
}

// Edit
if (isset($_GET['edit'])) {
  if (isset($_SESSION['username']) && strtolower($_SESSION['username']['fld_staff_role'] === 'Admin')) {
    try {
        $stmt = $conn->prepare("SELECT * FROM tbl_staffs_a194594_pt2 WHERE fld_staff_num = :sid");
        $stmt->bindParam(':sid', $sid, PDO::PARAM_STR);

        $sid = $_GET['edit'];
        $fid = sprintf("S%03d", $sid);

        $stmt->execute();

        $editrow = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error: " . $e->getMessage();
            header("LOCATION: {$_SERVER['PHP_SELF']}");
            exit();
        }
    } else {
        $_SESSION['error'] = "Sorry, but you don't have permission to edit a staff.";
        header("LOCATION: {$_SERVER['PHP_SELF']}");
        exit();
    }
}


// GET NEXT ID
$staff = $conn->query("SHOW TABLE STATUS LIKE 'tbl_staffs_a194594_pt2'")->fetch();
$NextID = sprintf("S%03d", $staff['Auto_increment']);

$conn = null;

?>

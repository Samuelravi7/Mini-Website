<?php
include_once 'database.php';

if (!isset($_SESSION['loggedin']))
    header("LOCATION: login.php");

function uploadPhoto($file, $id)
{
    if (!isset($file) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return 4; // No file was uploaded
    }

    $target_dir = "products/";
    $target_file = $target_dir . basename($file["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $allowedExt = ['png', 'gif'];

    $newfilename = "{$id}.{$imageFileType}";
    /*
     * 0 = image file is a fake image
     * 1 = file is too large.
     * 2 = PNG & GIF files are allowed
     * 3 = Server error
     * 4 = No file were uploaded
     */

   // Check if file is a valid image
    $imageInfo = getimagesize($file['tmp_name']);
    if ($imageInfo === false)
        return 0;

    // Check file size (limit to 10MB)
    if ($file["size"] > 10000000)
        return 1;

    // Check valid file format
    if (!in_array($imageFileType, $allowedExt))
        return 2;

    // Check and resize image dimensions
    list($width, $height) = $imageInfo;
    if ($width > 300 || $height > 400) {
        // Resize the image
        $resizedImage = resizeImage($file['tmp_name'], 300, 400, $imageInfo);
        if (!$resizedImage) return 3;

        // Save the resized image as a .jpg file
        if (!imagejpeg($resizedImage, $target_dir . $newfilename)) return 3;

        imagedestroy($resizedImage);
    } else {
        // Move the uploaded file as is
        if (!move_uploaded_file($file["tmp_name"], $target_dir . $newfilename))
            return 3;
    }

    return array('status' => 200, 'name' => $newfilename, 'ext' => 'jpg');
}
// Function to resize the image while maintaining aspect ratio
function resizeImage($filePath, $maxWidth, $maxHeight, $imageInfo)
{
    $srcWidth = $imageInfo[0];
    $srcHeight = $imageInfo[1];
    $srcType = $imageInfo[2];

    switch ($srcType) {
        case IMAGETYPE_JPEG:
            $srcImage = imagecreatefromjpeg($filePath);
            break;
        case IMAGETYPE_PNG:
            $srcImage = imagecreatefrompng($filePath);
            break;
        case IMAGETYPE_GIF:
            $srcImage = imagecreatefromgif($filePath);
            break;
        default:
            return false; // Unsupported image type
    }

    $aspectRatio = min($maxWidth / $srcWidth, $maxHeight / $srcHeight);
    $newWidth = round($srcWidth * $aspectRatio);
    $newHeight = round($srcHeight * $aspectRatio);

    $dstImage = imagecreatetruecolor($newWidth, $newHeight);
    imagecopyresampled($dstImage, $srcImage, 0, 0, 0, 0, $newWidth, $newHeight, $srcWidth, $srcHeight);
    imagedestroy($srcImage);

    return $dstImage;
}

// JavaScript Dialog Box for Errors
function showError($error)
{
    echo "<script>alert('$error');</script>";
}

//Create
if (isset($_POST['create'])) {
    if (isset($_SESSION['username']) && $_SESSION['username']['fld_staff_role'] === 'Admin') {
        $uploadStatus = isset($_FILES['fileToUpload']) ? uploadPhoto($_FILES['fileToUpload'], $_POST['pid']) : 4;


        if (isset($uploadStatus['status'])) {
            try {
                $stmt = $db->prepare("INSERT INTO tbl_products_a194594_pt2(fld_product_name, fld_product_price, fld_product_brand, fld_product_size, fld_product_warranty, fld_product_quantity, fld_product_image)
               VALUES (:name, :price, :brand, :size, :warranty, :quantity, :image)");

                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                $stmt->bindParam(':price', $price, PDO::PARAM_STR);
                $stmt->bindParam(':brand', $brand, PDO::PARAM_STR);
                $stmt->bindParam(':size', $size, PDO::PARAM_STR);
                $stmt->bindParam(':warranty', $warranty, PDO::PARAM_INT);
                $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
                $stmt->bindParam(':image', $uploadStatus['name']);

                $name = $_POST['name'];
                $price = $_POST['price'];
                $brand = $_POST['brand'];
                $size = $_POST['size'];
                $warranty = $_POST['warranty'];
                $quantity = $_POST['quantity'];

                $stmt->execute();

                clearstatcache();
            } catch (PDOException $e) {
                $_SESSION['error'] = "Error while creating: " . $e->getMessage();
                showError($_SESSION['error']);
            }
        } else {
            switch ($uploadStatus) {
                case 0:
                    showError("Please make sure the file uploaded is an image.");
                    break;
                case 1:
                    showError("Sorry, only files below 10MB are allowed.");
                    break;
                case 2:
                    showError("Sorry, only valid image formats are allowed.");
                    break;
                case 3:
                    showError("Sorry, there was an error uploading your file.");
                    break;
                case 4:
                    showError("Please upload an image.");
                    break;
                case 5:
                    showError("Image dimensions exceed the limit (300x400).");
                    break;
                default:
                    showError("An unknown error has occurred.");
                    break;
            }
        }
    } else {
        showError("Sorry, but you don't have permission to create a new product.");
    }

    header("LOCATION: {$_SERVER['REQUEST_URI']}");
    exit();
}

//Update
if (isset($_POST['update'])) {
    if (isset($_SESSION['username']) && $_SESSION['username']['fld_staff_role'] === 'Admin') {
        try {
            $stmt = $db->prepare("UPDATE tbl_products_a194594_pt2 SET
          fld_product_name = :name, fld_product_price = :price, fld_product_brand = :brand,
          fld_product_size = :size, fld_product_warranty = :warranty, fld_product_quantity = :quantity
          WHERE fld_product_num = :pid LIMIT 1");

            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':price', $price, PDO::PARAM_STR);
            $stmt->bindParam(':brand', $brand, PDO::PARAM_STR);
            $stmt->bindParam(':size', $size, PDO::PARAM_STR);
            $stmt->bindParam(':warranty', $warranty, PDO::PARAM_INT);
            $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
            $stmt->bindParam(':pid', $pid);

            $name = $_POST['name'];
            $price = $_POST['price'];
            $brand = $_POST['brand'];
            $size = $_POST['size'];
            $warranty = $_POST['warranty'];
            $quantity = $_POST['quantity'];
            $pid = $_POST['pid'];

            $stmt->execute();

            // Image Upload
            $flag = uploadPhoto($_FILES['fileToUpload'], $pid);
            if (isset($flag['status'])) {
                $stmt = $db->prepare("UPDATE tbl_products_a194594_pt2 SET fld_product_image = :image WHERE fld_product_num = :pid LIMIT 1");

                $stmt->bindParam(':image', $flag['name']);
                $stmt->bindParam(':pid', $pid);
                $stmt->execute();

                clearstatcache();

                // Rename file after upload (IF NEEDED)
                // rename("products/{$uploadStatus['name']}", "products/{$oldpid}.{$uploadStatus['ext']}");
            } elseif ($flag != 4) {
                if ($flag == 0)
                    $_SESSION['error'] = "Please make sure the file uploaded is an image.";
                elseif ($flag == 1)
                    $_SESSION['error'] = "Sorry, only file with below 10MB are allowed.";
                elseif ($flag == 2)
                    $_SESSION['error'] = "Sorry, only PNG & GIF files are allowed.";
                elseif ($flag == 3)
                    $_SESSION['error'] = "Sorry, there was an error uploading your file.";
                else
                    $_SESSION['error'] = "An unknown error has been occurred.";
            }
        } catch (PDOException $e) {
            $_SESSION['error'] = "Error while updating: " . $e->getMessage();
        } catch (Exception $e) {
            $_SESSION['error'] = "Error while updating: " . $e->getMessage();
        }
    } else {
        $_SESSION['error'] = "Sorry, but you don't have permission to update this product.";
        header("LOCATION: {$_SERVER['PHP_SELF']}");
        exit();
    }

    if (isset($_SESSION['error']))
        header("LOCATION: {$_SERVER['REQUEST_URI']}");
    else
        header("Location: {$_SERVER['PHP_SELF']}");

    exit();
}

// Hanya update bila tiada error. (IF NEEDED)
/*
if (isset($_POST['update'])) {
    try {
        // Image Upload
        $flag = uploadPhoto($_FILES['fileToUpload'], false);
        if (isset($flag['status']) || $flag == 4) {
            $sql = "UPDATE tbl_products_a174652_pt2 SET
                                    FLD_PRODUCT_NAME = :name, FLD_PRICE = :price, FLD_BRAND = :brand,
                                    FLD_SOCKET = :socket, FLD_MANUFACTURED_YEAR = :year, FLD_STOCK = :stock";

            if (isset($flag['status']))
                $sql .= ", FLD_PRODUCT_IMAGE = :image ";

            $sql .= "WHERE FLD_PRODUCT_ID = :oldpid LIMIT 1";

            $stmt = $db->prepare($sql);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':price', $price, PDO::PARAM_STR);
            $stmt->bindParam(':brand', $brand, PDO::PARAM_STR);
            $stmt->bindParam(':socket', $socket, PDO::PARAM_STR);
            $stmt->bindParam(':year', $year, PDO::PARAM_INT);
            $stmt->bindParam(':stock', $stock, PDO::PARAM_INT);
            $stmt->bindParam(':oldpid', $oldpid);

            $name = $_POST['name'];
            $price = $_POST['price'];
            $brand = $_POST['brand'];
            $socket = $_POST['socket'];
            $year = $_POST['year'];
            $stock = $_POST['stock'];
            $oldpid = $_POST['oldpid'];

            if (isset($flag['status']))
                $stmt->bindParam(':image', $flag['name']);

            $stmt->execute();
        } else {
            if ($flag == 0)
                $_SESSION['error'] = "Please make sure the file uploaded is an image.";
            elseif ($flag == 1)
                $_SESSION['error'] = "Sorry, only file with below 10MB are allowed.";
            elseif ($flag == 2)
                $_SESSION['error'] = "Sorry, only PNG & GIF files are allowed.";
            elseif ($flag == 3)
                $_SESSION['error'] = "Sorry, there was an error uploading your file.";
            else
                $_SESSION['error'] = "An unknown error has been occurred.";
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = $e->getMessage();
    }

    if (isset($_SESSION['error']))
        header("LOCATION: {$_SERVER['REQUEST_URI']}");
    else
        header("Location: products.php");

    exit();
}
*/

//Delete
if (isset($_GET['delete'])) {
    if (isset($_SESSION['username']) && $_SESSION['username']['fld_staff_role'] === 'Admin') {
        try {
            $pid = $_GET['delete'];

            // Select Product Image Name
            $query = $db->query("SELECT fld_product_image FROM tbl_products_a194594_pt2 WHERE fld_product_num = {$pid} LIMIT 1")->fetch(PDO::FETCH_ASSOC);

            // Check if selected product id exists .
            if (isset($query['fld_product_image'])) {
                // Delete Query
                $stmt = $db->prepare("DELETE FROM tbl_products_a194594_pt2 WHERE fld_product_num = :pid");
                $stmt->bindParam(':pid', $pid);

                $stmt->execute();

                // Delete Image
                unlink("products/{$query['fld_product_image']}");
            }

        } catch (PDOException $e) {
            $_SESSION['error'] = "Error while deleting: " . $e->getMessage();
        }
    } else {
        $_SESSION['error'] = "Sorry, but you don't have permission to delete this product.";
    }

    header("LOCATION: {$_SERVER['PHP_SELF']}");
    exit();
}

//Edit
if (isset($_GET['edit'])) {
    if (isset($_SESSION['username']) && $_SESSION['username']['fld_staff_role'] === 'Admin') {
        try {
            $stmt = $db->prepare("SELECT * FROM tbl_products_a194594_pt2 WHERE fld_product_num = :pid");
            $stmt->bindParam(':pid', $pid, PDO::PARAM_STR);
            $pid = $_GET['edit'];

            $stmt->execute();

            $editrow = $stmt->fetch(PDO::FETCH_ASSOC);
            $fID = sprintf("MB%03d", $editrow['fld_product_num']);

            if (empty($editrow['fld_product_image']))
                $editrow['fld_product_image'] = $editrow['fld_product_num'] . '.png';
        } catch (PDOException $e) {
            $_SESSION['error'] = "Error: " . $e->getMessage();
        }
    } else {
        $_SESSION['error'] = "Sorry, but you don't have permission to edit a product.";
    }

    if (isset($_SESSION['error'])) {
        header("LOCATION: {$_SERVER['PHP_SELF']}");
        exit();
    }
}

// GET NEXT ID
$product = $db->query("SHOW TABLE STATUS LIKE 'tbl_products_a194594_pt2'")->fetch();
$NextID = sprintf("MB%03d", $product['Auto_increment']);

?>

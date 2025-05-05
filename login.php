<?php
require_once 'database.php';

if (isset($_SESSION['loggedin']))
    header("LOCATION: index.php");

if (isset($_POST['username'], $_POST['password'])) {
    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $_SESSION['error'] = 'Please fill in the blanks.';
    } else {
        $stmt = $db->prepare("SELECT * FROM tbl_staffs_a194594_pt2 WHERE (fld_staff_num = :id OR fld_staff_email = :id) LIMIT 1");
        $stmt->bindParam(':id', $username);

        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (isset($user['fld_staff_num'])) {
            if ($user['fld_staff_password'] == $password) {
                unset($user['fld_staff_password']);
                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = $user;

                header("LOCATION: index.php");
                exit();
            } else {
                $_SESSION['error'] = 'Invalid login credentials. Please try again.';
            }
        } else {
            $_SESSION['error'] = 'Account does not exist.';
        }
    }

    header("LOCATION: " . $_SERVER['REQUEST_URI']);
    exit();
}
?>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>ScanMaster : Login</title>
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/login.css" rel="stylesheet">

    <link rel="shortcut icon" type="image/jpg" href="favicon.ico"/>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
<div class="container login-wrapper">
    <div class="panel panel-default" style="width: 100%">
        <div class="panel-body">
            <div class="text-center">
                <h2 class="text-center">LOGIN</h2>
                Sign in to your account
            </div>
            <hr/>

            <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST">
                <div class="form-group">
                    <label for="inputUserID">Email address / User ID</label>
                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1"><i class="glyphicon glyphicon-user" aria-hidden="true"></i></span>
                        <input type="text" class="form-control" id="inputUserID" name="username"
                               placeholder="Email address / User ID">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputUserPass">Password</label>
                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1"><i class="glyphicon glyphicon-lock" aria-hidden="true"></i></span>
                        <input type="password" class="form-control" id="inputUserPass" name="password"
                               placeholder="Password">
                    </div>
                </div>

                <?php
                if (isset($_SESSION['error'])) {
                    echo "<p class='text-danger text-center'>{$_SESSION['error']}</p>";
                    unset($_SESSION['error']);
                }
                ?>
                <button type="submit" class="btn btn-primary btn-block" style="border-radius: 20px;">Login</button>
            </form>

            <hr/>

        </div>
    </div>


</div>


<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>
</body>
</html>
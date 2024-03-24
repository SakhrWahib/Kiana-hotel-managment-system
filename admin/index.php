<?php
session_start();
$pageTitle = 'Login';
if (isset($_SESSION['username'])) {
    header('Location: dashboard.php');
}
include 'init.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = check_input($_POST['username']);
    $password = check_input($_POST['password']);
    $hashedPass = sha1($password);
    $stmt = $con->prepare("SELECT ad_no, username, password,role FROM admins WHERE username = ? AND password = ? LIMIT 1");
    $stmt->execute(array($username, $hashedPass));
    $row = $stmt->fetch();
    $count = $stmt->rowCount();
    if ($count > 0) {
        $_SESSION['username'] = $username;
        $_SESSION['ad_no'] = $row['ad_no'];
        $_SESSION['role'] = $row['role'];
        header('Location: dashboard.php');
        exit();
    }
}
?>

<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-center auth">
                <div class="row flex-grow">
                    <div class="col-lg-4 mx-auto">
                        <div class="auth-form-light text-left p-5">
                            <div class="brand-logo">
                                <img src=" layout/images/logo.svg" style="width: 180px;">
                            </div>
                            <h4>Hello! let's get started</h4>
                            <h6 class="font-weight-light">Sign in to continue.</h6>
                            <form class="pt-3" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                                <div class="form-group">
                                    <input type="text" name="username" class="form-control form-control-lg" placeholder="Username" autocomplete="off">
                                </div>
                                <div class="form-group">
                                    <input type="password" name="password" class="form-control form-control-lg" placeholder="Password" autocomplete="new-password">
                                </div>
                                <div class="mt-3">
                                    <button type="submit" name="signin" class="btn btn-gradient-primary me-2">Sign In</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include $tpl . 'footer.php'; ?>
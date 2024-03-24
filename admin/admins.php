<?php
ob_start();
session_start();
if (isset($_SESSION['username']) && $_SESSION['role'] == 'admin') {
    $pageTitle = 'Admins';
    include 'init.php';
?>
    <div class="container-scroller">
        <?php include 'includes/templates/navbar.php' ?>
        <div class="container-fluid page-body-wrapper">
            <?php include 'includes/templates/sidebar.php' ?>
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="page-header">
                        <h3 class="page-title">
                            <span class="page-title-icon bg-gradient-primary text-white me-2">
                                <i class="mdi mdi-account-key"></i>
                            </span> Admins
                        </h3>
                        <nav aria-label="breadcrumb">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item active" aria-current="page">
                                    <span></span>Overview <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    <?php
                    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
                    if ($do == 'Manage') {
                        $st = $con->prepare("select * from admins");
                        $st->execute();
                        $ads = $st->fetchAll();
                        if (!empty($ads)) {
                    ?>
                            <div class="card">
                                <div class="card-body">
                                    <?php if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'subadmin' || $_SESSION['role'] == 'subadminadd') { ?>
                                        <form class="forms-sample" action="<?php $_POST['add_new'] ?>" method="post">
                                            <button type="submit" class="btn btn-gradient-primary me-2" name="add_new">Add New admin</button>
                                        </form>
                                    <?php } ?>
                                </div>
                                <div class="card-body">
                                    <div id="printout">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th> ID </th>
                                                    <th> Name </th>
                                                    <th>Role</th>
                                                    <th> Control</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                foreach ($ads as $ad) {
                                                    echo '<tr>';
                                                    echo '<td class="py-1">' . $ad['ad_no'] . '</td>';
                                                    echo '<td>';
                                                    $allemps = getAllFrom("*", "employees", "", "", "emp_no");
                                                    foreach ($allemps as $emp) {
                                                        if (
                                                            $ad['emp_no'] == $emp['emp_no']
                                                        )
                                                            echo $emp['emp_name'];
                                                        else echo NULL;
                                                    }
                                                    echo "</td>";
                                                    echo '<td>' . $ad['role'] . '</td>';
                                                    echo "<td> ";
                                                ?>
                                                    <?php if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'subadmin' || $_SESSION['role'] == 'subadminshow') { ?>
                                                        <form action="<?php $_POST['show'] ?>" method="post">
                                                            <input type="hidden" name="id" value="<?php echo $ad['ad_no']; ?>">
                                                            <button type="submit" class="btn btn-gradient-info btn-rounded btn-icon" name="show">
                                                                <i class="mdi mdi-eye"></i>
                                                            </button>
                                                        </form>
                                                    <?php } ?>
                                                    <?php if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'subadmin' || $_SESSION['role'] == 'subadminedit') { ?>
                                                        <form action="<?php $_POST['edit'] ?>" method="post">
                                                            <input type="hidden" name="id" value="<?php echo $ad['ad_no']; ?>">
                                                            <button type="submit" class="btn btn-gradient-success btn-rounded btn-icon" name="edit">
                                                                <i class="mdi mdi-lead-pencil"></i>
                                                            </button>
                                                        </form>
                                                    <?php } ?>
                                                    <?php if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'subadmin' || $_SESSION['role'] == 'subadmindelete') { ?>
                                                        <form action="<?php $_POST['delete'] ?>" method="post">
                                                            <input type="hidden" name="id" value="<?php echo $ad['ad_no']; ?>">
                                                            <button type="submit" class="btn btn-gradient-danger btn-rounded btn-icon" name="delete">
                                                                <i class="mdi mdi-delete"></i>
                                                            </button>
                                                        </form>
                                                    <?php } ?>
                                                <?php
                                                    echo "</td>";
                                                    echo '</tr>';
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                        <table class="table table-dark">
                                            <tbody>
                                                <tr>
                                                    <td>Count Elements</td>
                                                    <td><?php echo countItems('ad_no', 'admins') ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <button class="btn btn-gradient-primary me-2" onclick="printContent();">Print Report</button>
                                </div>
                            </div>
                            <?php
                            if (isset($_POST['show'])) {
                                header("location:admins.php?do=Show&ID=" . check_input($_POST['id']) . "");
                            } else if (isset($_POST['edit'])) {
                                header("location:admins.php?do=Edit&ID=" . check_input($_POST['id']) . "");
                            } else if (isset($_POST['delete'])) {
                                header("location:admins.php?do=Delete&ID=" . check_input($_POST['id']) . "");
                            } else if (isset($_POST['add_new'])) {
                                header("location:admins.php?do=Add");
                            }
                        } else { ?>
                            <div class="card">
                                <div class="card-body">
                                    <p class="blockquote text-light bg-gradient-info"> Sorry ! There is No admins Please Add First admins </p>
                                    <form class="forms-sample" action="<?php $_POST['add_first'] ?>" method="post">
                                        <button type="submit" class="btn btn-gradient-primary me-2" name="add_first">Add First admins</button>
                                    </form>
                                </div>
                            </div>
                            <?php
                            if (isset($_POST['add_first'])) {
                                header("location:admins.php?do=Add");
                            }
                        }
                    } else if ($do == 'Show') {
                        if (isset($_GET['ID'])) $getid = check_input($_GET['ID']);
                        else if (isset($_POST['ID'])) $getid = check_input($_POST['ID']);
                        else header("location: admins.php");
                        $st = $con->prepare("select * from admins where ad_no= ?");
                        $st->execute(array($getid));
                        $ad = $st->fetch();
                        $count = $st->rowCount();
                        if ($count > 0) {
                            ?>
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Show Details</h4>
                                    <div id="printout">
                                        <form class="forms-sample" method="post">
                                            <div class="form-group">
                                                <label for="exampleInputUsername1">ID</label>
                                                <input type="text" name="ad_no" class="form-control" id="exampleInputUsername1" value="<?php echo $ad['ad_no'] ?>" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputUsername1">Name</label>
                                                <input type="text" name="emp_no" class="form-control" id="exampleInputUsername1" value="<?php
                                                                                                                                        $allemps = getAllFrom("*", "employees", "", "", "emp_no");
                                                                                                                                        foreach ($allemps as $emp) {
                                                                                                                                            if ($ad['emp_no'] == $emp['emp_no'])
                                                                                                                                                echo $emp['emp_name'];
                                                                                                                                            else echo NULL;
                                                                                                                                        } ?>" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputUsername1">Username</label>
                                                <input type="text" name="username" class="form-control" id="exampleInputUsername1" value="<?php echo $ad['username'] ?>" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputUsername1">Role</label>
                                                <input type="text" name="role" class="form-control" id="exampleInputUsername1" value="<?php echo $ad['role'] ?>" readonly>
                                            </div>
                                            <a href="admins.php" class="btn btn-light">Cancel</a>
                                        </form>
                                    </div>
                                    <button class="btn btn-gradient-primary me-2" onclick="printContent();">Print Report</button>
                                </div>
                            </div>
                        <?php
                        }
                    } else if ($do == 'Add') {
                        ?>
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Add</h4>
                                <form class="forms-sample" action="<?php $_POST['add_ad'] ?>" method="post">
                                    <div class="form-group">
                                        <label for="exampleSelectGender">Name</label>
                                        <select name="emp_no" class="form-control" id="exampleSelectGender">
                                            <option value="0">...</option>
                                            <?php
                                            $allemps = getAllFrom("*", "employees", "", "", "emp_no");
                                            foreach ($allemps as $emp) {
                                                echo "<option value='" . $emp['emp_no'] . "'>" . $emp['emp_name'] . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputUsername1">Username</label>
                                        <input type="text" name="username" class="form-control" id="exampleInputUsername1" placeholder="Admins Username" autocomplete="off">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputUsername1">Password</label>
                                        <input type="password" name="password" class="form-control" id="exampleInputUsername1" placeholder="Admins Password" autocomplete="new-password">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputUsername1">Confirm Password</label>
                                        <input type="password" name="passwordcon" class="form-control" id="exampleInputUsername1" placeholder="Admins Confirm Password" autocomplete="new-password">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleSelectGender">Role</label>
                                        <select name="role" class="form-control" id="exampleSelectGender">
                                            <?php
                                            foreach ($Roles['Role'] as $rol) {
                                                echo "<option value='" . $rol . "'>" . $rol . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-gradient-primary me-2" name="add_ad">Add</button>
                                    <a href="admins.php" class="btn btn-light">Cancel</a>
                                </form>
                                <?php
                                if (isset($_POST['add_ad'])) {
                                    $emp_no = check_input($_POST['emp_no']);
                                    $username = check_input($_POST['username']);
                                    $password = check_input($_POST['password']);
                                    $passwordcon = check_input($_POST['passwordcon']);
                                    $role = check_input($_POST['role']);
                                    $formErrors = array();
                                    if (empty($username)) {
                                        $formErrors[] = "The Username Must be <strong> not Empty </strong>";
                                    }
                                    if (empty($password) || empty($passwordcon)) {
                                        $formErrors[] = "The Password Must be <strong> not Empty </strong>";
                                    }
                                    if (!preg_match("/^[a-zA-Z].{7,}$/", $password) || !preg_match("/^[a-zA-Z].{7,}$/", $passwordcon)) {
                                        $formErrors[] = "Must start with char and at least 8 <strong> letters-symbols-numbers </strong>";
                                    }
                                    if ($password != $passwordcon) {
                                        $formErrors[] = "Password <strong> Is Not </strong> The same";
                                    } else
                                        $pass = sha1($password);
                                    foreach ($formErrors as $error) {
                                        echo '<p class="blockquote text-light bg-gradient-danger">' . $error . '</p>';
                                    }
                                    $check = checkItem("username", "admins", $username);
                                    if ($check == 1) {
                                        $formErrors[] = 'Sorry This Username is<strong>Exist</strong>';
                                    }
                                    foreach ($formErrors as $error) {
                                        echo '<p class="blockquote text-light bg-gradient-danger">' . $error . '</p>';
                                    }
                                    if (empty($formErrors)) {
                                        $st = $con->prepare("insert into admins (username,password,role,emp_no) values(:name,:pass,:role,:id)");
                                        if ($emp_no == 0) $emp_no = NULL;
                                        $st->execute(array(
                                            'name' => $username,
                                            'pass' => $pass,
                                            'role' => $role,
                                            'id' => $emp_no
                                        ));
                                        echo '<p class="blockquote text-light bg-gradient-success">' . $st->rowCount() . ' Recored Added Successfully</p>';
                                        header("refresh:1;url=admins.php");
                                    }
                                } ?>
                            </div>
                        </div>
                        <?php
                    } else if ($do == 'Edit') {
                        if (isset($_GET['ID'])) $getid = check_input($_GET['ID']);
                        else if (isset($_POST['ID'])) $getid = check_input($_POST['ID']);
                        else header("location: admins.php");
                        $st = $con->prepare("select * from admins where ad_no= ?");
                        $st->execute(array($getid));
                        $ad = $st->fetch();
                        $count = $st->rowCount();
                        if ($count > 0) {
                        ?>
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Edit</h4>
                                    <form class="forms-sample" action="<?php $_POST['edit_ad'] ?>" method="post">
                                        <input type="hidden" name="ad_no" value="<?php echo $ad['ad_no']; ?>">
                                        <div class="form-group">
                                            <label for="exampleSelectGender">Name</label>
                                            <select name="emp_no" class="form-control" id="exampleSelectGender">
                                                <option value="0">...</option>
                                                <?php
                                                $allemps = getAllFrom("*", "employees", "", "", "emp_no");
                                                foreach ($allemps as $emp) {
                                                    echo "<option value='" . $emp['emp_no'] . "'";
                                                    if ($ad['emp_no'] == $emp['emp_no']) {
                                                        echo 'selected';
                                                    }
                                                    echo ">" . $emp['emp_name'] . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputUsername1">Username</label>
                                            <input type="hidden" name="oldname" value="<?php echo $ad['username']; ?>">
                                            <input type="text" name="username" class="form-control" id="exampleInputUsername1" value="<?php echo $ad['username']; ?>" placeholder="Admins Username" autocomplete="off">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputUsername1">Password</label>
                                            <input type="hidden" name="oldpass" value="<?php echo $ad['password']; ?>">
                                            <input type="password" name="password" class="form-control" id="exampleInputUsername1" placeholder="leave It To No Change" autocomplete="new-password">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleSelectGender">Role</label>
                                            <select name="role" class="form-control" id="exampleSelectGender">
                                                <?php
                                                foreach ($Roles['Role'] as $rol) {
                                                    echo "<option value='" . $rol . "'";
                                                    if ($ad['role'] == $rol) {
                                                        echo 'selected';
                                                    }
                                                    echo ">" . $rol . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-gradient-primary me-2" name="edit_ad">Edit</button>
                                        <a href="admins.php" class="btn btn-light">Cancel</a>
                                    </form>
                                    <?php
                                    if (isset($_POST['edit_ad'])) {
                                        $ad_no = check_input($_POST['ad_no']);
                                        $emp_no = check_input($_POST['emp_no']);
                                        $username = check_input($_POST['username']);
                                        $oldname = check_input($_POST['oldname']);
                                        $password = check_input($_POST['password']);
                                        $oldpass = check_input($_POST['oldpass']);
                                        $role = check_input($_POST['role']);
                                        $formErrors = array();
                                        if (empty($username)) {
                                            $formErrors[] = "The Username Must be <strong> not Empty </strong>";
                                        }
                                        if (empty($password)) {
                                            $pass = $oldpass;
                                        } else {
                                            if (!preg_match("/^[a-zA-Z].{7,}$/", $password)) {
                                                $formErrors[] = "Must start with char and at least 8 <strong> letters-symbols-numbers </strong>";
                                            } else $pass = sha1($password);
                                        }
                                        foreach ($formErrors as $error) {
                                            echo '<p class="blockquote text-light bg-gradient-danger">' . $error . '</p>';
                                        }
                                        if ($oldname == $username) {
                                            $name = $oldname;
                                        } else {
                                            $name = $username;
                                            $check = checkItem("username", "admins", $name);
                                            if ($check == 1) {
                                                $formErrors[] = 'Sorry This Username is<strong>Exist</strong>';
                                            }
                                        }
                                        foreach ($formErrors as $error) {
                                            echo '<p class="blockquote text-light bg-gradient-danger">' . $error . '</p>';
                                        }
                                        if (empty($formErrors)) {
                                            $st = $con->prepare("update admins set username=?,password=?,role=?,emp_no=? where ad_no=?");
                                            if ($emp_no == 0) $emp_no = NULL;
                                            $st->execute(array($name, $pass, $role, $emp_no, $ad_no));
                                            echo '<p class="blockquote text-light bg-gradient-success">' . $st->rowCount() . ' Recored Updated Successfully</p>';
                                            header("refresh:1;url=admins.php");
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                    <?php }
                    } else if ($do == 'Delete') {
                        if (isset($_GET['ID'])) $getid = check_input($_GET['ID']);
                        else if (isset($_POST['ID'])) $getid = check_input($_POST['ID']);
                        else header("location: admins.php");
                        $st = $con->prepare("select * from admins where ad_no= ?");
                        $st->execute(array($getid));
                        $ad = $st->fetch();
                        $count = $st->rowCount();
                        if ($count > 0) {
                            $st = $con->prepare("delete from admins where ad_no=:id");
                            $st->bindParam(":id", $getid);
                            $st->execute();
                            echo '<p class="blockquote text-light bg-gradient-success">' . $st->rowCount() . ' Recored Deleted Successfully</p>';
                            header("refresh:1;url=admins.php");
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <script>
        function printContent() {
            var display_setting = "toolbar=no,location=no,directories=no,menubar=no,";
            display_setting += "scrollbars=no,width=500, height=500, left=100, top=25";
            var content_innerhtml = document.getElementById("printout").innerHTML;
            var document_print = window.open("", "", display_setting);
            document_print.document.open();
            document_print.document.write('<header><style>*{border:none;outline:none;background:none; margin:10px;width:350px;}a{display:none;}</style></header><body style="font-family:verdana; font-size:12px;" onLoad="self.print();self.close();">');
            document_print.document.write(content_innerhtml);
            document_print.document.write('</body></html>');
            document_print.print();
            document_print.document.close();
            return false;
        }
    </script>
<?php include $tpl . 'footer.php';
} else {
    header('Location: index.php');
    exit();
}
ob_end_flush();
?>
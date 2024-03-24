<?php
ob_start();
session_start();
if (isset($_SESSION['username'])) {
    $pageTitle = 'Guests';
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
                                <i class="mdi mdi-account"></i>
                            </span> Guests
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
                        $st = $con->prepare("select * from guests");
                        $st->execute();
                        $gus = $st->fetchAll();
                        if (!empty($gus)) {
                    ?>
                            <div class="card">
                                <div class="card-body">
                                    <?php if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'subadmin' || $_SESSION['role'] == 'subadminadd') { ?>
                                        <form class="forms-sample" action="<?php $_POST['add_new'] ?>" method="post">
                                            <button type="submit" class="btn btn-gradient-primary me-2" name="add_new">Add New Guest</button>
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
                                                    <th> Control</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                foreach ($gus as $gu) {
                                                    echo '<tr>';
                                                    echo '<td class="py-1">' . $gu['gu_no'] . '</td>';
                                                    echo '<td>' . $gu['gu_name'] . '</td>';
                                                    echo "<td> ";
                                                ?>
                                                    <?php if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'subadmin' || $_SESSION['role'] == 'subadminshow') { ?>
                                                        <form action="<?php $_POST['show'] ?>" method="post">
                                                            <input type="hidden" name="id" value="<?php echo $gu['gu_no']; ?>">
                                                            <button type="submit" class="btn btn-gradient-info btn-rounded btn-icon" name="show">
                                                                <i class="mdi mdi-eye"></i>
                                                            </button>
                                                        </form>
                                                    <?php } ?>
                                                    <?php if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'subadmin' || $_SESSION['role'] == 'subadminedit') { ?>
                                                        <form action="<?php $_POST['edit'] ?>" method="post">
                                                            <input type="hidden" name="id" value="<?php echo $gu['gu_no']; ?>">
                                                            <button type="submit" class="btn btn-gradient-success btn-rounded btn-icon" name="edit">
                                                                <i class="mdi mdi-lead-pencil"></i>
                                                            </button>
                                                        </form>
                                                    <?php } ?>
                                                    <?php if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'subadmin' || $_SESSION['role'] == 'subadmindelete') { ?>
                                                        <form action="<?php $_POST['delete'] ?>" method="post">
                                                            <input type="hidden" name="id" value="<?php echo $gu['gu_no']; ?>">
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
                                                    <td><?php echo countItems('gu_no', 'guests') ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <button class="btn btn-gradient-primary me-2" onclick="printContent();">Print Report</button>
                                </div>
                            </div>
                            <?php
                            if (isset($_POST['show'])) {
                                header("location:guests.php?do=Show&ID=" . check_input($_POST['id']) . "");
                            } else if (isset($_POST['edit'])) {
                                header("location:guests.php?do=Edit&ID=" . check_input($_POST['id']) . "");
                            } else if (isset($_POST['delete'])) {
                                header("location:guests.php?do=Delete&ID=" . check_input($_POST['id']) . "");
                            } else if (isset($_POST['add_new'])) {
                                header("location:guests.php?do=Add");
                            }
                        } else { ?>
                            <div class="card">
                                <div class="card-body">
                                    <p class="blockquote text-light bg-gradient-info"> Sorry ! There is No Guest Please Add First Guest </p>
                                    <form class="forms-sample" action="<?php $_POST['add_first'] ?>" method="post">
                                        <button type="submit" class="btn btn-gradient-primary me-2" name="add_first">Add First Guest</button>
                                    </form>
                                </div>
                            </div>
                            <?php
                            if (isset($_POST['add_first'])) {
                                header("location:guests.php?do=Add");
                            }
                        }
                    } else if ($do == 'Show') {
                        if (isset($_GET['ID'])) $getid = check_input($_GET['ID']);
                        else if (isset($_POST['ID'])) $getid = check_input($_POST['ID']);
                        else header("location: guests.php");
                        $st = $con->prepare("select * from guests where gu_no= ?");
                        $st->execute(array($getid));
                        $gu = $st->fetch();
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
                                                <input type="text" name="gu_no" class="form-control" id="exampleInputUsername1" value="<?php echo $gu['gu_no'] ?>" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputUsername1">Name</label>
                                                <input type="text" name="gu_name" class="form-control" id="exampleInputUsername1" value="<?php echo $gu['gu_name'] ?>" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputUsername1">Gender</label>
                                                <input type="text" name="gu_name" class="form-control" id="exampleInputUsername1" value="<?php echo $gu['gender'] ?>" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputUsername1">Phone</label>
                                                <input type="text" name="gu_name" class="form-control" id="exampleInputUsername1" value="<?php echo $gu['gu_phone'] ?>" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputUsername1">Email</label>
                                                <input type="text" name="gu_name" class="form-control" id="exampleInputUsername1" value="<?php echo $gu['gu_email'] ?>" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputUsername1">Address</label>
                                                <input type="text" name="gu_name" class="form-control" id="exampleInputUsername1" value="<?php echo $gu['gu_address'] ?>" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputUsername1">Code Type</label>
                                                <input type="text" name="gu_code_type" class="form-control" id="exampleInputUsername1" value="<?php foreach ($Guests_code_type['code_type'] as $key => $value) {
                                                                                                                                                    if ($gu['gu_code_type'] == $key)
                                                                                                                                                        echo $value;
                                                                                                                                                } ?>" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputUsername1">Code</label>
                                                <input type="text" name="gu_code" class="form-control" id="exampleInputUsername1" value="<?php echo $gu['gu_code'] ?>" readonly>
                                            </div>
                                            <a href="guests.php" class="btn btn-light">Cancel</a>
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
                                <form class="forms-sample" action="<?php $_POST['add_gu'] ?>" method="post">
                                    <div class="form-group">
                                        <label for="exampleInputUsername1">Name</label>
                                        <input type="text" name="gu_name" class="form-control" id="exampleInputUsername1" placeholder="Guest Name" autocomplete="off">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleSelectGender">Gender</label>
                                        <select name="gender" class="form-control" id="exampleSelectGender">
                                            <?php
                                            foreach ($genders['gender'] as $gen) {
                                                echo "<option value='" . $gen . "'>" . $gen . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputUsername1">Phone</label>
                                        <input type="text" name="gu_phone" class="form-control" id="exampleInputUsername1" placeholder="Guest Phone" autocomplete="off">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputUsername1">Email</label>
                                        <input type="text" name="gu_email" class="form-control" id="exampleInputUsername1" placeholder="Guest Email" autocomplete="off">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputUsername1">Address</label>
                                        <input type="text" name="gu_address" class="form-control" id="exampleInputUsername1" placeholder="Guest Address" autocomplete="off">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleSelectGender">Code Type</label>
                                        <select name="gu_code_type" class="form-control" id="exampleSelectGender">
                                            <?php
                                            foreach ($Guests_code_type['code_type'] as $code_type) {
                                                echo "<option value='" . $code_type . "'>" . $code_type . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputUsername1">Code</label>
                                        <input type="number" name="gu_code" class="form-control" id="exampleInputUsername1" placeholder="Guest Code" autocomplete="off">
                                    </div>
                                    <button type="submit" class="btn btn-gradient-primary me-2" name="add_gu">Add</button>
                                    <a href="guests.php" class="btn btn-light">Cancel</a>
                                </form>
                                <?php
                                if (isset($_POST['add_gu'])) {
                                    $gu_name = check_input($_POST['gu_name']);
                                    $gender = check_input($_POST['gender']);
                                    $gu_phone = check_input($_POST['gu_phone']);
                                    $gu_email = check_input($_POST['gu_email']);
                                    $gu_address = check_input($_POST['gu_address']);
                                    $gu_code_type = check_input($_POST['gu_code_type']);
                                    $gu_code = check_input($_POST['gu_code']);
                                    $formErrors = array();
                                    if (empty($gu_name)) {
                                        $formErrors[] = "The Name Must be <strong> not Empty </strong>";
                                    }
                                    if (empty($gu_phone)) {
                                        $formErrors[] = "The Phone Must be <strong> not Empty </strong>";
                                    }
                                    if (!filter_var($gu_phone, FILTER_VALIDATE_INT)) {
                                        $formErrors[] = "The Phone Must be <strong> not Letters </strong>";
                                    }
                                    if (empty($gu_email)) {
                                        $formErrors[] = "The Email Must be <strong> not Empty </strong>";
                                    }
                                    if (!filter_var($gu_email, FILTER_VALIDATE_EMAIL)) {
                                        $formErrors[] = "The Email is <strong> Invalid </strong> Check It";
                                    }
                                    if (empty($gu_address)) {
                                        $formErrors[] = "The Address Must be <strong> not Empty </strong>";
                                    }
                                    if (empty($gu_code)) {
                                        $formErrors[] = "The Code Must be <strong> not Empty </strong>";
                                    }
                                    $check = checkItem("gu_name", "guests", $gu_name);
                                    if ($check == 1) {
                                        $formErrors[] = 'Sorry This Guest is<strong>Exist</strong>';
                                    }
                                    foreach ($formErrors as $error) {
                                        echo '<p class="blockquote text-light bg-gradient-danger">' . $error . '</p>';
                                    }
                                    if (empty($formErrors)) {
                                        $st = $con->prepare("insert into guests (gu_name,gender,gu_phone,gu_email,gu_address,gu_code_type,gu_code) 
                                        values(:name,:gender,:phone,:email,:address,:code_type,:code)");
                                        $st->execute(array(
                                            'name' => $gu_name,
                                            'gender' => $gender,
                                            'phone' => $gu_phone,
                                            'email' => $gu_email,
                                            'address' => $gu_address,
                                            'code_type' => $gu_code_type,
                                            'code' => $gu_code
                                        ));
                                        echo '<p class="blockquote text-light bg-gradient-success">' . $st->rowCount() . ' Recored Added Successfully</p>';
                                        header("refresh:1;url=guests.php");
                                    }
                                } ?>
                            </div>
                        </div>
                        <?php
                    } else if ($do == 'Edit') {
                        if (isset($_GET['ID'])) $getid = check_input($_GET['ID']);
                        else if (isset($_POST['ID'])) $getid = check_input($_POST['ID']);
                        else header("location: guests.php");
                        $st = $con->prepare("select * from guests where gu_no= ?");
                        $st->execute(array($getid));
                        $gu = $st->fetch();
                        $count = $st->rowCount();
                        if ($count > 0) {
                        ?>
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Edit</h4>
                                    <form class="forms-sample" action="<?php $_POST['edit_gu'] ?>" method="post">
                                        <input type="hidden" name="gu_no" value="<?php echo $gu['gu_no']; ?>">
                                        <div class="form-group">
                                            <label for="exampleInputUsername1">Name</label>
                                            <input type="hidden" name="oldname" value="<?php echo $gu['gu_name']; ?>">
                                            <input type="text" name="gu_name" class="form-control" id="exampleInputUsername1" value="<?php echo $gu['gu_name']; ?>" placeholder=" guests Name" autocomplete="off">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleSelectGender">Gender</label>
                                            <select name="gender" class="form-control" id="exampleSelectGender">
                                                <?php
                                                foreach ($genders['gender'] as $gen) {
                                                    echo "<option value='" . $gen . "'";
                                                    if ($gu['gender'] == $gen) {
                                                        echo 'selected';
                                                    }
                                                    echo ">" . $gen . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputUsername1">Phone</label>
                                            <input type="text" name="gu_phone" class="form-control" id="exampleInputUsername1" value="<?php echo $gu['gu_phone']; ?>" placeholder="Guest Phone" autocomplete="off">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputUsername1">Email</label>
                                            <input type="text" name="gu_email" class="form-control" id="exampleInputUsername1" value="<?php echo $gu['gu_email']; ?>" placeholder="Guest Email" autocomplete="off">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputUsername1">Address</label>
                                            <input type="text" name="gu_address" class="form-control" id="exampleInputUsername1" value="<?php echo $gu['gu_address']; ?>" placeholder="Guest Address" autocomplete="off">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleSelectGender">Code Type</label>
                                            <select name="gu_code_type" class="form-control" id="exampleSelectGender">
                                                <?php
                                                foreach ($Guests_code_type['code_type'] as $code_type) {
                                                    echo "<option value='" . $code_type . "'";
                                                    if ($gu['gu_code_type'] == $code_type) {
                                                        echo 'selected';
                                                    }
                                                    echo ">" . $code_type . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputUsername1">Code</label>
                                            <input type="number" name="gu_code" class="form-control" id="exampleInputUsername1" value="<?php echo $gu['gu_code']; ?>" placeholder="Guest Code" autocomplete="off">
                                        </div>
                                        <button type="submit" class="btn btn-gradient-primary me-2" name="edit_gu">Edit</button>
                                        <a href="guests.php" class="btn btn-light">Cancel</a>
                                    </form>
                                    <?php
                                    if (isset($_POST['edit_gu'])) {
                                        $gu_no = check_input($_POST['gu_no']);
                                        $gu_name = check_input($_POST['gu_name']);
                                        $oldname = check_input($_POST['oldname']);
                                        $gender = check_input($_POST['gender']);
                                        $gu_phone = check_input($_POST['gu_phone']);
                                        $gu_email = check_input($_POST['gu_email']);
                                        $gu_address = check_input($_POST['gu_address']);
                                        $gu_code_type = check_input($_POST['gu_code_type']);
                                        $gu_code = check_input($_POST['gu_code']);
                                        $formErrors = array();
                                        if (empty($gu_name)) {
                                            $formErrors[] = "The Name Must be <strong> not Empty </strong>";
                                        }
                                        if (empty($gu_phone)) {
                                            $formErrors[] = "The Phone Must be <strong> not Empty </strong>";
                                        }
                                        if (!filter_var($gu_phone, FILTER_VALIDATE_INT)) {
                                            $formErrors[] = "The Phone Must be <strong> not Letters </strong>";
                                        }
                                        if (empty($gu_email)) {
                                            $formErrors[] = "The Email Must be <strong> not Empty </strong>";
                                        }
                                        if (!filter_var($gu_email, FILTER_VALIDATE_EMAIL)) {
                                            $formErrors[] = "The Email is <strong> Invalid </strong> Check It";
                                        }
                                        if (empty($gu_address)) {
                                            $formErrors[] = "The Address Must be <strong> not Empty </strong>";
                                        }
                                        if (empty($gu_code)) {
                                            $formErrors[] = "The Code Must be <strong> not Empty </strong>";
                                        }
                                        if ($oldname == $gu_name) {
                                            $name = $oldname;
                                        } else {
                                            $name = $gu_name;
                                            $check = checkItem("gu_name", "guests", $name);
                                            if ($check == 1) {
                                                $formErrors[] = 'Sorry This Username is<strong>Exist</strong>';
                                            }
                                        }
                                        foreach ($formErrors as $error) {
                                            echo '<p class="blockquote text-light bg-gradient-danger">' . $error . '</p>';
                                        }
                                        if (empty($formErrors)) {
                                            $st = $con->prepare("update guests set gu_name=?,gender=?,gu_phone=?,gu_email=?,gu_address=?,gu_code_type=?,gu_code=? where gu_no=?");
                                            $st->execute(array($name, $gender, $gu_phone, $gu_email, $gu_address, $gu_code_type, $gu_code, $gu_no));
                                            echo '<p class="blockquote text-light bg-gradient-success">' . $st->rowCount() . ' Recored Updated Successfully</p>';
                                            header("refresh:1;url=guests.php");
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                    <?php }
                    } else if ($do == 'Delete') {
                        if (isset($_GET['ID'])) $getid = check_input($_GET['ID']);
                        else if (isset($_POST['ID'])) $getid = check_input($_POST['ID']);
                        else header("location: guests.php");
                        $st = $con->prepare("select * from guests where gu_no= ?");
                        $st->execute(array($getid));
                        $gu = $st->fetch();
                        $count = $st->rowCount();
                        if ($count > 0) {
                            $st = $con->prepare("delete from guests where gu_no=:id");
                            $st->bindParam(":id", $getid);
                            $st->execute();
                            echo '<p class="blockquote text-light bg-gradient-success">' . $st->rowCount() . ' Recored Deleted Successfully</p>';
                            header("refresh:1;url=guests.php");
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
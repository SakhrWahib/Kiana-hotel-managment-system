<?php
ob_start();
session_start();
if (isset($_SESSION['username'])) {
    $pageTitle = 'Orders';
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
                                <i class="mdi mdi-format-list-bulleted"></i>
                            </span> Orders
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
                        $st = $con->prepare("select * from orders");
                        $st->execute();
                        $ors = $st->fetchAll();
                        if (!empty($ors)) {
                    ?>
                            <div class="card">
                                <div class="card-body">
                                    <?php if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'subadmin' || $_SESSION['role'] == 'subadminadd') { ?>
                                        <form class="forms-sample" action="<?php $_POST['add_new'] ?>" method="post">
                                            <button type="submit" class="btn btn-gradient-primary me-2" name="add_new">Add New Order</button>
                                        </form>
                                    <?php } ?>
                                </div>
                                <div class="card-body">
                                    <div id="printout">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th> ID </th>
                                                    <th> Time </th>
                                                    <th> Money </th>
                                                    <th> State </th>
                                                    <th> Control</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                foreach ($ors as $or) {
                                                    echo '<tr>';
                                                    echo '<td class="py-1">' . $or['or_no'] . '</td>';
                                                    echo '<td>' . $or['or_time'] . '</td>';
                                                    echo '<td>' . $or['or_money'] . '</td>';
                                                    echo '<td>';
                                                    if ($or['or_state'] == 0) {
                                                ?>
                                                        <button class="btn btn-gradient-danger btn-rounded btn-icon" name="edit">
                                                            <i class="mdi mdi-close"></i>
                                                        </button>
                                                    <?php
                                                    } else if ($or['or_state'] == 1) {
                                                    ?>
                                                        <button class="btn btn-gradient-success btn-rounded btn-icon" name="edit">
                                                            <i class="mdi mdi-check"></i>
                                                        </button>
                                                    <?php
                                                    }
                                                    echo '</td>';
                                                    echo "<td> ";
                                                    ?>
                                                    <?php if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'subadmin' || $_SESSION['role'] == 'subadminshow') { ?>
                                                        <form action="<?php $_POST['show'] ?>" method="post">
                                                            <input type="hidden" name="id" value="<?php echo $or['or_no']; ?>">
                                                            <button type="submit" class="btn btn-gradient-info btn-rounded btn-icon" name="show">
                                                                <i class="mdi mdi-eye"></i>
                                                            </button>
                                                        </form>
                                                    <?php } ?>
                                                    <?php if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'subadmin' || $_SESSION['role'] == 'subadminedit') { ?>
                                                        <form action="<?php $_POST['edit'] ?>" method="post">
                                                            <input type="hidden" name="id" value="<?php echo $or['or_no']; ?>">
                                                            <button type="submit" class="btn btn-gradient-success btn-rounded btn-icon" name="edit">
                                                                <i class="mdi mdi-lead-pencil"></i>
                                                            </button>
                                                        </form>
                                                    <?php } ?>
                                                    <?php if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'subadmin' || $_SESSION['role'] == 'subadmindelete') { ?>
                                                        <form action="<?php $_POST['delete'] ?>" method="post">
                                                            <input type="hidden" name="id" value="<?php echo $or['or_no']; ?>">
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
                                                    <td><?php echo countItems('or_no', 'orders') ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <button class="btn btn-gradient-primary me-2" onclick="printContent();">Print Report</button>
                                </div>
                            </div>
                            <?php
                            if (isset($_POST['show'])) {
                                header("location:orders.php?do=Show&ID=" . check_input($_POST['id']) . "");
                            } else if (isset($_POST['edit'])) {
                                header("location:orders.php?do=Edit&ID=" . check_input($_POST['id']) . "");
                            } else if (isset($_POST['delete'])) {
                                header("location:orders.php?do=Delete&ID=" . check_input($_POST['id']) . "");
                            } else if (isset($_POST['add_new'])) {
                                header("location:orders.php?do=Add");
                            }
                        } else { ?>
                            <div class="card">
                                <div class="card-body">
                                    <p class="blockquote text-light bg-gradient-info"> Sorry ! There is No Order Please Add First Order </p>
                                    <form class="forms-sample" action="<?php $_POST['add_first'] ?>" method="post">
                                        <button type="submit" class="btn btn-gradient-primary me-2" name="add_first">Add First Order</button>
                                    </form>
                                </div>
                            </div>
                            <?php
                            if (isset($_POST['add_first'])) {
                                header("location:orders.php?do=Add");
                            }
                        }
                    } else if ($do == 'Show') {
                        if (isset($_GET['ID'])) $getid = check_input($_GET['ID']);
                        else if (isset($_POST['ID'])) $getid = check_input($_POST['ID']);
                        else header("location: orders.php");
                        $st = $con->prepare("select * from orders where or_no= ?");
                        $st->execute(array($getid));
                        $or = $st->fetch();
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
                                                <input type="text" name="or_no" class="form-control" id="exampleInputUsername1" value="<?php echo $or['or_no'] ?>" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputUsername1">Time</label>
                                                <input type="text" name="or_time" class="form-control" id="exampleInputUsername1" value="<?php echo $or['or_time'] ?>" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputUsername1">Amount</label>
                                                <input type="text" name="or_mount" class="form-control" id="exampleInputUsername1" value="<?php echo $or['or_mount'] ?>" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputUsername1">Type</label>
                                                <input type="text" name="or_type" class="form-control" id="exampleInputUsername1" value="<?php echo $or['or_type'] ?>" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleTextarea1">Details</label>
                                                <textarea name="or_details" class="form-control" id="exampleTextarea1" rows="4" readonly><?php echo $or['or_details'] ?></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputUsername1">Money</label>
                                                <input type="text" name="or_money" class="form-control" id="exampleInputUsername1" value="<?php echo $or['or_money'] ?>" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputUsername1">State</label>
                                                <input type="text" name="or_state" class="form-control" id="exampleInputUsername1" value="<?php foreach ($Pays['Pay'] as $key => $value) {
                                                                                                                                                if ($or['or_state'] == $key)
                                                                                                                                                    echo $value;
                                                                                                                                            } ?>" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputUsername1">Guest</label>
                                                <input type="text" name="gu_no" class="form-control" id="exampleInputUsername1" value="<?php
                                                                                                                                        $allgu = getAllFrom("*", "guests", "", "", "gu_no");
                                                                                                                                        foreach ($allgu as $gu) {
                                                                                                                                            if ($or['gu_no'] == $gu['gu_no'])
                                                                                                                                                echo $gu['gu_name'];
                                                                                                                                            else echo NULL;
                                                                                                                                        } ?>" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputUsername1">Room</label>
                                                <input type="text" name="rm_no" class="form-control" id="exampleInputUsername1" value="<?php echo $or['rm_no'] ?>" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputUsername1">Floor</label>
                                                <input type="text" name="floor_no" class="form-control" id="exampleInputUsername1" value="<?php echo $or['floor_no'] ?>" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputUsername1">Employee</label>
                                                <input type="text" name="emp_no" class="form-control" id="exampleInputUsername1" value="<?php
                                                                                                                                        $allemps = getAllFrom("*", "employees", "", "", "emp_no");
                                                                                                                                        foreach ($allemps as $emp) {
                                                                                                                                            if ($or['emp_no'] == $emp['emp_no'])
                                                                                                                                                echo $emp['emp_name'];
                                                                                                                                            else echo NULL;
                                                                                                                                        } ?>" readonly>
                                            </div>
                                            <a href="orders.php" class="btn btn-light">Cancel</a>
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
                                <form class="forms-sample" action="<?php $_POST['add_or'] ?>" method="post">
                                    <div class="form-group">
                                        <label for="exampleInputUsername1">Time</label>
                                        <input type="datetime-local" name="or_time" class="form-control" id="exampleInputUsername1" placeholder="" autocomplete="off">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputUsername1">Amount</label>
                                        <input type="number" name="or_mount" class="form-control" id="exampleInputUsername1" placeholder="Amount of Order" autocomplete="off">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputUsername1">Type</label>
                                        <input type="text" name="or_type" class="form-control" id="exampleInputUsername1" placeholder="Type of Order" autocomplete="off">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleTextarea1">Details</label>
                                        <textarea name="or_details" class="form-control" id="exampleTextarea1" rows="4" placeholder="Details of Order" autocomplete="off"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputUsername1">Money</label>
                                        <input type="number" name="or_money" class="form-control" id="exampleInputUsername1" placeholder="Money of Order" autocomplete="off">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleSelectGender">State</label>
                                        <select name="or_state" class="form-control" id="exampleSelectGender">
                                            <?php
                                            foreach ($Pays['Pay'] as $key => $value) {
                                                echo "<option value='" . $key . "'>" . $value . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleSelectGender">Guest</label>
                                        <select name="gu_no" class="form-control" id="exampleSelectGender">
                                            <option value="0">...</option>
                                            <?php
                                            $allgu = getAllFrom("*", "guests", "", "", "gu_no");
                                            foreach ($allgu as $gu) {
                                                echo "<option value='" . $gu['gu_no'] . "'>" . $gu['gu_name'] . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleSelectGender">Room</label>
                                        <select name="rm_no" class="form-control" id="exampleSelectGender">
                                            <option value="0">...</option>
                                            <?php
                                            $allrm = getAllFrom("*", "rooms", "where rm_state=1", "", "rm_no");
                                            foreach ($allrm as $rm) {
                                                echo "<option value='" . $rm['rm_no'] . "'>" . $rm['rm_no'] . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleSelectGender">Employee</label>
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
                                    <button type="submit" class="btn btn-gradient-primary me-2" name="add_or">Add</button>
                                    <a href="orders.php" class="btn btn-light">Cancel</a>
                                </form>
                                <?php
                                if (isset($_POST['add_or'])) {
                                    $or_time = check_input($_POST['or_time']);
                                    $or_mount = check_input($_POST['or_mount']);
                                    $or_type = check_input($_POST['or_type']);
                                    $or_details = check_input($_POST['or_details']);
                                    $or_money = check_input($_POST['or_money']);
                                    $or_state = check_input($_POST['or_state']);
                                    $gu_no = check_input($_POST['gu_no']);
                                    $rm_no = check_input($_POST['rm_no']);
                                    $emp_no = check_input($_POST['emp_no']);
                                    $formErrors = array();
                                    if (empty($or_time)) {
                                        $formErrors[] = "The Date Must be <strong> not Empty </strong>";
                                    }
                                    if (empty($or_mount)) {
                                        $formErrors[] = "The Amount Must be <strong> not Empty </strong>";
                                    }
                                    if (empty($or_type)) {
                                        $formErrors[] = "The Type Must be <strong> not Empty </strong>";
                                    }
                                    if (empty($or_details)) {
                                        $formErrors[] = "The Details Must be <strong> not Empty </strong>";
                                    }
                                    if (empty($or_money)) {
                                        $formErrors[] = "The Money Must be <strong> not Empty </strong>";
                                    }
                                    foreach ($formErrors as $error) {
                                        echo '<p class="blockquote text-light bg-gradient-danger">' . $error . '</p>';
                                    }
                                    if (empty($formErrors)) {
                                        $st = $con->prepare("insert into 
                                        orders (or_time,or_mount,or_type,or_details,or_money,or_state,gu_no,rm_no,floor_no,emp_no)
                                         values(:time,:amount,:type,:detail,:money,:state,:guest,:room,:floor,:employee)");
                                        if ($rm_no == 0) {
                                            $rm_no = NULL;
                                            $floor_no = NULL;
                                        } else {
                                            $st1 = $con->prepare("select floor_no from rooms where rm_no= ?");
                                            $st1->execute(array($rm_no));
                                            $rm1 = $st1->fetch();
                                            $floor_no = $rm1['floor_no'];
                                        }
                                        if ($gu_no == 0) $gu_no = NULL;
                                        if ($emp_no == 0) $emp_no = NULL;
                                        $st->execute(array(
                                            'time' => $or_time,
                                            'amount' => $or_mount,
                                            'type' => $or_type,
                                            'detail' => $or_details,
                                            'money' => $or_money,
                                            'state' => $or_state,
                                            'guest' => $gu_no,
                                            'room' => $rm_no,
                                            'floor' => $floor_no,
                                            'employee' => $emp_no
                                        ));
                                        echo '<p class="blockquote text-light bg-gradient-success">' . $st->rowCount() . ' Recored Added Successfully</p>';
                                        header("refresh:1;url=orders.php");
                                    }
                                } ?>
                            </div>
                        </div>
                        <?php
                    } else if ($do == 'Edit') {
                        if (isset($_GET['ID'])) $getid = check_input($_GET['ID']);
                        else if (isset($_POST['ID'])) $getid = check_input($_POST['ID']);
                        else header("location: orders.php");
                        $st = $con->prepare("select * from orders where or_no= ?");
                        $st->execute(array($getid));
                        $or = $st->fetch();
                        $count = $st->rowCount();
                        if ($count > 0) {
                        ?>
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Edit</h4>
                                    <form class="forms-sample" action="<?php $_POST['edit_or'] ?>" method="post">
                                        <input type="hidden" name="or_no" value="<?php echo $or['or_no']; ?>">
                                        <div class="form-group">
                                            <label for="exampleInputUsername1">Time</label>
                                            <input type="datetime-local" name="or_time" class="form-control" id="exampleInputUsername1" value="<?php echo $or['or_time']; ?>" autocomplete="off">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputUsername1">Amount</label>
                                            <input type="number" name="or_mount" class="form-control" id="exampleInputUsername1" value="<?php echo $or['or_mount']; ?>" placeholder="Amount of Order" autocomplete="off">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputUsername1">Type</label>
                                            <input type="text" name="or_type" class="form-control" id="exampleInputUsername1" value="<?php echo $or['or_type']; ?>" placeholder="Type of Order" autocomplete="off">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleTextarea1">Details</label>
                                            <textarea name="or_details" class="form-control" id="exampleTextarea1" rows="4" placeholder="Details of Order" autocomplete="off"><?php echo $or['or_details']; ?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputUsername1">Money</label>
                                            <input type="number" name="or_money" class="form-control" id="exampleInputUsername1" value="<?php echo $or['or_money']; ?>" placeholder="Money of Order" autocomplete="off">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleSelectGender">State</label>
                                            <select name="or_state" class="form-control" id="exampleSelectGender">
                                                <?php
                                                foreach ($Pays['Pay'] as $key => $value) {
                                                    echo "<option value='" . $key . "'";
                                                    if ($or['or_state'] == $key) {
                                                        echo 'selected';
                                                    }
                                                    echo ">" . $value . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleSelectGender">Guest</label>
                                            <select name="gu_no" class="form-control" id="exampleSelectGender">
                                                <option value="0">...</option>
                                                <?php
                                                $allgu = getAllFrom("*", "guests", "", "", "gu_no");
                                                foreach ($allgu as $gu) {
                                                    echo "<option value='" . $gu['gu_no'] . "'";
                                                    if ($or['gu_no'] == $gu['gu_no']) {
                                                        echo 'selected';
                                                    }
                                                    echo ">" . $gu['gu_name'] . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleSelectGender">Room</label>
                                            <select name="rm_no" class="form-control" id="exampleSelectGender">
                                                <option value="0">...</option>
                                                <?php
                                                $allrm = getAllFrom("*", "rooms", "", "", "rm_no");
                                                foreach ($allrm as $rm) {
                                                    echo "<option ' value='" . $rm['rm_no'] . "'";
                                                    if ($or['rm_no'] == $rm['rm_no']) {
                                                        echo 'selected';
                                                    }
                                                    echo ">" . $rm['rm_no'] . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleSelectGender">Employee</label>
                                            <select name="emp_no" class="form-control" id="exampleSelectGender">
                                                <option value="0">...</option>
                                                <?php
                                                $allemps = getAllFrom("*", "employees", "", "", "emp_no");
                                                foreach ($allemps as $emp) {
                                                    echo "<option value='" . $emp['emp_no'] . "'";
                                                    if ($or['emp_no'] == $emp['emp_no']) {
                                                        echo 'selected';
                                                    }
                                                    echo ">" . $emp['emp_name'] . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-gradient-primary me-2" name="edit_or">Edit</button>
                                        <a href="orders.php" class="btn btn-light">Cancel</a>
                                    </form>
                                    <?php
                                    if (isset($_POST['edit_or'])) {
                                        $or_no = check_input($_POST['or_no']);
                                        $or_time = check_input($_POST['or_time']);
                                        $or_mount = check_input($_POST['or_mount']);
                                        $or_type = check_input($_POST['or_type']);
                                        $or_details = check_input($_POST['or_details']);
                                        $or_money = check_input($_POST['or_money']);
                                        $or_state = check_input($_POST['or_state']);
                                        $gu_no = check_input($_POST['gu_no']);
                                        $rm_no = check_input($_POST['rm_no']);
                                        $emp_no = check_input($_POST['emp_no']);
                                        $formErrors = array();
                                        if (empty($or_time)) {
                                            $formErrors[] = "The Date Must be <strong> not Empty </strong>";
                                        }
                                        if (empty($or_mount)) {
                                            $formErrors[] = "The Amount Must be <strong> not Empty </strong>";
                                        }
                                        if (empty($or_type)) {
                                            $formErrors[] = "The Type Must be <strong> not Empty </strong>";
                                        }
                                        if (empty($or_details)) {
                                            $formErrors[] = "The Details Must be <strong> not Empty </strong>";
                                        }
                                        if (empty($or_money)) {
                                            $formErrors[] = "The Money Must be <strong> not Empty </strong>";
                                        }
                                        foreach ($formErrors as $error) {
                                            echo '<p class="blockquote text-light bg-gradient-danger">' . $error . '</p>';
                                        }
                                        if (empty($formErrors)) {
                                            $st = $con->prepare("update orders set 
                                            or_time=?,or_mount=?,or_type=?,or_details=?,or_money=?,or_state=?,gu_no=?,rm_no=?,floor_no=?,emp_no=?
                                            where or_no=?");

                                            if ($rm_no == 0) {
                                                $rm_no = NULL;
                                                $floor_no = NULL;
                                            } else {
                                                $st1 = $con->prepare("select floor_no from rooms where rm_no= ?");
                                                $st1->execute(array($rm_no));
                                                $rm1 = $st1->fetch();
                                                $floor_no = $rm1['floor_no'];
                                            }
                                            if ($gu_no == 0) $gu_no = NULL;
                                            if ($emp_no == 0) $emp_no = NULL;

                                            $st->execute(array($or_time, $or_mount, $or_type, $or_details, $or_money, $or_state, $gu_no, $rm_no, $floor_no, $emp_no, $or_no));
                                            echo '<p class="blockquote text-light bg-gradient-success">' . $st->rowCount() . ' Recored Updated Successfully</p>';
                                            header("refresh:1;url=orders.php");
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                    <?php }
                    } else if ($do == 'Delete') {
                        if (isset($_GET['ID'])) $getid = check_input($_GET['ID']);
                        else if (isset($_POST['ID'])) $getid = check_input($_POST['ID']);
                        else header("location: orders.php");
                        $st = $con->prepare("select * from orders where or_no= ?");
                        $st->execute(array($getid));
                        $or = $st->fetch();
                        $count = $st->rowCount();
                        if ($count > 0) {
                            $st = $con->prepare("delete from orders where or_no=:id");
                            $st->bindParam(":id", $getid);
                            $st->execute();
                            echo '<p class="blockquote text-light bg-gradient-success">' . $st->rowCount() . ' Recored Deleted Successfully</p>';
                            header("refresh:1;url=orders.php");
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
            document_print.document.write('<body style="font-family:verdana; font-size:12px;" onLoad="self.print();self.close();">');
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
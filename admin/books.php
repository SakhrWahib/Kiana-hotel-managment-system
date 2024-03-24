<?php
ob_start();
session_start();
if (isset($_SESSION['username'])) {
    $pageTitle = 'Books';
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
                                <i class="mdi mdi-bookmark"></i>
                            </span> Books
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
                        $st = $con->prepare("select * from books");
                        $st->execute();
                        $bos = $st->fetchAll();
                        if (!empty($bos)) {
                    ?>
                            <div class="card">
                                <div class="card-body">
                                    <?php if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'subadmin' || $_SESSION['role'] == 'subadminadd') { ?>
                                        <form class="forms-sample" action="<?php $_POST['add_new'] ?>" method="post">
                                            <button type="submit" class="btn btn-gradient-primary me-2" name="add_new">Add New Book</button>
                                        </form>
                                    <?php } ?>
                                </div>
                                <div class="card-body">
                                    <div id="printout">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th> ID </th>
                                                    <th> Books State</th>
                                                    <th> Books Date</th>
                                                    <th> Control</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                foreach ($bos as $bo) {
                                                    echo '<tr>';
                                                    echo '<td class="py-1">' . $bo['bo_no'] . '</td>';
                                                    echo '<td>';
                                                    if ($bo['gu_state'] == 0) {
                                                ?>
                                                        <button class="btn btn-gradient-danger btn-rounded btn-icon" name="edit">
                                                            <i class="mdi mdi-close"></i>
                                                        </button>
                                                    <?php
                                                    } else if ($bo['gu_state'] == 1) {
                                                    ?>
                                                        <button class="btn btn-gradient-success btn-rounded btn-icon" name="edit">
                                                            <i class="mdi mdi-check"></i>
                                                        </button>
                                                    <?php
                                                    }
                                                    echo '</td>';
                                                    echo '<td>' . $bo['bo_date'] . '</td>';
                                                    echo "<td> ";
                                                    ?>
                                                    <?php if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'subadmin' || $_SESSION['role'] == 'subadminshow') { ?>
                                                        <form action="<?php $_POST['show'] ?>" method="post">
                                                            <input type="hidden" name="id" value="<?php echo $bo['bo_no']; ?>">
                                                            <button type="submit" class="btn btn-gradient-info btn-rounded btn-icon" name="show">
                                                                <i class="mdi mdi-eye"></i>
                                                            </button>
                                                        </form>
                                                    <?php } ?>
                                                    <?php if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'subadmin' || $_SESSION['role'] == 'subadminedit') { ?>
                                                        <form action="<?php $_POST['edit'] ?>" method="post">
                                                            <input type="hidden" name="id" value="<?php echo $bo['bo_no']; ?>">
                                                            <button type="submit" class="btn btn-gradient-success btn-rounded btn-icon" name="edit">
                                                                <i class="mdi mdi-lead-pencil"></i>
                                                            </button>
                                                        </form>
                                                    <?php } ?>
                                                    <?php if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'subadmin' || $_SESSION['role'] == 'subadmindelete') { ?>
                                                        <form action="<?php $_POST['delete'] ?>" method="post">
                                                            <input type="hidden" name="id" value="<?php echo $bo['bo_no']; ?>">
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
                                                    <td><?php echo countItems('bo_no', 'books') ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <button class="btn btn-gradient-primary me-2" onclick="printContent();">Print Report</button>
                                </div>
                            </div>
                            <?php
                            if (isset($_POST['show'])) {
                                header("location:books.php?do=Show&ID=" . check_input($_POST['id']) . "");
                            } else if (isset($_POST['edit'])) {
                                header("location:books.php?do=Edit&ID=" . check_input($_POST['id']) . "");
                            } else if (isset($_POST['delete'])) {
                                header("location:books.php?do=Delete&ID=" . check_input($_POST['id']) . "");
                            } else if (isset($_POST['add_new'])) {
                                header("location:books.php?do=Add");
                            }
                        } else { ?>
                            <div class="card">
                                <div class="card-body">
                                    <p class="blockquote text-light bg-gradient-info"> Sorry ! There is No Book Please Add First Book </p>
                                    <form class="forms-sample" action="<?php $_POST['add_first'] ?>" method="post">
                                        <button type="submit" class="btn btn-gradient-primary me-2" name="add_first">Add First Book</button>
                                    </form>
                                </div>
                            </div>
                            <?php
                            if (isset($_POST['add_first'])) {
                                header("location:books.php?do=Add");
                            }
                        }
                    } else if ($do == 'Show') {
                        if (isset($_GET['ID'])) $getid = check_input($_GET['ID']);
                        else if (isset($_POST['ID'])) $getid = check_input($_POST['ID']);
                        else header("location: books.php");
                        $st = $con->prepare("select * from books where bo_no= ?");
                        $st->execute(array($getid));
                        $bo = $st->fetch();
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
                                                <input type="text" name="bo_no" class="form-control" id="exampleInputUsername1" value="<?php echo $bo['bo_no'] ?>" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputUsername1">State</label>
                                                <input type="text" name="gu_state" class="form-control" id="exampleInputUsername1" value="<?php foreach ($Guests_states['Arrive'] as $key => $value) {
                                                                                                                                                if ($bo['gu_state'] == $key)
                                                                                                                                                    echo $value;
                                                                                                                                            } ?>" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputUsername1">Payed amount</label>
                                                <input type="text" name="bo_payed_amount" class="form-control" id="exampleInputUsername1" value="<?php echo $bo['bo_payed_amount'] ?>" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputUsername1">Date</label>
                                                <input type="text" name="bo_date" class="form-control" id="exampleInputUsername1" value="<?php echo $bo['bo_date'] ?>" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputUsername1">Arrived Date</label>
                                                <input type="text" name="bo_arrive_date" class="form-control" id="exampleInputUsername1" value="<?php echo $bo['bo_arrive_date'] ?>" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputUsername1">How many Persons</label>
                                                <input type="text" name="bo_people_no" class="form-control" id="exampleInputUsername1" value="<?php echo $bo['bo_people_no'] ?>" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputUsername1">Room</label>
                                                <input type="text" name="rm_no" class="form-control" id="exampleInputUsername1" value="<?php echo $bo['rm_no'] ?>" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputUsername1">Guest</label>
                                                <input type="text" name="gu_no" class="form-control" id="exampleInputUsername1" value="<?php
                                                                                                                                        $allgu = getAllFrom("*", "guests", "", "", "gu_no");
                                                                                                                                        foreach ($allgu as $gu) {
                                                                                                                                            if ($bo['gu_no'] == $gu['gu_no'])
                                                                                                                                                echo $gu['gu_name'];
                                                                                                                                            else echo NULL;
                                                                                                                                        } ?>" readonly>
                                            </div>
                                            <a href="books.php" class="btn btn-light">Cancel</a>
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
                                <form class="forms-sample" action="<?php $_POST['add_bo'] ?>" method="post">
                                    <div class="form-group">
                                        <label for="exampleSelectGender">State</label>
                                        <select name="gu_state" class="form-control" id="exampleSelectGender">
                                            <?php
                                            foreach ($Guests_states['Arrive'] as $key => $value) {
                                                echo "<option value='" . $key . "'>" . $value . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputUsername1">Payed Amount</label>
                                        <input type="number" name="bo_payed_amount" class="form-control" id="exampleInputUsername1" placeholder="Book Payed Amount">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputUsername1">Date</label>
                                        <input type="date" name="bo_date" class="form-control" id="exampleInputUsername1" placeholder="Book Date">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputUsername1">Arrived Date</label>
                                        <input type="date" name="bo_arrive_date" class="form-control" id="exampleInputUsername1" placeholder="Book Arrived Date">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputUsername1">How many Persons</label>
                                        <input type="number" name="bo_people_no" class="form-control" id="exampleInputUsername1" placeholder="Book How many Persons">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleSelectGender">Room</label>
                                        <select name="rm_no" class="form-control" id="exampleSelectGender">
                                            <option value="0">...</option>
                                            <?php
                                            $allrm = getAllFrom("*", "rooms", "where rm_state=0", "", "rm_no");
                                            foreach ($allrm as $rm) {
                                                echo "<option value='" . $rm['rm_no'] . "'>" . $rm['rm_no'] . "</option>";
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
                                    <button type="submit" class="btn btn-gradient-primary me-2" name="add_bo">Add</button>
                                    <a href="books.php" class="btn btn-light">Cancel</a>
                                </form>
                                <?php
                                if (isset($_POST['add_bo'])) {
                                    $gu_state = check_input($_POST['gu_state']);
                                    $bo_payed_amount = check_input($_POST['bo_payed_amount']);
                                    $bo_date = check_input($_POST['bo_date']);
                                    $bo_arrive_date = check_input($_POST['bo_arrive_date']);
                                    $bo_people_no = check_input($_POST['bo_people_no']);
                                    $rm_no = check_input($_POST['rm_no']);
                                    $gu_no = check_input($_POST['gu_no']);
                                    $formErrors = array();
                                    if (empty($bo_payed_amount)) {
                                        $formErrors[] = "The Payed Amount Must be <strong> not Empty </strong>";
                                    }
                                    if (empty($bo_date)) {
                                        $formErrors[] = "The Date Must be <strong> not Empty </strong>";
                                    }
                                    if (empty($bo_arrive_date)) {
                                        $formErrors[] = "The Arrived Date Must be <strong> not Empty </strong>";
                                    }
                                    if (empty($bo_people_no)) {
                                        $formErrors[] = "The Persons Must be <strong> not Empty </strong>";
                                    }
                                    if (!filter_var($bo_people_no, FILTER_VALIDATE_INT)) {
                                        $formErrors[] = "The Persons Must be <strong> not Letters </strong>";
                                    }
                                    foreach ($formErrors as $error) {
                                        echo '<p class="blockquote text-light bg-gradient-danger">' . $error . '</p>';
                                    }
                                    if (empty($formErrors)) {
                                        $st = $con->prepare("insert into books (gu_state,bo_payed_amount,bo_date,bo_arrive_date,bo_people_no,rm_no,gu_no)
                                         values(:state,:payed,:date,:arrive,:person,:room,:guest)");
                                        if ($rm_no == 0) $rm_no = NULL;
                                        else {
                                            $st1 = $con->prepare("update rooms set rm_state=? where rm_no=?");
                                            $st1->execute(array(1, $rm_no));
                                        }
                                        if ($gu_no == 0) $gu_no = NULL;
                                        $st->execute(array(
                                            'state' => $gu_state,
                                            'payed' => $bo_payed_amount,
                                            'date' => $bo_date,
                                            'arrive' => $bo_arrive_date,
                                            'person' => $bo_people_no,
                                            'room' => $rm_no,
                                            'guest' => $gu_no
                                        ));
                                        echo '<p class="blockquote text-light bg-gradient-success">' . $st->rowCount() . ' Recored Added Successfully</p>';
                                        header("refresh:1;url=books.php");
                                    }
                                } ?>
                            </div>
                        </div>
                        <?php
                    } else if ($do == 'Edit') {
                        if (isset($_GET['ID'])) $getid = check_input($_GET['ID']);
                        else if (isset($_POST['ID'])) $getid = check_input($_POST['ID']);
                        else header("location: books.php");
                        $st = $con->prepare("select * from books where bo_no= ?");
                        $st->execute(array($getid));
                        $bo = $st->fetch();
                        $count = $st->rowCount();
                        if ($count > 0) {
                        ?>
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Edit</h4>
                                    <form class="forms-sample" action="<?php $_POST['edit_bo'] ?>" method="post">
                                        <input type="hidden" name="bo_no" value="<?php echo $bo['bo_no']; ?>">
                                        <div class="form-group">
                                            <label for="exampleSelectGender">State</label>
                                            <select name="gu_state" class="form-control" id="exampleSelectGender">
                                                <?php
                                                foreach ($Guests_states['Arrive'] as $key => $value) {
                                                    echo "<option value='" . $key . "'";
                                                    if ($bo['gu_state'] == $key) {
                                                        echo 'selected';
                                                    }
                                                    echo ">" . $value . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputUsername1">Payed Amount</label>
                                            <input type="number" name="bo_payed_amount" class="form-control" id="exampleInputUsername1" value="<?php echo $bo['bo_payed_amount']; ?>" placeholder="Book Payed Amount">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputUsername1">Date</label>
                                            <input type="date" name="bo_date" class="form-control" id="exampleInputUsername1" value="<?php echo $bo['bo_date']; ?>" placeholder="Book Date">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputUsername1">Arrived Date</label>
                                            <input type="date" name="bo_arrive_date" class="form-control" id="exampleInputUsername1" value="<?php echo $bo['bo_arrive_date']; ?>" placeholder="Book Arrived Date">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputUsername1">How many Persons</label>
                                            <input type="number" name="bo_people_no" class="form-control" id="exampleInputUsername1" value="<?php echo $bo['bo_people_no']; ?>" placeholder="Book How many Persons">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleSelectGender">Room</label>
                                            <input type="text" name="oldroom" class="form-control" id="exampleInputUsername1" value="<?php echo $bo['rm_no'] ?>" readonly>
                                            <select name="rm_no" class="form-control" id="exampleSelectGender">
                                                <option value="0">...</option>
                                                <?php
                                                $allrm = getAllFrom("*", "rooms", "", "", "rm_no");
                                                foreach ($allrm as $rm) {
                                                    echo "<option ' value='" . $rm['rm_no'] . "'";
                                                    if ($bo['rm_no'] == $rm['rm_no']) {
                                                        echo 'style="background:#000;color:#fff;" selected';
                                                    }
                                                    echo ">" . $rm['rm_no'] . "</option>";
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
                                                    if ($bo['gu_no'] == $gu['gu_no']) {
                                                        echo 'selected';
                                                    }
                                                    echo ">" . $gu['gu_name'] . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-gradient-primary me-2" name="edit_bo">Edit</button>
                                        <a href="books.php" class="btn btn-light">Cancel</a>
                                    </form>
                                    <?php
                                    if (isset($_POST['edit_bo'])) {
                                        $bo_no = check_input($_POST['bo_no']);
                                        $gu_state = check_input($_POST['gu_state']);
                                        $bo_payed_amount = check_input($_POST['bo_payed_amount']);
                                        $bo_date = check_input($_POST['bo_date']);
                                        $bo_arrive_date = check_input($_POST['bo_arrive_date']);
                                        $bo_people_no = check_input($_POST['bo_people_no']);
                                        $rm_no = check_input($_POST['rm_no']);
                                        $oldroom = check_input($_POST['oldroom']);
                                        $gu_no = check_input($_POST['gu_no']);
                                        $formErrors = array();
                                        if (empty($bo_payed_amount)) {
                                            $formErrors[] = "The Payed Amount Must be <strong> not Empty </strong>";
                                        }
                                        if (empty($bo_date)) {
                                            $formErrors[] = "The Date Must be <strong> not Empty </strong>";
                                        }
                                        if (empty($bo_arrive_date)) {
                                            $formErrors[] = "The Arrived Date Must be <strong> not Empty </strong>";
                                        }
                                        if (empty($bo_people_no)) {
                                            $formErrors[] = "The Persons Must be <strong> not Empty </strong>";
                                        }
                                        if (!filter_var($bo_people_no, FILTER_VALIDATE_INT)) {
                                            $formErrors[] = "The Persons Must be <strong> not Letters </strong>";
                                        }
                                        foreach ($formErrors as $error) {
                                            echo '<p class="blockquote text-light bg-gradient-danger">' . $error . '</p>';
                                        }
                                        if (empty($formErrors)) {
                                            $st = $con->prepare("update books set gu_state=?,bo_payed_amount=?,bo_date=?,bo_arrive_date=?,bo_people_no=?,rm_no=?,gu_no=? where bo_no=?");
                                            if ($rm_no == 0) {
                                                $rm_no = NULL;
                                                $st1 = $con->prepare("update rooms set rm_state=? where rm_no=?");
                                                $st1->execute(array(0, $oldroom));
                                            } else if ($rm_no != 0 && $rm_no != $oldroom) {
                                                $st1 = $con->prepare("update rooms set rm_state=? where rm_no=?");
                                                $st1->execute(array(1, $rm_no));
                                                $st2 = $con->prepare("update rooms set rm_state=? where rm_no=?");
                                                $st2->execute(array(0, $oldroom));
                                            }
                                            if ($gu_no == 0) $gu_no = NULL;
                                            $st->execute(array($gu_state, $bo_payed_amount, $bo_date, $bo_arrive_date, $bo_people_no, $rm_no, $gu_no, $bo_no));
                                            echo '<p class="blockquote text-light bg-gradient-success">' . $st->rowCount() . ' Recored Updated Successfully</p>';
                                            header("refresh:1;url=books.php");
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                    <?php }
                    } else if ($do == 'Delete') {
                        if (isset($_GET['ID'])) $getid = check_input($_GET['ID']);
                        else if (isset($_POST['ID'])) $getid = check_input($_POST['ID']);
                        else header("location: books.php");
                        $st = $con->prepare("select * from books where bo_no= ?");
                        $st->execute(array($getid));
                        $bo = $st->fetch();
                        $count = $st->rowCount();
                        if ($count > 0) {
                            $st = $con->prepare("delete from books where bo_no=:id");

                            $st1 = $con->prepare("update rooms set rm_state=? where rm_no=?");
                            $st1->execute(array(0, $bo['rm_no']));

                            $st->bindParam(":id", $getid);
                            $st->execute();
                            echo '<p class="blockquote text-light bg-gradient-success">' . $st->rowCount() . ' Recored Deleted Successfully</p>';
                            header("refresh:1;url=books.php");
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
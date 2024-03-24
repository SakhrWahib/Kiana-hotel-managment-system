<?php
ob_start();
session_start();
if (isset($_SESSION['username'])) {
    $pageTitle = 'Rooms';
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
                                <i class="mdi mdi-seat-flat"></i>
                            </span> Rooms
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
                        $st = $con->prepare("select * from rooms");
                        $st->execute();
                        $rms = $st->fetchAll();
                        if (!empty($rms)) {
                    ?>
                            <div class="card">
                                <div class="card-body">
                                    <?php if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'subadmin' || $_SESSION['role'] == 'subadminadd') { ?>
                                        <form class="forms-sample" action="<?php $_POST['add_new'] ?>" method="post">
                                            <button type="submit" class="btn btn-gradient-primary me-2" name="add_new">Add New Room</button>
                                        </form>
                                    <?php } ?>
                                </div>
                                <div class="card-body">
                                    <div id="printout">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th> ID </th>
                                                    <th> Floor </th>
                                                    <th> State Book </th>
                                                    <th> Control</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                foreach ($rms as $rm) {
                                                    echo '<tr>';
                                                    echo '<td class="py-1">' . $rm['rm_no'] . '</td>';
                                                    echo '<td>' . $rm['floor_no'] . '</td>';
                                                    echo '<td>';
                                                    if ($rm['rm_state'] == 0) {
                                                ?>
                                                        <button class="btn btn-gradient-danger btn-rounded btn-icon" name="edit">
                                                            <i class="mdi mdi-close"></i>
                                                        </button>
                                                    <?php
                                                    } else if ($rm['rm_state'] == 1) {
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
                                                            <input type="hidden" name="id" value="<?php echo $rm['rm_no']; ?>">
                                                            <button type="submit" class="btn btn-gradient-info btn-rounded btn-icon" name="show">
                                                                <i class="mdi mdi-eye"></i>
                                                            </button>
                                                        </form>
                                                    <?php } ?>
                                                    <?php if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'subadmin' || $_SESSION['role'] == 'subadminedit') { ?>
                                                        <form action="<?php $_POST['edit'] ?>" method="post">
                                                            <input type="hidden" name="id" value="<?php echo $rm['rm_no']; ?>">
                                                            <button type="submit" class="btn btn-gradient-success btn-rounded btn-icon" name="edit">
                                                                <i class="mdi mdi-lead-pencil"></i>
                                                            </button>
                                                        </form>
                                                    <?php } ?>
                                                    <?php if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'subadmin' || $_SESSION['role'] == 'subadmindelete') { ?>
                                                        <form action="<?php $_POST['delete'] ?>" method="post">
                                                            <input type="hidden" name="id" value="<?php echo $rm['rm_no']; ?>">
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
                                                    <td><?php echo countItems('rm_no', 'rooms') ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <button class="btn btn-gradient-primary me-2" onclick="printContent();">Print Report</button>
                                </div>
                            </div>
                            <?php
                            if (isset($_POST['show'])) {
                                header("location:rooms.php?do=Show&ID=" . check_input($_POST['id']) . "");
                            } else if (isset($_POST['edit'])) {
                                header("location:rooms.php?do=Edit&ID=" . check_input($_POST['id']) . "");
                            } else if (isset($_POST['delete'])) {
                                header("location:rooms.php?do=Delete&ID=" . check_input($_POST['id']) . "");
                            } else if (isset($_POST['add_new'])) {
                                header("location:rooms.php?do=Add");
                            }
                        } else { ?>
                            <div class="card">
                                <div class="card-body">
                                    <p class="blockquote text-light bg-gradient-info"> Sorry ! There is No Room Please Add First Room </p>
                                    <form class="forms-sample" action="<?php $_POST['add_first'] ?>" method="post">
                                        <button type="submit" class="btn btn-gradient-primary me-2" name="add_first">Add First Room</button>
                                    </form>
                                </div>
                            </div>
                            <?php
                            if (isset($_POST['add_first'])) {
                                header("location:rooms.php?do=Add");
                            }
                        }
                    } else if ($do == 'Show') {
                        if (isset($_GET['ID'])) $getid = check_input($_GET['ID']);
                        else if (isset($_POST['ID'])) $getid = check_input($_POST['ID']);
                        else header("location: rooms.php");
                        $st = $con->prepare("select * from rooms where rm_no= ?");
                        $st->execute(array($getid));
                        $rm = $st->fetch();
                        $count = $st->rowCount();
                        if ($count > 0) {
                            ?>
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Show Details</h4>
                                    <div id="printout">
                                        <form class="forms-sample" method="post">
                                            <div class="col-6 pe-1">
                                                <img src="<?php
                                                            if ($rm['rm_img'] == NULL)
                                                                echo "img.png";
                                                            else echo "upload\\rooms\\" . $rm['rm_img'];
                                                            ?>" class="mw-100 w-100 rounded" alt="image">
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputUsername1">ID</label>
                                                <input type="text" name="rm_no" class="form-control" id="exampleInputUsername1" value="<?php echo $rm['rm_no'] ?>" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputUsername1">Floor</label>
                                                <input type="text" name="floor_no" class="form-control" id="exampleInputUsername1" value="<?php echo $rm['floor_no'] ?>" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputUsername1">State</label>
                                                <input type="text" name="rm_state" class="form-control" id="exampleInputUsername1" value="<?php foreach ($Bookss['Book'] as $key => $value) {
                                                                                                                                                if ($rm['rm_state'] == $key)
                                                                                                                                                    echo $value;
                                                                                                                                            } ?>" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputUsername1">Capacity</label>
                                                <input type="text" name="rm_people_capacity" class="form-control" id="exampleInputUsername1" value="<?php echo $rm['rm_people_capacity'] ?>" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputUsername1">Type</label>
                                                <input type="text" name="rm_type_no" class="form-control" id="exampleInputUsername1" value="<?php
                                                                                                                                            $allrm_typs = getAllFrom("*", "rooms_type", "", "", "rm_type_no");
                                                                                                                                            foreach ($allrm_typs as $rm_t) {
                                                                                                                                                if ($rm['rm_type_no'] == $rm_t['rm_type_no'])
                                                                                                                                                    echo $rm_t['rm_type_name'];
                                                                                                                                                else echo NULL;
                                                                                                                                            } ?>" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputUsername1">Cost</label>
                                                <input type="text" name="rm_cost" class="form-control" id="exampleInputUsername1" value="<?php echo $rm['rm_cost'] ?>" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleTextarea1">Details</label>
                                                <textarea name="rm_details" class="form-control" id="exampleTextarea1" rows="4" readonly><?php echo $rm['rm_details'] ?></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputUsername1">Rate</label>
                                                <input type="text" name="rm_rate" class="form-control" id="exampleInputUsername1" value="<?php echo $rm['rm_rate'] ?>" readonly>
                                            </div>
                                            <a href="rooms.php" class="btn btn-light">Cancel</a>
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
                                <form class="forms-sample" action="<?php $_POST['add_rm'] ?>" method="post" enctype="multipart/form-data">
                                    <div class="col-6 pe-1">
                                        <img src="" class="mw-100 w-100 rounded" alt="image">
                                        <input type="file" name="rm_img" class="fl" id="fl" style="display: none;">
                                        <label for="fl" class="btn btn-gradient-primary me-2">Choose an Image</label>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleSelectGender">Floor</label>
                                        <select name="floor_no" class="form-control" id="exampleSelectGender">
                                            <?php
                                            foreach ($Floors['Floor'] as $key => $value) {
                                                echo "<option value='" . $key . "'>" . $value . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleSelectGender">State</label>
                                        <select name="rm_state" class="form-control" id="exampleSelectGender">
                                            <?php
                                            foreach ($Bookss['Book'] as $key => $value) {
                                                echo "<option value='" . $key . "'>" . $value . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputUsername1">Capacity</label>
                                        <input type="number" name="rm_people_capacity" class="form-control" id="exampleInputUsername1" placeholder="Room Capacity">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleSelectGender">Type</label>
                                        <select name="rm_type_no" class="form-control" id="exampleSelectGender">
                                            <option value="0">...</option>
                                            <?php
                                            $allrm_typs = getAllFrom("*", "rooms_type", "", "", "rm_type_no");
                                            foreach ($allrm_typs as $rm_t) {
                                                echo "<option value='" . $rm_t['rm_type_no'] . "'>" . $rm_t['rm_type_name'] . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputUsername1">Cost</label>
                                        <input type="text" name="rm_cost" class="form-control" id="exampleInputUsername1" placeholder="Room Cost">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleTextarea1">Details</label>
                                        <textarea name="rm_details" class="form-control" id="exampleTextarea1" rows="4" placeholder="Room Details"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputUsername1">Rate</label>
                                        <select name="rm_rate" class="form-control" id="exampleSelectGender">
                                            <?php
                                            foreach ($Stars['Star'] as $key => $value) {
                                                echo "<option value='" . $key . "'>" . $value . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-gradient-primary me-2" name="add_rm">Add</button>
                                    <a href="rooms.php" class="btn btn-light">Cancel</a>
                                </form>
                                <?php
                                if (isset($_POST['add_rm'])) {
                                    $floor_no = check_input($_POST['floor_no']);
                                    $rm_state = check_input($_POST['rm_state']);
                                    $rm_people_capacity = check_input($_POST['rm_people_capacity']);
                                    $rm_type_no = check_input($_POST['rm_type_no']);
                                    $rm_cost = check_input($_POST['rm_cost']);
                                    $rm_details = check_input($_POST['rm_details']);
                                    $rm_rate = check_input($_POST['rm_rate']);
                                    $rm_imgName = $_FILES['rm_img']['name'];
                                    $rm_imgSize = $_FILES['rm_img']['size'];
                                    $rm_imgTmp  = $_FILES['rm_img']['tmp_name'];
                                    $rm_imgype = $_FILES['rm_img']['type'];

                                    $rm_imgAllowedExtension = array("jpeg", "jpg", "png", "gif");

                                    $rm_imgExtension = strtolower(end(explode('.', $rm_imgName)));
                                    $formErrors = array();
                                    if (empty($rm_people_capacity)) {
                                        $formErrors[] = "The Capacity Must be <strong> not Empty </strong>";
                                    }
                                    if (!filter_var($rm_people_capacity, FILTER_VALIDATE_INT)) {
                                        $formErrors[] = "The Capacity Must be <strong> not Letters </strong>";
                                    }
                                    if (empty($rm_cost)) {
                                        $formErrors[] = "The Cost Must be <strong> not Empty </strong>";
                                    }
                                    if (!filter_var($rm_cost, FILTER_VALIDATE_INT)) {
                                        $formErrors[] = "The Cost Must be <strong> not Letters </strong>";
                                    }
                                    if (empty($rm_details)) {
                                        $formErrors[] = "The Details Must be <strong> not Empty </strong>";
                                    }
                                    if (!empty($rm_imgName) && !in_array($rm_imgExtension, $rm_imgAllowedExtension)) {
                                        $formErrors[] = 'The Extension of File <strong>Not Allowed</strong>';
                                    }
                                    if (empty($rm_imgName)) {
                                        $formErrors[] = 'The Image <strong>is Required</strong>';
                                    }

                                    if ($rm_imgSize > 4194304) {
                                        $formErrors[] = 'The Image Must be Not Larger <strong>4MB</strong>';
                                    }
                                    foreach ($formErrors as $error) {
                                        echo '<p class="blockquote text-light bg-gradient-danger">' . $error . '</p>';
                                    }
                                    if (empty($formErrors)) {
                                        $rm_img = rand(0, 10000000000) . '_' . $rm_imgName;
                                        move_uploaded_file($rm_imgTmp, "upload\\rooms\\" . $rm_img);
                                        $st = $con->prepare("insert into rooms (floor_no,rm_state,rm_people_capacity,rm_type_no,rm_cost,rm_details,	rm_rate	,rm_img)
                                        values(:floor,:state,:capacity,:type,:cost,:details,:rate,:img)");
                                        if ($rm_type_no == 0) $rm_type_no = NULL;
                                        $st->execute(array(
                                            'floor' => $floor_no,
                                            'state' => $rm_state,
                                            'capacity' => $rm_people_capacity,
                                            'type' => $rm_type_no,
                                            'cost' => $rm_cost,
                                            'details' => $rm_details,
                                            'rate' => $rm_rate,
                                            'img' => $rm_img
                                        ));
                                        echo '<p class="blockquote text-light bg-gradient-success">' . $st->rowCount() . ' Recored Added Successfully</p>';
                                        header("refresh:1;url=rooms.php");
                                    }
                                } ?>
                            </div>
                        </div>
                        <?php
                    } else if ($do == 'Edit') {
                        if (isset($_GET['ID'])) $getid = check_input($_GET['ID']);
                        else if (isset($_POST['ID'])) $getid = check_input($_POST['ID']);
                        else header("location: rooms.php");
                        $st = $con->prepare("select * from rooms where rm_no= ?");
                        $st->execute(array($getid));
                        $rm = $st->fetch();
                        $count = $st->rowCount();
                        if ($count > 0) {
                        ?>
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Edit</h4>
                                    <form class="forms-sample" action="<?php $_POST['edit_rm'] ?>" method="post" enctype="multipart/form-data">
                                        <input type="hidden" name="rm_no" value="<?php echo $rm['rm_no']; ?>">
                                        <div class="col-6 pe-1">
                                            <input type="hidden" name="oldimg" value="<?php echo $rm['rm_img'] ?>">
                                            <img src="<?php
                                                        if ($rm['rm_img'] == NULL)
                                                            echo "img.png";
                                                        else echo "upload\\rooms\\" . $rm['rm_img'];
                                                        ?>" class="mw-100 w-100 rounded" alt="image">
                                            <input type="file" name="rm_img" class="fl" id="fl" style="display: none;">
                                            <label for="fl" class="btn btn-gradient-primary me-2">Choose an Image</label>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleSelectGender">Floor</label>
                                            <select name="floor_no" class="form-control" id="exampleSelectGender">
                                                <?php
                                                foreach ($Floors['Floor'] as $key => $value) {
                                                    echo "<option value='" . $key . "'";
                                                    if ($rm['floor_no'] == $key) {
                                                        echo 'selected';
                                                    }
                                                    echo ">" . $value . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleSelectGender">State</label>
                                            <select name="rm_state" class="form-control" id="exampleSelectGender">
                                                <?php
                                                foreach ($Bookss['Book'] as $key => $value) {
                                                    echo "<option value='" . $key . "'";
                                                    if ($rm['rm_state'] == $key) {
                                                        echo 'selected';
                                                    }
                                                    echo ">" . $value . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputUsername1">Capacity</label>
                                            <input type="number" name="rm_people_capacity" class="form-control" id="exampleInputUsername1" value="<?php echo $rm['rm_people_capacity']; ?>" placeholder="Room Capacity">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleSelectGender">Type</label>
                                            <select name="rm_type_no" class="form-control" id="exampleSelectGender">
                                                <option value="0">...</option>
                                                <?php
                                                $allrm_typs = getAllFrom("*", "rooms_type", "", "", "rm_type_no");
                                                foreach ($allrm_typs as $rm_t) {
                                                    echo "<option value='" . $rm_t['rm_type_no'] . "'";
                                                    if ($rm['rm_type_no'] == $rm_t['rm_type_no']) {
                                                        echo 'selected';
                                                    }
                                                    echo ">" . $rm_t['rm_type_name'] . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputUsername1">Cost</label>
                                            <input type="text" name="rm_cost" class="form-control" id="exampleInputUsername1" value="<?php echo $rm['rm_cost']; ?>" placeholder="Room Cost">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleTextarea1">Details</label>
                                            <textarea name="rm_details" class="form-control" id="exampleTextarea1" rows="4" placeholder="Room Details"><?php echo $rm['rm_details']; ?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputUsername1">Rate</label>
                                            <select name="rm_rate" class="form-control" id="exampleSelectGender">
                                                <?php                                                
                                                foreach ($Stars['Star'] as $key => $value) {
                                                    echo "<option value='" . $key . "'";
                                                    if ($rm['rm_rate'] == $key) {
                                                        echo 'selected';
                                                    }
                                                    echo ">" . $value . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-gradient-primary me-2" name="edit_rm">Edit</button>
                                        <a href="rooms.php" class="btn btn-light">Cancel</a>
                                    </form>
                                    <?php
                                    if (isset($_POST['edit_rm'])) {
                                        $rm_no = check_input($_POST['rm_no']);
                                        $floor_no = check_input($_POST['floor_no']);
                                        $rm_state = check_input($_POST['rm_state']);
                                        $rm_people_capacity = check_input($_POST['rm_people_capacity']);
                                        $rm_type_no = check_input($_POST['rm_type_no']);
                                        $rm_cost = check_input($_POST['rm_cost']);
                                        $rm_details = check_input($_POST['rm_details']);
                                        $rm_rate = check_input($_POST['rm_rate']);
                                        $rm_imgName = $_FILES['rm_img']['name'];
                                        $rm_imgSize = $_FILES['rm_img']['size'];
                                        $rm_imgTmp  = $_FILES['rm_img']['tmp_name'];
                                        $rm_imgype = $_FILES['rm_img']['type'];

                                        $rm_imgAllowedExtension = array("jpeg", "jpg", "png", "gif");

                                        $rm_imgExtension = strtolower(end(explode('.', $rm_imgName)));
                                        $formErrors = array();
                                        if (empty($rm_people_capacity)) {
                                            $formErrors[] = "The Capacity Must be <strong> not Empty </strong>";
                                        }
                                        if (!filter_var($rm_people_capacity, FILTER_VALIDATE_INT)) {
                                            $formErrors[] = "The Capacity Must be <strong> not Letters </strong>";
                                        }
                                        if (empty($rm_cost)) {
                                            $formErrors[] = "The Cost Must be <strong> not Empty </strong>";
                                        }
                                        if (!filter_var($rm_cost, FILTER_VALIDATE_INT)) {
                                            $formErrors[] = "The Cost Must be <strong> not Letters </strong>";
                                        }
                                        if (empty($rm_details)) {
                                            $formErrors[] = "The Details Must be <strong> not Empty </strong>";
                                        }
                                        if (!empty($rm_imgName) && !in_array($rm_imgExtension, $rm_imgAllowedExtension)) {
                                            $formErrors[] = 'The Extension of File <strong>Not Allowed</strong>';
                                        }
                                        if ($rm_imgSize > 4194304) {
                                            $formErrors[] = 'The Image Must be Not Larger <strong>4MB</strong>';
                                        }
                                        foreach ($formErrors as $error) {
                                            echo '<p class="blockquote text-light bg-gradient-danger">' . $error . '</p>';
                                        }
                                        if (empty($formErrors)) {
                                            if (empty($rm_imgName)) {
                                                $rm_img = $_POST['oldimg'];
                                            } else {
                                                $rm_img = rand(0, 10000000000) . '_' . $rm_imgName;
                                                unlink("upload\\rooms\\" . $_POST['oldimg']);
                                                move_uploaded_file($rm_imgTmp, "upload\\rooms\\" . $rm_img);
                                            }
                                            $st = $con->prepare("update rooms set floor_no=?,rm_state=?,rm_people_capacity=?,rm_type_no=?,rm_cost=?,rm_details=?,rm_rate=?,rm_img=? where rm_no=?");
                                            if ($rm_type_no == 0) $rm_type_no = NULL;
                                            $st->execute(array($floor_no, $rm_state, $rm_people_capacity, $rm_type_no, $rm_cost,$rm_details,$rm_rate,$rm_img, $rm_no));
                                            echo '<p class="blockquote text-light bg-gradient-success">' . $st->rowCount() . ' Recored Updated Successfully</p>';
                                            header("refresh:1;url=rooms.php");
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                    <?php }
                    } else if ($do == 'Delete') {
                        if (isset($_GET['ID'])) $getid = check_input($_GET['ID']);
                        else if (isset($_POST['ID'])) $getid = check_input($_POST['ID']);
                        else header("location: rooms.php");
                        $st = $con->prepare("select * from rooms where rm_no= ?");
                        $st->execute(array($getid));
                        $rm = $st->fetch();
                        $count = $st->rowCount();
                        if ($count > 0) {
                            $st = $con->prepare("delete from rooms where rm_no=:id");
                            $st->bindParam(":id", $getid);
                            $st->execute();
                            unlink("upload\\rooms\\" . $rm['rm_img']);
                            echo '<p class="blockquote text-light bg-gradient-success">' . $st->rowCount() . ' Recored Deleted Successfully</p>';
                            header("refresh:1;url=rooms.php");
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
        const img = document.querySelector(".mw-100");
        const fl = document.querySelector(".fl");
        fl.addEventListener("change", function() {
            const chooseFile = this.files[0];
            if (chooseFile) {
                const reader = new FileReader();
                reader.addEventListener("load", function() {

                    img.setAttribute('src', reader.result);
                });
                reader.readAsDataURL(chooseFile);
            }
        });
    </script>
<?php include $tpl . 'footer.php';
} else {
    header('Location: index.php');
    exit();
}
ob_end_flush();
?>
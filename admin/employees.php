<?php
ob_start();
session_start();
if (isset($_SESSION['username']) && $_SESSION['role'] == 'admin') {
    $pageTitle = 'Employees';
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
                                <i class="mdi mdi-worker"></i>
                            </span> Employees
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
                        $st = $con->prepare("select * from employees");
                        $st->execute();
                        $emps = $st->fetchAll();
                        if (!empty($emps)) {
                    ?>
                            <div class="card">
                                <div class="card-body">
                                    <?php if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'subadmin' || $_SESSION['role'] == 'subadminadd') { ?>
                                        <form class="forms-sample" action="<?php $_POST['add_new'] ?>" method="post">
                                            <button type="submit" class="btn btn-gradient-primary me-2" name="add_new">Add New employee</button>
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
                                                foreach ($emps as $emp) {
                                                    echo '<tr>';
                                                    echo '<td class="py-1">' . $emp['emp_no'] . '</td>';
                                                    echo '<td>' . $emp['emp_name'] . '</td>';
                                                    echo "<td> ";
                                                ?>
                                                    <?php if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'subadmin' || $_SESSION['role'] == 'subadminshow') { ?>
                                                        <form action="<?php $_POST['show'] ?>" method="post">
                                                            <input type="hidden" name="id" value="<?php echo $emp['emp_no']; ?>">
                                                            <button type="submit" class="btn btn-gradient-info btn-rounded btn-icon" name="show">
                                                                <i class="mdi mdi-eye"></i>
                                                            </button>
                                                        </form>
                                                    <?php } ?>
                                                    <?php if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'subadmin' || $_SESSION['role'] == 'subadminedit') { ?>
                                                        <form action="<?php $_POST['edit'] ?>" method="post">
                                                            <input type="hidden" name="id" value="<?php echo $emp['emp_no']; ?>">
                                                            <button type="submit" class="btn btn-gradient-success btn-rounded btn-icon" name="edit">
                                                                <i class="mdi mdi-lead-pencil"></i>
                                                            </button>
                                                        </form>
                                                    <?php } ?>
                                                    <?php if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'subadmin' || $_SESSION['role'] == 'subadmindelete') { ?>
                                                        <form action="<?php $_POST['delete'] ?>" method="post">
                                                            <input type="hidden" name="id" value="<?php echo $emp['emp_no']; ?>">
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
                                                    <td><?php echo countItems('emp_no', 'employees') ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <button class="btn btn-gradient-primary me-2" onclick="printContent();">Print Report</button>
                                </div>
                            </div>
                            <?php
                            if (isset($_POST['show'])) {
                                header("location:employees.php?do=Show&ID=" . check_input($_POST['id']) . "");
                            } else if (isset($_POST['edit'])) {
                                header("location:employees.php?do=Edit&ID=" . check_input($_POST['id']) . "");
                            } else if (isset($_POST['delete'])) {
                                header("location:employees.php?do=Delete&ID=" . check_input($_POST['id']) . "");
                            } else if (isset($_POST['add_new'])) {
                                header("location:employees.php?do=Add");
                            }
                        } else { ?>
                            <div class="card">
                                <div class="card-body">
                                    <p class="blockquote text-light bg-gradient-info"> Sorry ! There is No employees Please Add First employee </p>
                                    <form class="forms-sample" action="<?php $_POST['add_first'] ?>" method="post">
                                        <button type="submit" class="btn btn-gradient-primary me-2" name="add_first">Add First employee</button>
                                    </form>
                                </div>
                            </div>
                            <?php
                            if (isset($_POST['add_first'])) {
                                header("location:employees.php?do=Add");
                            }
                        }
                    } else if ($do == 'Show') {
                        if (isset($_GET['ID'])) $getid = check_input($_GET['ID']);
                        else if (isset($_POST['ID'])) $getid = check_input($_POST['ID']);
                        else header("location: employees.php");
                        $st = $con->prepare("select * from employees where emp_no= ?");
                        $st->execute(array($getid));
                        $emp = $st->fetch();
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
                                                <input type="text" name="emp_no" class="form-control" id="exampleInputUsername1" value="<?php echo $emp['emp_no'] ?>" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputUsername1">Name</label>
                                                <input type="text" name="emp_name" class="form-control" id="exampleInputUsername1" value="<?php echo $emp['emp_name'] ?>" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputUsername1">ID Card</label>
                                                <input type="text" name="emp_id_card" class="form-control" id="exampleInputUsername1" value="<?php echo $emp['emp_id_card'] ?>" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputUsername1">Email</label>
                                                <input type="text" name="emp_email" class="form-control" id="exampleInputUsername1" value="<?php echo $emp['emp_email'] ?>" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputUsername1">Gender</label>
                                                <input type="text" name="gender" class="form-control" id="exampleInputUsername1" value="<?php echo $emp['gender'] ?>" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputUsername1">Birth Date</label>
                                                <input type="text" name="emp_br_date" class="form-control" id="exampleInputUsername1" value="<?php echo $emp['emp_br_date'] ?>" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputUsername1">Age</label>
                                                <input type="text" name="emp_age" class="form-control" id="exampleInputUsername1" value="<?php echo $emp['emp_age'] ?>" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputUsername1">Hire Date</label>
                                                <input type="text" name="emp_hr_date" class="form-control" id="exampleInputUsername1" value="<?php echo $emp['emp_hr_date'] ?>" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputUsername1">Phone</label>
                                                <input type="text" name="emp_phone" class="form-control" id="exampleInputUsername1" value="<?php echo $emp['emp_phone'] ?>" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputUsername1">Address</label>
                                                <input type="text" name="emp_address" class="form-control" id="exampleInputUsername1" value="<?php echo $emp['emp_address'] ?>" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputUsername1">Salary</label>
                                                <input type="text" name="salary" class="form-control" id="exampleInputUsername1" value="<?php echo $emp['salary'] ?>" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputUsername1">Work Hours</label>
                                                <input type="text" name="emp_hours_work" class="form-control" id="exampleInputUsername1" value="<?php echo $emp['emp_hours_work'] ?>" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputUsername1">Department</label>
                                                <input type="text" name="dept_no" class="form-control" id="exampleInputUsername1" value="<?php
                                                                                                                                            $alldepts = getAllFrom("*", "department", "", "", "dept_no");
                                                                                                                                            foreach ($alldepts as $dept) {
                                                                                                                                                if ($emp['dept_no'] == $dept['dept_no'])
                                                                                                                                                    echo $dept['dept_name'];
                                                                                                                                                else echo NULL;
                                                                                                                                            }
                                                                                                                                            ?>" readonly>
                                            </div>
                                            <a href="employees.php" class="btn btn-light">Cancel</a>
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
                                <form class="forms-sample" action="<?php $_POST['add_emp'] ?>" method="post">
                                    <div class="form-group">
                                        <label for="exampleInputUsername1">Name</label>
                                        <input type="text" name="emp_name" class="form-control" id="exampleInputUsername1" placeholder="Employee Name">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputUsername1">ID Card</label>
                                        <input type="text" name="emp_id_card" class="form-control" id="exampleInputUsername1" placeholder="Employee ID Card">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputUsername1">Email</label>
                                        <input type="text" name="emp_email" class="form-control" id="exampleInputUsername1" placeholder="Employee Email">
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
                                        <label for="exampleInputUsername1">Birth Date</label>
                                        <input type="date" name="emp_br_date" class="form-control" id="exampleInputUsername1">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputUsername1">Phone</label>
                                        <input type="text" name="emp_phone" class="form-control" id="exampleInputUsername1" placeholder="Employee Phone">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputUsername1">Address</label>
                                        <input type="text" name="emp_address" class="form-control" id="exampleInputUsername1" placeholder="Employee Address">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputUsername1">Salary</label>
                                        <input type="text" name="salary" class="form-control" id="exampleInputUsername1" placeholder="Employee Salary">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputUsername1">Work Hours</label>
                                        <input type="text" name="emp_hours_work" class="form-control" id="exampleInputUsername1" placeholder="Employee Work Hours">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleSelectGender">Department</label>
                                        <select name="dept_no" class="form-control" id="exampleSelectGender">
                                            <option value="0">...</option>
                                            <?php
                                            $alldepts = getAllFrom("*", "department", "", "", "dept_no");
                                            foreach ($alldepts as $dept) {
                                                echo "<option value='" . $dept['dept_no'] . "'>" . $dept['dept_name'] . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-gradient-primary me-2" name="add_emp">Add</button>
                                    <a href="employees.php" class="btn btn-light">Cancel</a>
                                </form>
                                <?php
                                if (isset($_POST['add_emp'])) {
                                    $emp_name = check_input($_POST['emp_name']);
                                    $emp_id_card = check_input($_POST['emp_id_card']);
                                    $emp_email = check_input($_POST['emp_email']);
                                    $gender = check_input($_POST['gender']);
                                    $emp_br_date = check_input($_POST['emp_br_date']);
                                    $emp_age = getAge($emp_br_date);
                                    $emp_phone = check_input($_POST['emp_phone']);
                                    $emp_address = check_input($_POST['emp_address']);
                                    $salary = check_input($_POST['salary']);
                                    $emp_hours_work = check_input($_POST['emp_hours_work']);
                                    $dept_no = check_input($_POST['dept_no']);
                                    $formErrors = array();
                                    if (empty($emp_name)) {
                                        $formErrors[] = "The Name Must be <strong> not Empty </strong>";
                                    }
                                    if (empty($emp_id_card)) {
                                        $formErrors[] = "The ID Card Must be <strong> not Empty </strong>";
                                    }
                                    if (!filter_var($emp_id_card, FILTER_VALIDATE_INT)) {
                                        $formErrors[] = "The ID Card Must be <strong> not Letters </strong>";
                                    }
                                    if (empty($emp_email)) {
                                        $formErrors[] = "The Email Must be <strong> not Empty </strong>";
                                    }
                                    if (!filter_var($emp_email, FILTER_VALIDATE_EMAIL)) {
                                        $formErrors[] = "The Email is <strong> Invalid </strong> Check It";
                                    }
                                    if (empty($emp_phone)) {
                                        $formErrors[] = "The Phone Must be <strong> not Empty </strong>";
                                    }
                                    if (!filter_var($emp_phone, FILTER_VALIDATE_INT)) {
                                        $formErrors[] = "The Phone Must be <strong> not Letters </strong>";
                                    }
                                    if (empty($emp_address)) {
                                        $formErrors[] = "The Address Must be <strong> not Empty </strong>";
                                    }
                                    if (empty($salary)) {
                                        $formErrors[] = "The Salary Must be <strong> not Empty </strong>";
                                    }
                                    if (!filter_var($salary, FILTER_VALIDATE_INT)) {
                                        $formErrors[] = "The Salary Must be <strong> not Letters </strong>";
                                    }
                                    if (empty($emp_hours_work)) {
                                        $formErrors[] = "The Hours Work Must be <strong> not Empty </strong>";
                                    }
                                    if (!filter_var($emp_hours_work, FILTER_VALIDATE_INT)) {
                                        $formErrors[] = "The Hours Work Must be <strong> not Letters </strong>";
                                    }
                                    $check = checkItem("emp_name", "employees", $emp_name);
                                    if ($check == 1) {
                                        $formErrors[] = 'Sorry This User is<strong>Exist</strong>';
                                    }
                                    foreach ($formErrors as $error) {
                                        echo '<p class="blockquote text-light bg-gradient-danger">' . $error . '</p>';
                                    }
                                    if (empty($formErrors)) {
                                        $st = $con->prepare("insert into employees 
                                        (emp_name,emp_id_card,emp_email,gender,emp_br_date,emp_age,emp_hr_date,emp_phone,emp_address,salary,emp_hours_work,dept_no) 
                                        values(:name,:c_id,:email,:gender,:birh,:age,now(),:phone,:address,:salary,:h_work,:dept)");
                                        if ($dept_no == 0) $dept_no = NULL;
                                        $st->execute(array(
                                            'name' => $emp_name,
                                            'c_id' => $emp_id_card,
                                            'email' => $emp_email,
                                            'gender' => $gender,
                                            'birh' => $emp_br_date,
                                            'age' => $emp_age,
                                            'phone' => $emp_phone,
                                            'address' => $emp_address,
                                            'salary' => $salary,
                                            'h_work' => $emp_hours_work,
                                            'dept' => $dept_no
                                        ));
                                        echo '<p class="blockquote text-light bg-gradient-success">' . $st->rowCount() . ' Recored Added Successfully</p>';
                                        header("refresh:1;url=employees.php");
                                    }
                                } ?>
                            </div>
                        </div>
                        <?php
                    } else if ($do == 'Edit') {
                        if (isset($_GET['ID'])) $getid = check_input($_GET['ID']);
                        else if (isset($_POST['ID'])) $getid = check_input($_POST['ID']);
                        else header("location: employees.php");
                        $st = $con->prepare("select * from employees where emp_no= ?");
                        $st->execute(array($getid));
                        $emp = $st->fetch();
                        $count = $st->rowCount();
                        if ($count > 0) {
                        ?>
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Edit</h4>
                                    <form class="forms-sample" action="<?php $_POST['edit_emp'] ?>" method="post">
                                        <input type="hidden" name="emp_no" value="<?php echo $emp['emp_no']; ?>">
                                        <div class="form-group">
                                            <label for="exampleInputUsername1">Name</label>
                                            <input type="hidden" name="oldname" value="<?php echo $emp['emp_name']; ?>">
                                            <input type="text" name="emp_name" class="form-control" id="exampleInputUsername1" value="<?php echo $emp['emp_name']; ?>" placeholder="Employee Name">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputUsername1">ID Card</label>
                                            <input type="text" name="emp_id_card" class="form-control" id="exampleInputUsername1" value="<?php echo $emp['emp_id_card']; ?>" placeholder="Employee ID Card">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputUsername1">Email</label>
                                            <input type="text" name="emp_email" class="form-control" id="exampleInputUsername1" value="<?php echo $emp['emp_email']; ?>" placeholder="Employee Email">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleSelectGender">Gender</label>
                                            <select name="gender" class="form-control" id="exampleSelectGender">
                                                <?php
                                                foreach ($genders['gender'] as $gen) {
                                                    echo "<option value='" . $gen . "'";
                                                    if ($emp['gender'] == $gen) {
                                                        echo 'selected';
                                                    }
                                                    echo ">" . $gen . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputUsername1">Birth Date</label>
                                            <input type="date" name="emp_br_date" class="form-control" value="<?php echo $emp['emp_br_date']; ?>" id="exampleInputUsername1">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputUsername1">Phone</label>
                                            <input type="text" name="emp_phone" class="form-control" id="exampleInputUsername1" value="<?php echo $emp['emp_phone']; ?>" placeholder="Employee Phone">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputUsername1">Address</label>
                                            <input type="text" name="emp_address" class="form-control" id="exampleInputUsername1" value="<?php echo $emp['emp_address']; ?>" placeholder="Employee Address">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputUsername1">Salary</label>
                                            <input type="text" name="salary" class="form-control" id="exampleInputUsername1" value="<?php echo $emp['salary']; ?>" placeholder="Employee Salary">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputUsername1">Work Hours</label>
                                            <input type="text" name="emp_hours_work" class="form-control" id="exampleInputUsername1" value="<?php echo $emp['emp_hours_work']; ?>" placeholder="Employee Work Hours">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleSelectGender">Department</label>
                                            <select name="dept_no" class="form-control" id="exampleSelectGender">
                                                <option value="0">...</option>
                                                <?php
                                                $alldepts = getAllFrom("*", "department", "", "", "dept_no");
                                                foreach ($alldepts as $dept) {
                                                    echo "<option value='" . $dept['dept_no'] . "'";
                                                    if ($emp['dept_no'] == $dept['dept_no']) {
                                                        echo 'selected';
                                                    }
                                                    echo ">" . $dept['dept_name'] . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-gradient-primary me-2" name="edit_emp">Edit</button>
                                        <a href="employees.php" class="btn btn-light">Cancel</a>
                                    </form>
                                    <?php
                                    if (isset($_POST['edit_emp'])) {
                                        $emp_no = check_input($_POST['emp_no']);
                                        $emp_name = check_input($_POST['emp_name']);
                                        $oldname = check_input($_POST['oldname']);
                                        $emp_id_card = check_input($_POST['emp_id_card']);
                                        $emp_email = check_input($_POST['emp_email']);
                                        $gender = check_input($_POST['gender']);
                                        $emp_br_date = check_input($_POST['emp_br_date']);
                                        $emp_age = getAge($emp_br_date);
                                        $emp_phone = check_input($_POST['emp_phone']);
                                        $emp_address = check_input($_POST['emp_address']);
                                        $salary = check_input($_POST['salary']);
                                        $emp_hours_work = check_input($_POST['emp_hours_work']);
                                        $dept_no = check_input($_POST['dept_no']);
                                        $formErrors = array();
                                        if (empty($emp_name)) {
                                            $formErrors[] = "The Name Must be <strong> not Empty </strong>";
                                        }
                                        if (empty($emp_id_card)) {
                                            $formErrors[] = "The ID Card Must be <strong> not Empty </strong>";
                                        }
                                        if (!filter_var($emp_id_card, FILTER_VALIDATE_INT)) {
                                            $formErrors[] = "The ID Card Must be <strong> not Letters </strong>";
                                        }
                                        if (empty($emp_email)) {
                                            $formErrors[] = "The Email Must be <strong> not Empty </strong>";
                                        }
                                        if (!filter_var($emp_email, FILTER_VALIDATE_EMAIL)) {
                                            $formErrors[] = "The Email is <strong> Invalid </strong> Check It";
                                        }
                                        if (empty($emp_phone)) {
                                            $formErrors[] = "The Phone Must be <strong> not Empty </strong>";
                                        }
                                        if (!filter_var($emp_phone, FILTER_VALIDATE_INT)) {
                                            $formErrors[] = "The Phone Must be <strong> not Letters </strong>";
                                        }
                                        if (empty($emp_address)) {
                                            $formErrors[] = "The Address Must be <strong> not Empty </strong>";
                                        }
                                        if (empty($salary)) {
                                            $formErrors[] = "The Salary Must be <strong> not Empty </strong>";
                                        }
                                        if (!filter_var($salary, FILTER_VALIDATE_INT)) {
                                            $formErrors[] = "The Salary Must be <strong> not Letters </strong>";
                                        }
                                        if (empty($emp_hours_work)) {
                                            $formErrors[] = "The Hours Work Must be <strong> not Empty </strong>";
                                        }
                                        if (!filter_var($emp_hours_work, FILTER_VALIDATE_INT)) {
                                            $formErrors[] = "The Hours Work Must be <strong> not Letters </strong>";
                                        }
                                        if ($emp_name == $oldname) {
                                            $name = $oldname;
                                        } else {
                                            $name = $emp_name;
                                            $check = checkItem("emp_name", "employees", $name);
                                            if ($check == 1) {
                                                $formErrors[] = 'Sorry This User is<strong>Exist</strong>';
                                            }
                                        }
                                        foreach ($formErrors as $error) {
                                            echo '<p class="blockquote text-light bg-gradient-danger">' . $error . '</p>';
                                        }
                                        if (empty($formErrors)) {
                                            $st = $con->prepare("update employees set
                                             emp_name=? ,emp_id_card=?,emp_email = ?,gender = ?,emp_br_date = ?,emp_age = ?,emp_phone = ?,emp_address = ?,salary = ?,emp_hours_work = ?,dept_no = ?
                                             where emp_no=?");
                                            if ($dept_no == 0) $dept_no = NULL;
                                            $st->execute(array(
                                                $name, $emp_id_card, $emp_email, $gender, $emp_br_date,
                                                $emp_age, $emp_phone, $emp_address, $salary, $emp_hours_work,
                                                $dept_no, $emp_no
                                            ));
                                            echo '<p class="blockquote text-light bg-gradient-success">' . $st->rowCount() . ' Recored Updated Successfully</p>';
                                            header("refresh:1;url=employees.php");
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                    <?php }
                    } else if ($do == 'Delete') {
                        if (isset($_GET['ID'])) $getid = check_input($_GET['ID']);
                        else if (isset($_POST['ID'])) $getid = check_input($_POST['ID']);
                        else header("location: employees.php");
                        $st = $con->prepare("select * from employees where emp_no= ?");
                        $st->execute(array($getid));
                        $emp = $st->fetch();
                        $count = $st->rowCount();
                        if ($count > 0) {
                            $st = $con->prepare("delete from employees where emp_no=:id");
                            $st->bindParam(":id", $getid);
                            $st->execute();
                            echo '<p class="blockquote text-light bg-gradient-success">' . $st->rowCount() . ' Recored Deleted Successfully</p>';
                            header("refresh:1;url=employees.php");
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
<?php
ob_start();
session_start();
if (isset($_SESSION['username'])) {
  $pageTitle = 'Dashboard';
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
                <i class="mdi mdi-home"></i>
              </span> Dashboard
            </h3>
            <nav aria-label="breadcrumb">
              <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                  <span></span>Overview <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
                </li>
              </ul>
            </nav>
          </div>
          <div class="row">
            <div class="col-md-4 stretch-card grid-margin">
              <div class="card bg-gradient-success card-img-holder text-white">
                <div class="card-body">
                  <img src="layout/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
                  <h4 class="font-weight-normal mb-3">Employees <i class="mdi mdi-worker mdi-24px float-right"></i>
                  </h4>
                  <h2 class="mb-5"><?php echo countItems('emp_no', 'employees') ?></h2>
                </div>
              </div>
            </div>
            <div class="col-md-4 stretch-card grid-margin">
              <div class="card bg-gradient-success card-img-holder text-white">
                <div class="card-body">
                  <img src="layout/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
                  <h4 class="font-weight-normal mb-3">Guests<i class="mdi mdi-account mdi-24px float-right"></i>
                  </h4>
                  <h2 class="mb-5"><?php echo countItems('gu_no', 'guests') ?></h2>
                </div>
              </div>
            </div>
            <div class="col-md-4 stretch-card grid-margin">
              <div class="card bg-gradient-success card-img-holder text-white">
                <div class="card-body">
                  <img src="layout/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
                  <h4 class="font-weight-normal mb-3">Books<i class="mdi mdi-bookmark mdi-24px float-right"></i>
                  </h4>
                  <h2 class="mb-5"><?php echo countItems('bo_no', 'books') ?></h2>
                </div>
              </div>
            </div>
            <div class="col-md-4 stretch-card grid-margin">
              <div class="card bg-gradient-success card-img-holder text-white">
                <div class="card-body">
                  <img src="layout/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
                  <h4 class="font-weight-normal mb-3">Rooms<i class="mdi mdi-seat-flat mdi-24px float-right"></i>
                  </h4>
                  <h2 class="mb-5"><?php echo countItems('rm_no', 'rooms') ?></h2>
                </div>
              </div>
            </div>
            <div class="col-md-4 stretch-card grid-margin">
              <div class="card bg-gradient-success card-img-holder text-white">
                <div class="card-body">
                  <img src="layout/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
                  <h4 class="font-weight-normal mb-3">Booked Rooms<i class="mdi mdi mdi-bookmark-check mdi-24px float-right"></i>
                  </h4>
                  <h2 class="mb-5"><?php echo countAvaillabl('rm_no', 'rooms', 'rm_state', 1) ?></h2>
                </div>
              </div>
            </div>
            <div class="col-md-4 stretch-card grid-margin">
              <div class="card bg-gradient-success card-img-holder text-white">
                <div class="card-body">
                  <img src="layout/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
                  <h4 class="font-weight-normal mb-3">Available Rooms <i class="mdi mdi-bookmark-remove mdi-24px float-right"></i>
                  </h4>
                  <h2 class="mb-5"><?php echo countAvaillabl('rm_no', 'rooms', 'rm_state', 0) ?></h2>
                </div>
              </div>
            </div>
            <div class="col-md-4 stretch-card grid-margin">
              <div class="card bg-gradient-success card-img-holder text-white">
                <div class="card-body">
                  <img src="layout/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
                  <h4 class="font-weight-normal mb-3">Orders<i class="mdi mdi mdi-format-list-bulleted mdi-24px float-right"></i>
                  </h4>
                  <h2 class="mb-5"><?php echo countItems('or_no', 'orders') ?></h2>
                </div>
              </div>
            </div>
            <div class="col-md-4 stretch-card grid-margin">
              <div class="card bg-gradient-success card-img-holder text-white">
                <div class="card-body">
                  <img src="layout/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
                  <h4 class="font-weight-normal mb-3">Amount Orders<i class="mdi mdi-cart mdi-24px float-right"></i>
                  </h4>
                  <h2 class="mb-5">$<?php echo sumBills('or_money', 'orders') ?></h2>
                </div>
              </div>
            </div>
            <div class="col-md-4 stretch-card grid-margin">
              <div class="card bg-gradient-success card-img-holder text-white">
                <div class="card-body">
                  <img src="layout/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
                  <h4 class="font-weight-normal mb-3">Payed Orders<i class="mdi mdi mdi mdi-credit-card mdi-24px float-right"></i>
                  </h4>
                  <h2 class="mb-5"><?php echo countAvaillabl('or_no', 'orders', 'or_state', 1) ?></h2>
                  <h6 class="card-text">$<?php echo sumAvaillabl('or_money', 'orders', 'or_state', 1) ?></h6>
                </div>
              </div>
            </div>
            <div class="col-md-4 stretch-card grid-margin">
              <div class="card bg-gradient-success card-img-holder text-white">
                <div class="card-body">
                  <img src="layout/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
                  <h4 class="font-weight-normal mb-3"> Not Payed Orders<i class="mdi mdi mdi-credit-card-off mdi-24px float-right"></i>
                  </h4>
                  <h2 class="mb-5"><?php echo countAvaillabl('or_no', 'orders', 'or_state', 0) ?></h2>
                  <h6 class="card-text">$<?php echo sumAvaillabl('or_money', 'orders', 'or_state', 0) ?></h6>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php include $tpl . 'footer.php';
} else {
  header('Location: index.php');
  exit();
}
ob_end_flush();
?>
<?php
session_start(); 
include '../db_connection.php'; 

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

$user_id = $_SESSION['user_id']; 
$role = $_SESSION['role']; 

$query = "SELECT name_admin FROM tb_admin WHERE id_admin = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id); 
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $username = $user['name_admin']; 
} else {
    $username = "User not found"; 
}


$query_reservation = "
    SELECT ofr.id_offline_reservation, ot.name_off_tourist, ts.name_services, ot.phoneno_off_tourist, 
    ofr.visit_start_date, tst.title_status, ofr.id_status FROM tb_offline_reservaion ofr 
    JOIN tb_offline_tourist ot ON ot.id_off_tourist = ofr.id_offline_tourist JOIN tb_services ts 
    ON ts.id_services = ofr.id_services JOIN tb_status tst ON tst.id_status = ofr.id_status 
    WHERE ofr.id_status IN (1, 2, 3, 4);";
$result_reservation = $conn->query($query_reservation);

if (!$result_reservation) {
    die("Error fetching reservation data: " . $conn->error);
}


$query_online_reservation = "
    SELECT tbrvo.id_reservation, tbot.name_tourist, tbs.name_services, tbot.phone_number_tourist, 
    tbrvo.reservation_date, tst.title_status, tbrvo.id_status FROM tb_reservation tbrvo 
    JOIN tb_tourist tbot ON tbot.id_tourist = tbrvo.id_tourist 
    JOIN tb_services tbs ON tbs.id_services = tbrvo.id_services 
    JOIN tb_status tst ON tst.id_status = tbrvo.id_status 
    WHERE tbrvo.id_status IN (1, 2, 3, 4);
";
$result_online_reservation = $conn->query($query_online_reservation);

if (!$result_online_reservation) {
    die("Error fetching online reservation data: " . $conn->error);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Admin</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description"
        content="Admindek Bootstrap admin template made using Bootstrap 4 and it has huge amount of ready made feature, UI components, pages which completely fulfills any dashboard needs." />
    <meta name="keywords"
        content="flat ui, admin Admin , Responsive, Landing, Bootstrap, App, Template, Mobile, iOS, Android, apple, creative app">
    <meta name="author" content="colorlib" />
    <link rel="icon" href="https://colorlib.com/polygon/admindek/files/assets/images/favicon.ico" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Quicksand:500,700" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/waves.min.css" type="text/css" media="all">
    <link rel="stylesheet" type="text/css" href="css/feather.css">
    <link rel="stylesheet" type="text/css" href="css/font-awesome-n.min.css">
    <link rel="stylesheet" href="css/chartist.css" type="text/css" media="all">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/widget.css">
</head>

<body>
    <div class="loader-bg">
        <div class="loader-bar"></div>
    </div>
    <div id="pcoded" class="pcoded">
        <div class="pcoded-overlay-box"></div>
        <div class="pcoded-container navbar-wrapper">
            <nav class="navbar header-navbar pcoded-header">
                <div class="navbar-wrapper">
                    <div class="navbar-logo">
                        <a href="index.php">
                            <img class="img-fluid" src="png/logo.png" alt="Theme-Logo" />
                        </a>
                        <a class="mobile-menu" id="mobile-collapse" href="#!">
                            <i class="feather icon-menu icon-toggle-right"></i>
                        </a>
                        <a class="mobile-options waves-effect waves-light">
                            <i class="feather icon-more-horizontal"></i>
                        </a>
                    </div>
                    <div class="navbar-container container-fluid">
                        <ul class="nav-left">
                            <li class="header-search">
                            </li>
                            <li>
                                <a href="#!"
                                    onclick="if (!window.__cfRLUnblockHandlers) return false; javascript:toggleFullScreen()"
                                    class="waves-effect waves-light" data-cf-modified-d2d1d6e2f87cbebdf4013b26-="">
                                    <i class="full-screen feather icon-maximize"></i>
                                </a>
                            </li>
                        </ul>
                        <ul class="nav-right">
                            <li class="user-profile header-notification">
                                <div class="dropdown-primary dropdown">
                                    <div class="dropdown-toggle" data-toggle="dropdown">
                                        <span><?php echo $username; ?></span>
                                        <i class="feather icon-chevron-down"></i>
                                    </div>
                                    <ul class="show-notification profile-notification dropdown-menu"
                                        data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">
                                        <li>
                                            <a href="logout.php"><i class="feather icon-log-out"></i> Logout</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <div id="sidebar" class="users p-chat-user showChat">
                <div class="had-container">
                    <div class="p-fixed users-main">
                        <div class="user-box">
                            <div class="chat-search-box">
                                <a class="back_friendlist">
                                    <i class="feather icon-x"></i>
                                </a>
                                <div class="right-icon-control">
                                    <div class="input-group input-group-button">
                                        <input type="text" id="search-friends" name="footer-email" class="form-control"
                                            placeholder="Search Friend">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary waves-effect waves-light" type="button"><i
                                                    class="feather icon-search"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>


            <div class="pcoded-main-container">
                <div class="pcoded-wrapper">

                    <nav class="pcoded-navbar">
                        <div class="nav-list">
                            <div class="pcoded-inner-navbar main-menu">
                                <div class="pcoded-navigation-label">Navigation</div>
                                <ul class="pcoded-item pcoded-left-item">
                                    <li class="pcoded-hasmenu">
                                        <a href="index.php" class="waves-effect waves-dark">
                                            <span class="pcoded-micon"><i class="feather icon-home"></i></span>
                                            <span class="pcoded-mtext">Dashboard</span>
                                        </a>
                                    </li>
                                    <li class="pcoded-hasmenu active">
                                        <a href="javascript:void(0)" class="waves-effect waves-dark">
                                            <span class="pcoded-micon"><i class="feather icon-sidebar"></i></span>
                                            <span class="pcoded-mtext">Reservasi</span>
                                        </a>
                                        <ul class="pcoded-submenu active">
                                            <li class=" ">
                                                <a href="offline_reservation.php" class="waves-effect waves-dark">
                                                    <span class="pcoded-mtext">Buat Reservasi Offline</span>
                                                </a>
                                            </li>

                                            <li class=" ">
                                                <a href="list_reservation.php" class="waves-effect waves-dark">
                                                    <span class="pcoded-mtext">Daftar Reservasi</span>
                                                </a>
                                            </li>
                                            <li class=" ">
                                                <a href="history_reservation.php" class="waves-effect waves-dark">
                                                    <span class="pcoded-mtext">History Reservasi</span>
                                                </a>
                                            </li>
                                        </ul>

                                    <li class="pcoded-hasmenu">
                                        <a href="tools.php" class="waves-effect waves-dark">
                                            <span class="pcoded-micon"><i class="feather icon-command"></i></span>
                                            <span class="pcoded-mtext">Sewa Alat</span>
                                        </a>
                                    </li>
                            </div>
                        </div>
                    </nav>

                    <div class="pcoded-content">

                        <div class="page-header card">
                            <div class="row align-items-end">
                                <div class="col-lg-8">
                                    <div class="page-header-title">
                                        <i class="feather icon-home bg-c-blue"></i>
                                        <div class="d-inline">
                                            <h5>Daftar Reservasi</h5>
                                            <span>Reservasi/Daftar Reservasi</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="page-header-breadcrumb">
                                        <ul class=" breadcrumb breadcrumb-title">
                                            <li class="breadcrumb-item">
                                                <a href="index.php"><i class="feather icon-home"></i></a>
                                            </li>
                                            <li class="breadcrumb-item"><a href="#!">Dashboard</a> </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="pcoded-inner-content">
                            <div class="main-body">
                                <div class="page-wrapper">
                                    <div class="page-body">
                                        <div class="row">
                                            <div class="col-xl-12">
                                                <div class="card-block">
                                                    <div class="card">
                                                        <div class="card-block p-b-0">
                                                        <h5>Daftar Reservasi Offline</h5>
                                                        <div class="table-responsive">
                                                            <table class="table table-hover m-b-0">
                                                                <thead>
                                                                    <tr>
                                                                        <th>ID Reservasi</th>
                                                                        <th>Nama Wisatawan</th>
                                                                        <th>Layanan</th>
                                                                        <th>No. Telepon</th>
                                                                        <th>Tanggal Reservasi</th>
                                                                        <th>Status Reservasi</th>
                                                                        <th>Aksi</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    if ($result_reservation->num_rows > 0) {
                                                                        while ($row = $result_reservation->fetch_assoc()) {
                                                                            echo '<tr>';
                                                                            echo '<td>' . $row['id_offline_reservation'] . '</td>';
                                                                            echo '<td>' . $row['name_off_tourist'] . '</td>';
                                                                            echo '<td>' . $row['name_services'] . '</td>';
                                                                            echo '<td>' . $row['phoneno_off_tourist'] . '</td>';
                                                                            echo '<td>' . $row['visit_start_date'] . '</td>';
                                                                            echo '<td>' . $row['title_status'] . '</td>';
                                                                            echo '<td>';

                                                                            switch ($row['id_status']) {
                                                                                case 1: 
                                                                                    echo '<a href="view_data_offline.php?id=' . $row['id_offline_reservation'] . '" class="btn btn-info btn-sm">Lihat Data</a> ';
                                                                                    echo '<a href="update_reservation_status.php?id=' . $row['id_offline_reservation'] . '&action=reject" class="btn btn-danger btn-sm" onclick="return confirm(\'Yakin tolak reservasi ini?\')">Tolak Reservasi</a>';
                                                                                    break;
                                                                                case 2: 
                                                                                    echo '<a href="view_data_offline.php?id=' . $row['id_offline_reservation'] . '" class="btn btn-info btn-sm">Lihat Data</a> ';
                                                                                    echo '<a href="update_reservation_status.php?id=' . $row['id_offline_reservation'] . '&action=payment_accepted" class="btn btn-success btn-sm">Pembayaran Diterima</a> ';
                                                                                    echo '<a href="update_reservation_status.php?id=' . $row['id_offline_reservation'] . '&action=payment_rejected" class="btn btn-warning btn-sm">Pembayaran Tidak Diterima</a>';
                                                                                    break;
                                                                                case 3:
                                                                                    echo '<a href="view_data_offline.php?id=' . $row['id_offline_reservation'] . '" class="btn btn-info btn-sm">Lihat Data</a> ';
                                                                                    echo '<a href="update_reservation_status.php?id=' . $row['id_offline_reservation'] . '&action=start" class="btn btn-primary btn-sm">Mulai Reservasi</a> ';
                                                                                    echo '<a href="update_reservation_status.php?id=' . $row['id_offline_reservation'] . '&action=cancel" class="btn btn-danger btn-sm" onclick="return confirm(\'Yakin batalkan reservasi ini?\')">Cancel Reservasi</a>';
                                                                                    break;
                                                                                case 4:
                                                                                    echo '<a href="view_data_offline.php?id=' . $row['id_offline_reservation'] . '" class="btn btn-info btn-sm">Lihat Data</a> '; 
                                                                                    echo '<a href="update_reservation_status.php?id=' . $row['id_offline_reservation'] . '&action=complete" class="btn btn-success btn-sm">Selesaikan Reservasi</a>';
                                                                                    break;
                                                                                
                                                                                default: 
                                                                                    echo 'No actions available';
                                                                                    break;
                                                                            }
                                                            
                                                                            echo '</td>';
                                                                            echo '</tr>';
                                                                        }
                                                                    } else {
                                                                        echo '<tr><td colspan="6">No reservations available.</td></tr>';
                                                                    }
                                                                    ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                </div>

                                                
                                                    </div>
                                                </div>

                                            </div>


                                        </div>

                                        <div class="card-block">
                                                    <div class="card">
                                                        <div class="card-block p-b-0">
                                                        <h5>Daftar Reservasi Online</h5>
                                                        <div class="table-responsive">
                                                        <table class="table table-hover m-b-0">
                                                            <thead>
                                                                <tr>
                                                                    <th>ID Reservasi</th>
                                                                    <th>Nama Wisatawan</th>
                                                                    <th>Layanan</th>
                                                                    <th>No. Telepon</th>
                                                                    <th>Tanggal Reservasi</th>
                                                                    <th>Status Reservasi</th>
                                                                    <th>Aksi</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                if ($result_online_reservation->num_rows > 0) {
                                                                    while ($row = $result_online_reservation->fetch_assoc()) {
                                                                        echo '<tr>';
                                                                        echo '<td>' . $row['id_reservation'] . '</td>';
                                                                        echo '<td>' . $row['name_tourist'] . '</td>';
                                                                        echo '<td>' . $row['name_services'] . '</td>';
                                                                        echo '<td>' . $row['phone_number_tourist'] . '</td>';
                                                                        echo '<td>' . $row['reservation_date'] . '</td>';
                                                                        echo '<td>' . $row['title_status'] . '</td>';
                                                                        echo '<td>';

                                                                        switch ($row['id_status']) {
                                                                            case 1: 
                                                                                echo '<a href="view_data.php?id=' . $row['id_reservation'] . '" class="btn btn-info btn-sm">Lihat Data</a> ';
                                                                                echo '<a href="update_reservasi_online.php?id=' . $row['id_reservation'] . '&action=reject" class="btn btn-danger btn-sm" onclick="return confirm(\'Yakin tolak reservasi ini?\')">Tolak Reservasi</a>';
                                                                                break;
                                                                            case 2: 
                                                                                echo '<a href="view_data.php?id=' . $row['id_reservation'] . '" class="btn btn-info btn-sm">Lihat Data</a> ';
                                                                                echo '<a href="update_reservasi_online.php?id=' . $row['id_reservation'] . '&action=payment_accepted" class="btn btn-success btn-sm">Pembayaran Diterima</a> ';
                                                                                echo '<a href="update_reservasi_online.php?id=' . $row['id_reservation'] . '&action=payment_rejected" class="btn btn-warning btn-sm">Pembayaran Tidak Diterima</a>';
                                                                                break;
                                                                            case 3: 
                                                                                echo '<a href="view_data.php?id=' . $row['id_reservation'] . '" class="btn btn-info btn-sm">Lihat Data</a> ';
                                                                                echo '<a href="update_reservasi_online.php?id=' . $row['id_reservation'] . '&action=start" class="btn btn-primary btn-sm">Mulai Reservasi</a> ';
                                                                                echo '<a href="update_reservasi_online.php?id=' . $row['id_reservation'] . '&action=cancel" class="btn btn-danger btn-sm" onclick="return confirm(\'Yakin batalkan reservasi ini?\')">Cancel Reservasi</a>';
                                                                                break;
                                                                            case 4: 
                                                                                echo '<a href="view_data.php?id=' . $row['id_reservation'] . '" class="btn btn-info btn-sm">Lihat Data</a> ';
                                                                                echo '<a href="update_reservasi_online.php?id=' . $row['id_reservation'] . '&action=complete" class="btn btn-success btn-sm">Selesaikan Reservasi</a>';
                                                                                break;
                                                                            default: 
                                                                                echo 'No actions available';
                                                                                break;
                                                                        }
                                                                        
                                                                        echo '</td>';
                                                                        echo '</tr>';
                                                                    }
                                                                } else {
                                                                    echo '<tr><td colspan="7">No online reservations available.</td></tr>';
                                                                }
                                                                ?>
                                                            </tbody>
                                                        </table>
                                                        </div>
                                                </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="styleSelector">
                    </div>

                </div>
            </div>
        </div>
    </div>


    <script data-cfasync="false" src="js/email-decode.min.js"></script>
    <script type="d2d1d6e2f87cbebdf4013b26-text/javascript" src="js/jquery.min.js"></script>
    <script type="d2d1d6e2f87cbebdf4013b26-text/javascript" src="js/jquery-ui.min.js"></script>
    <script type="d2d1d6e2f87cbebdf4013b26-text/javascript" src="js/popper.min.js"></script>
    <script type="d2d1d6e2f87cbebdf4013b26-text/javascript" src="js/bootstrap.min.js"></script>

    <script src="js/waves.min.js" type="d2d1d6e2f87cbebdf4013b26-text/javascript"></script>

    <script type="d2d1d6e2f87cbebdf4013b26-text/javascript" src="js/jquery.slimscroll.js"></script>

    <script src="js/jquery.flot.js" type="d2d1d6e2f87cbebdf4013b26-text/javascript"></script>
    <script src="js/jquery.flot.categories.js" type="d2d1d6e2f87cbebdf4013b26-text/javascript"></script>
    <script src="js/curvedlines.js" type="d2d1d6e2f87cbebdf4013b26-text/javascript"></script>
    <script src="js/jquery.flot.tooltip.min.js" type="d2d1d6e2f87cbebdf4013b26-text/javascript"></script>

    <script src="js/chartist.js" type="d2d1d6e2f87cbebdf4013b26-text/javascript"></script>

    <script src="js/amcharts.js" type="d2d1d6e2f87cbebdf4013b26-text/javascript"></script>
    <script src="js/serial.js" type="d2d1d6e2f87cbebdf4013b26-text/javascript"></script>
    <script src="js/light.js" type="d2d1d6e2f87cbebdf4013b26-text/javascript"></script>

    <script src="js/pcoded.min.js" type="d2d1d6e2f87cbebdf4013b26-text/javascript"></script>
    <script src="js/vertical-layout.min.js" type="d2d1d6e2f87cbebdf4013b26-text/javascript"></script>
    <script type="d2d1d6e2f87cbebdf4013b26-text/javascript" src="js/custom-dashboard.min.js"></script>
    <script type="d2d1d6e2f87cbebdf4013b26-text/javascript" src="js/script.min.js"></script>

    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-23581568-13"
        type="d2d1d6e2f87cbebdf4013b26-text/javascript"></script>
    <script type="d2d1d6e2f87cbebdf4013b26-text/javascript">
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'UA-23581568-13');
    </script>
    <script src="js/rocket-loader.min.js" data-cf-settings="d2d1d6e2f87cbebdf4013b26-|49" defer=""></script>
</body>


</html>
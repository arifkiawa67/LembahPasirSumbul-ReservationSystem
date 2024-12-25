<?php
session_start(); 
include '../db_connection.php'; 

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

$user_id = $_SESSION['user_id']; 
$role = $_SESSION['role']; 

$query = "SELECT name_manager FROM tb_manager WHERE id_manager = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id); 
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $username = $user['name_manager']; 
} else {
    $username = "User not found"; 
}

$query_reservations = "
    SELECT 
        tbrv.id_offline_reservation, 
        tbs.name_services, 
        tbt.name_off_tourist, 
        tbrv.visit_start_date, 
        tbrv.visit_end_date 
    FROM 
        tb_offline_reservaion tbrv 
    JOIN 
        tb_offline_tourist tbt ON tbt.id_off_tourist = tbrv.id_offline_tourist 
    JOIN 
        tb_services tbs ON tbs.id_services = tbrv.id_services 
    GROUP BY 
        tbrv.id_offline_reservation ASC;
";

$result_reservations = $conn->query($query_reservations);

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
                                            <span class="pcoded-mtext">Laporan</span>
                                        </a>
                                        <ul class="pcoded-submenu">
                                            <li class=" ">
                                                <a href="reservasionline.php" class="waves-effect waves-dark">
                                                    <span class="pcoded-mtext">Reservasi Online</span>
                                                </a>
                                            </li>

                                            <li class=" ">
                                                <a href="reservasioffline.php" class="waves-effect waves-dark">
                                                    <span class="pcoded-mtext">Reservasi Offline</span>
                                                </a>
                                            </li>
                                            <li class=" ">
                                                <a href="wisatawan.php" class="waves-effect waves-dark">
                                                    <span class="pcoded-mtext">Wisatawan</span>
                                                </a>
                                            </li>
                                            
                                        </ul>

                                    <li class="pcoded-hasmenu">
                                        <a href="manageadmin.php" class="waves-effect waves-dark">
                                            <span class="pcoded-micon"><i class="feather icon-command"></i></span>
                                            <span class="pcoded-mtext">Manage Admin</span>
                                        </a>
                                    </li>

                                    <li class="pcoded-hasmenu">
                                        <a href="manageservice.php" class="waves-effect waves-dark">
                                            <span class="pcoded-micon"><i class="feather icon-command"></i></span>
                                            <span class="pcoded-mtext">Manage Service</span>
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
                                            <h5>Report Wisatawan</h5>
                                            <span>Laporan/Wisatawan</span>
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
                                                <div class="card proj-progress-card">
                                                <div class="card-block p-b-0">
                                                <a href="generate_pdf_wisatawan.php" class="btn btn-primary">Download Report</a>
                                                <br></br>
                                                    <h5>Offline Wisatawan</h5>
                                                    <div class="table-responsive">
                                                    <table class="table table-hover m-b-0">
                                                        <thead>
                                                            <tr>
                                                                <th>Nama Wisatawan</th>
                                                                <th>No. Telepon</th>
                                                                <th>NIK</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            // Query untuk mendapatkan data dari tabel tb_offline_tourist
                                                            $query_tourists = "SELECT name_off_tourist, phoneno_off_tourist, nik_tourist FROM tb_offline_tourist GROUP BY name_off_tourist ASC ;";
                                                            $result_tourists = $conn->query($query_tourists);

                                                            if ($result_tourists->num_rows > 0) {
                                                                while ($row = $result_tourists->fetch_assoc()) {
                                                                    echo '<tr>';
                                                                    echo '<td>' . htmlspecialchars($row['name_off_tourist']) . '</td>'; // Nama Wisatawan
                                                                    echo '<td>' . htmlspecialchars($row['phoneno_off_tourist']) . '</td>'; // No. Telepon
                                                                    echo '<td>' . htmlspecialchars($row['nik_tourist']) . '</td>'; // NIK
                                                                    echo '</tr>';
                                                                }
                                                            } else {
                                                                echo '<tr><td colspan="3">No data available.</td></tr>';
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>

                                                    </div>
                                                </div>

                                                </div>
                                            </div>

                                            <div class="col-xl-12">
                                                <div class="card proj-progress-card">
                                                <div class="card-block p-b-0">
                                                    <h5>Online Wisatawan</h5>
                                                    <div class="table-responsive">
                                                    <table class="table table-hover m-b-0">
                                                        <thead>
                                                            <tr>
                                                                <th>Nama Wisatawan</th>
                                                                <th>No. Telepon</th>
                                                                <th>Email</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            // Query untuk mendapatkan data dari tabel tb_tourist
                                                            $query_tourists = "SELECT name_tourist, phone_number_tourist, email_tourist FROM tb_tourist GROUP BY name_tourist ASC;";
                                                            $result_tourists = $conn->query($query_tourists);

                                                            if ($result_tourists->num_rows > 0) {
                                                                while ($row = $result_tourists->fetch_assoc()) {
                                                                    echo '<tr>';
                                                                    echo '<td>' . htmlspecialchars($row['name_tourist']) . '</td>'; // Nama Wisatawan
                                                                    echo '<td>' . htmlspecialchars($row['phone_number_tourist']) . '</td>'; // No. Telepon
                                                                    echo '<td>' . htmlspecialchars($row['email_tourist']) . '</td>'; // Email
                                                                    echo '</tr>';
                                                                }
                                                            } else {
                                                                echo '<tr><td colspan="3">No data available.</td></tr>';
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
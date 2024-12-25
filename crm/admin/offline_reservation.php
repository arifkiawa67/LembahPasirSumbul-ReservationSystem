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

$services_query = "SELECT id_services, name_services FROM tb_services";
$services_result = $conn->query($services_query);
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
                                            <h5>Reservasi Offline</h5>
                                            <span>Reservasi/Reservasi Offline</span>
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
                                                        <div class="card-header">
                                                            <h5>Reservasi Offline Tourist</h5>
                                                        </div>
                                                        <div class="card-block">
                                                            <form action="reservasi_offline_tourist.php" method="POST">
                                                                <div class="form-group row">
                                                                    <label class="col-sm-2 col-form-label">Nama
                                                                        Tourist</label>
                                                                    <div class="col-sm-10">
                                                                        <input type="text" name="name_tourist"
                                                                            class="form-control"
                                                                            placeholder="Masukkan nama tourist"
                                                                            required>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group row">
                                                                    <label class="col-sm-2 col-form-label">Nomor HP
                                                                        Tourist</label>
                                                                    <div class="col-sm-10">
                                                                        <input type="text" name="phoneno_tourist"
                                                                            class="form-control"
                                                                            placeholder="Masukkan nomor HP tourist"
                                                                            required>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group row">
                                                                    <label class="col-sm-2 col-form-label">NIK
                                                                        Tourist</label>
                                                                    <div class="col-sm-10">
                                                                        <input type="text" name="nik_tourist"
                                                                            class="form-control"
                                                                            placeholder="Masukkan NIK tourist" required
                                                                            maxlength="16" minlength="16">
                                                                    </div>
                                                                </div>

                                                                <div class="form-group row">
                                                                    <label class="col-sm-2 col-form-label">Pilih
                                                                        Layanan</label>
                                                                    <div class="col-sm-10">
                                                                        <select name="id_services" class="form-control"
                                                                            required>
                                                                            <option value="">-- Pilih Layanan --
                                                                            </option>
                                                                            <?php while ($service = $services_result->fetch_assoc()) { ?>
                                                                            <option
                                                                                value="<?php echo $service['id_services']; ?>">
                                                                                <?php echo $service['name_services']; ?>
                                                                            </option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                
                                                                <!-- Combo Box for Renting Tools -->
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Apakah Anda Ingin Sewa Alat?</label>
                    <div class="col-sm-10">
                        <select name="rent_tools" id="rent_tools" class="form-control" onchange="toggleToolsList(this)">
                            <option value="">-- Pilih --</option>
                            <option value="yes">Ya</option>
                            <option value="no">Tidak</option>
                        </select>
                    </div>
                </div>

                <!-- Tools List Table (hidden by default) -->
                <div id="tools_list" style="display:none;">
                    <table class="table">
                    <thead>
            <tr>
                <th>Nama Alat</th>
                <th>Harga</th>
                <th>Qty</th>
                <th>Total Harga</th>
            </tr>
        </thead>
        <tbody id="tools_table_body">
            <?php
            // Fetch available tools from tb_tools table
            $tools_query = "SELECT * FROM tb_tools";
            $tools_result = $conn->query($tools_query);
            while ($tool = $tools_result->fetch_assoc()) {
                ?>
                <tr>
                    <td><?php echo $tool['name_tools']; ?></td>
                    <td>
                        <input type="text" name="tool_price[]" value="<?php echo $tool['price_tools']; ?>" readonly class="form-control">
                    </td>
                    <td>
                        <input type="number" name="tool_qty[]" value="0" class="form-control" min="0" onchange="updateToolPrice(this)">
                    </td>
                    <td>
                        <input type="text" name="tool_total[]" value="0" readonly class="form-control total_price">
                    </td>
                    <input type="hidden" name="id_tools[]" value="<?php echo $tool['id_tools']; ?>"> <!-- Hidden ID field -->
                </tr>
            <?php } ?>
        </tbody>
                    </table>
                </div>



                                                                <div class="form-group row">
                                                                    <div class="col-sm-10 offset-sm-2">
                                                                        <button type="submit"
                                                                            class="btn btn-primary">Simpan Data</button>
                                                                    </div>
                                                                </div>
                                                            </form>
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

    <script>
    // Toggle the tool list based on user selection
    function toggleToolsList(select) {
        var toolsList = document.getElementById('tools_list');
        if (select.value === 'yes') {
            toolsList.style.display = 'block';
        } else {
            toolsList.style.display = 'none';
        }
    }

    // Update total price when quantity is changed
    function updateToolPrice(input) {
        var row = input.closest("tr");
        var qty = parseInt(input.value) || 0;
        var price = parseInt(row.querySelector("input[name='tool_price[]']").value) || 0;
        var totalPriceField = row.querySelector("input[name='tool_total[]']");

        // Calculate total price for the tool
        var totalPrice = qty * price;
        totalPriceField.value = totalPrice;

        // Optionally, update the form to reflect these changes.
    }
    </script>


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
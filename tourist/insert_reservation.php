<?php
session_start();

if (!isset($_SESSION['id_tourist']) || !isset($_SESSION['name_tourist'])) {
    header('Location: login.php');
    exit();
}

include('db_connection.php');  // Include the database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tanggal_reservasi = $_POST['tanggal_reservasi'];
    $id_service = $_POST['service'];
    $rent_tools = isset($_POST['rent_tools']) ? $_POST['rent_tools'] : 'no';  // Default to 'no' if rent_tools not set
    $tools_selected = isset($_POST['id_tools']) ? $_POST['id_tools'] : [];  // Ensure this is an array
    $qty_tools = isset($_POST['tool_qty']) ? $_POST['tool_qty'] : [];  // Ensure this is an array
    $total_tools = isset($_POST['tool_total']) ? $_POST['tool_total'] : [];  // Get total price for tools
    // Get the current date and time for reservation_date
    $reservation_date = date('Y-m-d H:i:s');
    $id_tourist = $_SESSION['id_tourist'];

    // Default status
    $status = 1;

    // Ambil harga service dari tb_services
    $service_price = 0;
    $service_query = "SELECT price_services FROM tb_services WHERE id_services = ?";
    $service_stmt = $conn->prepare($service_query);
    $service_stmt->bind_param("i", $id_service);
    $service_stmt->execute();
    $service_result = $service_stmt->get_result();
    if ($service_row = $service_result->fetch_assoc()) {
        $service_price = $service_row['price_services'];
    }

    // Hitung total harga alat jika ada
    $total_tool_price = 0;
    if ($rent_tools == 'yes' && !empty($tools_selected) && !empty($qty_tools)) {
        foreach ($tools_selected as $index => $tool_id) {
            if (isset($qty_tools[$index]) && isset($total_tools[$index])) {
                $total_tool_price += $total_tools[$index];  // Tambahkan total harga alat
            }
        }
    }

    // Hitung total harga
    $total_price = $service_price + $total_tool_price; // Total harga termasuk harga service dan alat

    // Insert reservation query
    $query = "INSERT INTO tb_reservation (id_tourist, id_services, reservation_date, visit_start_date, id_status, is_tools, price) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($query);
    $is_tools = ($rent_tools == 'yes') ? 1 : 0;  // Set is_tools to 1 if renting tools, otherwise 0
    $stmt->bind_param('iissidi', $id_tourist, $id_service, $reservation_date, $tanggal_reservasi, $status, $is_tools, $total_price);

    if ($stmt->execute()) {
        $id_off_reservation = $stmt->insert_id;  // Get the last inserted reservation ID
        $rent_success = true;  // Track rental success

        // Handle tool rentals if 'yes' is selected
        if ($rent_tools == 'yes') {
            if (!empty($tools_selected) && !empty($qty_tools)) {
                foreach ($tools_selected as $index => $tool_id) {
                    if (isset($qty_tools[$index]) && isset($total_tools[$index])) {
                        $tool_qty = $qty_tools[$index];
                        $tool_total = $total_tools[$index];

                        // Query tool price
                        $tool_query = "SELECT price_tools FROM tb_tools WHERE id_tools = ?";
                        $tool_stmt = $conn->prepare($tool_query);
                        $tool_stmt->bind_param("i", $tool_id);
                        $tool_stmt->execute();
                        $tool_result = $tool_stmt->get_result();
                        $tool = $tool_result->fetch_assoc();

                        if ($tool) {
                            $tool_price = $tool['price_tools'];
                        } else {
                            continue;
                        }

                        if ($tool_qty <= 0 || $tool_price <= 0) {
                            continue;
                        }

                        // Insert rental tools data
                        $rental_date = date("Y-m-d");
                        $insert_rent_query = "INSERT INTO tb_rent_tools (id_tools, qty_rent, price_rent, rental_date, id_reservation)
                                              VALUES (?, ?, ?, ?, ?)";
                        $rent_stmt = $conn->prepare($insert_rent_query);
                        if (!$rent_stmt) {
                            $rent_success = false;
                            break;
                        }
                        $rent_stmt->bind_param("iiisi", $tool_id, $tool_qty, $tool_total, $rental_date, $id_off_reservation);
                        if (!$rent_stmt->execute()) {
                            $rent_success = false;
                            break;
                        }
                    }
                }
            }
        }

        // SweetAlert2 success notification
        if ($rent_success) {
            echo "
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: 'Reservasi dan sewa alat berhasil dibuat.',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'index.php';
                        }
                    });
                });
            </script>";
        } else {
            echo "
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan dalam menyewa alat.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                });
            </script>";
        }
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

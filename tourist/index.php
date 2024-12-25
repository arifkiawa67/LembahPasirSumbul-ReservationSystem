<?php
session_start();
include('db_connection.php');  // Include the database connection

// Check if the user is logged in and the session variables are set
if (!isset($_SESSION['user']) || !isset($_SESSION['id_tourist']) || !isset($_SESSION['name_tourist'])) {
    header('Location: login.php');  
    exit();
}


?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* General styling for the form and layout */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        main {
            padding: 20px;
            background-color: white;
            margin: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
            font-size: 24px;
            margin-bottom: 15px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        label {
            font-size: 14px;
            color: #333;
        }

        input[type="date"],
        input[type="time"],
        input[type="number"],
        select,
        button {
            padding: 10px;
            font-size: 16px;
            border-radius: 4px;
            border: 1px solid #ddd;
            width: 100%;
            box-sizing: border-box;
        }

        input[type="number"] {
            -moz-appearance: textfield;
        }

        /* Styling for the dropdown (select) */
        select {
            background-color: #f9f9f9;
            color: #333;
            border: 1px solid #ccc;
            font-size: 16px;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
            transition: border-color 0.3s;
        }

        select:focus {
            border-color: #4CAF50;
        }

        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #45a049;
        }

        /* Optional: Add some space around the form */
        section {
            margin-bottom: 30px;
        }

        /* Styling for error or success messages */
        .message {
            padding: 10px;
            margin-top: 20px;
            border-radius: 5px;
        }

        .success {
            background-color: #4CAF50;
            color: white;
        }

        .error {
            background-color: #f44336;
            color: white;
        }
    </style>
</head>
<body>
    <header>
        <div class="navbar">
        <h1>Welcome, <?php echo isset($_SESSION['name_tourist']) ? htmlspecialchars($_SESSION['name_tourist']) : 'Customer'; ?>!</h1>            <nav>

        <a href="#reservasi">Buat Reservasi</a>
                <a href="#riwayat">Riwayat Reservasi</a>
                <a href="logout.php">Logout</a>
            </nav>
        </div>
    </header>

    <main>
        <!-- Formulir untuk membuat reservasi -->
        <section id="reservasi">
        <h2>Buat Reservasi</h2>
        <form action="insert_reservation.php" method="POST">
                <label for="tanggal_reservasi">Tanggal Reservasi:</label>
                <input type="date" id="tanggal_reservasi" name="tanggal_reservasi" required>

                <label for="service">Pilih Layanan:</label>
                <select id="service" name="service" required>
                    <option value="">--Pilih Layanan--</option>
                    <?php
                        include('db_connection.php');
                        $query = "SELECT * FROM tb_services";
                        $result = mysqli_query($conn, $query);

                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<option value='" . $row['id_services'] . "'>" . $row['name_services'] . " - Rp " . number_format($row['price_services'], 0, ',', '.') . "</option>";
                        }
                    ?>
                </select>
                
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
                            include('db_connection.php');
                            $tools_query = "SELECT * FROM tb_tools";
                            $tools_result = mysqli_query($conn, $tools_query);

                            while ($tool = mysqli_fetch_assoc($tools_result)) { ?>
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
                                    <input type="hidden" name="id_tools[]" value="<?php echo $tool['id_tools']; ?>">
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

                <button type="submit">Buat Reservasi</button>
            </form>
        </section>


        <!-- Riwayat reservasi -->
        <section id="riwayat">
        <h2>Riwayat Reservasi</h2>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal Booking</th>
                    <th>Tanggal Reservasi</th>
                    <th>Layanan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php

                // Get the id_tourist from the session
                $id_tourist = $_SESSION['id_tourist'];

                // SQL query to get the reservation data based on the logged-in tourist's ID
                $query = "SELECT tbr.id_reservation, tbr.reservation_date, tbr.visit_start_date, tbr.visit_end_date, tbs.name_services, 
                            tbr.is_tools, tbr.price, tbst.title_status, tbr.id_status 
                        FROM `tb_reservation` tbr 
                        JOIN tb_tourist tbt ON tbt.id_tourist = tbr.id_tourist 
                        JOIN tb_services tbs ON tbs.id_services = tbr.id_services 
                        JOIN tb_status tbst ON tbst.id_status = tbr.id_status 
                        WHERE tbr.id_tourist = ?";

                $stmt = $conn->prepare($query);

                // Check if the statement was prepared successfully
                if (!$stmt) {
                    die("Query preparation failed: " . $conn->error);  // Show error if query preparation fails
                }

                // Bind the tourist ID to the prepared statement
                $stmt->bind_param('i', $id_tourist);

                // Execute the query
                $stmt->execute();

                // Get the result
                $result = $stmt->get_result();

                // Check if any reservations were found
                if ($result->num_rows == 0) {
                    echo "<tr><td colspan='6'>No reservations found.</td></tr>";  // If no data, display a message
                } else {
                    // Output reservation rows
                    $counter = 1;  // For numbering rows
                    while ($row = $result->fetch_assoc()) {
                        // Format the reservation date and other date fields
                        $reservation_date = date('Y-m-d', strtotime($row['reservation_date']));
                        $visit_start_date = date('Y-m-d', strtotime($row['visit_start_date']));
                        $visit_end_date = date('H:i', strtotime($row['visit_end_date']));
                        $status = $row['title_status'];
                        $price = number_format($row['price'], 2, ',', '.');

                        // Output each reservation row
                        echo "
                        <tr>
                            <td>{$counter}</td>
                            <td>{$reservation_date}</td>
                            <td>{$visit_start_date} - {$visit_end_date}</td>
                            <td>{$row['name_services']}</td>
                            <td>{$status}</td>
                            <td><a href='view_reservation.php?id_reservation={$row['id_reservation']}'>Lihat Data</a></td>
                        </tr>";
                        $counter++;
                    }
                }

                ?>
            </tbody>
        </table>
    </section>

    </main>

    <footer>
        <p>&copy; 2025 Lembah Pasir Sumbul. All rights reserved.</p>
    </footer>
</body>
</html>

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
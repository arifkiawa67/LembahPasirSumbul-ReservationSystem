<?php
session_start();
include '../db_connection.php';
ob_start();
// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "User is not logged in.";
    header('Location: ../index.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Fetch services for the dropdown
$services_query = "SELECT id_services, name_services FROM tb_services";
$services_result = $conn->query($services_query);
if (!$services_result) {
    echo "Error fetching services: " . $conn->error;
    die();
} else {
    echo "Successfully fetched services.<br>";
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name_tourist = $_POST['name_tourist'];
    $phoneno_tourist = $_POST['phoneno_tourist'];
    $nik_tourist = $_POST['nik_tourist'];
    $id_service = $_POST['id_services'];
    $rent_tools = isset($_POST['rent_tools']) ? $_POST['rent_tools'] : 'no';  // Default to 'no' if rent_tools not set
    $tools_selected = isset($_POST['id_tools']) ? $_POST['id_tools'] : [];  // Ensure this is an array
    $qty_tools = isset($_POST['tool_qty']) ? $_POST['tool_qty'] : [];  // Ensure this is an array
    $total_tools = isset($_POST['tool_total']) ? $_POST['tool_total'] : [];  // Get total price for tools

    // Insert tourist data
    $insert_tourist_query = "INSERT INTO tb_offline_tourist (name_off_tourist, phoneno_off_tourist, nik_tourist) 
                             VALUES (?, ?, ?)";
    $stmt = $conn->prepare($insert_tourist_query);
    if (!$stmt) {
        echo "Error preparing statement for tourist insertion: " . $conn->error;
        die();
    }
    $stmt->bind_param("sss", $name_tourist, $phoneno_tourist, $nik_tourist);
    if (!$stmt->execute()) {
        echo "Error executing tourist insertion query: " . $stmt->error;
        die();
    } else {
        echo "Tourist data inserted successfully.<br>";
    }

    // Get the inserted tourist ID
    $id_off_tourist = $stmt->insert_id;
    echo "Inserted tourist ID: $id_off_tourist<br>";

    // Insert reservation data
    $visit_start_date = date("Y-m-d"); 
    $status = 4; // Set status to 4

    $insert_reservation_query = "INSERT INTO tb_offline_reservaion (id_offline_tourist, id_services, visit_start_date, id_status)
                                 VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_reservation_query);
    if (!$stmt) {
        echo "Error preparing statement for reservation insertion: " . $conn->error;
        die();
    }
    $stmt->bind_param("iisi", $id_off_tourist, $id_service, $visit_start_date, $status);
    if (!$stmt->execute()) {
        echo "Error executing reservation insertion query: " . $stmt->error;
        die();
    } else {
        echo "Reservation data inserted successfully.<br>";
    }

    // Get the inserted reservation ID
    $id_off_reservation = $stmt->insert_id;
    echo "Inserted reservation ID: $id_off_reservation<br>";

    // Handle rental tools if selected
    $rent_success = true;  // Flag to track if all tool insertions were successful
    echo "Rent tools flag: $rent_tools<br>"; // Debugging: Check if rent_tools is '1'

    if ($rent_tools == 'yes') {
        // Check if any tools were selected and quantity is provided
        if (empty($tools_selected) || empty($qty_tools)) {
            echo "No tools selected or quantity not provided.<br>";
        } else {
            // Iterate over selected tools
            foreach ($tools_selected as $index => $tool_id) {
                if (isset($qty_tools[$index])) {
                    $tool_qty = $qty_tools[$index];  // Quantity of the selected tool
                    $tool_total = $total_tools[$index];  // Total price of the selected tool
                    echo "Tool ID: $tool_id, Quantity: $tool_qty, Total Price: $tool_total<br>";  // Debugging: Show selected tools and quantities

                    echo "Inserted reservation ID in tools: $id_off_reservation<br>";
                    // Query tool price
                    $tool_query = "SELECT price_tools FROM tb_tools WHERE id_tools = ?";
                    $tool_stmt = $conn->prepare($tool_query);
                    if (!$tool_stmt) {
                        echo "Error preparing tool query: " . $conn->error;
                        die();
                    }
                    $tool_stmt->bind_param("i", $tool_id);
                    if (!$tool_stmt->execute()) {
                        echo "Error executing tool query: " . $tool_stmt->error;
                        die();
                    }
                    $tool_result = $tool_stmt->get_result();
                    $tool = $tool_result->fetch_assoc();
                    if ($tool) {
                        $tool_price = $tool['price_tools'];
                        echo "Tool price: $tool_price<br>";
                    } else {
                        echo "Tool with ID $tool_id not found.<br>";
                        continue; // Skip this tool if not found in tb_tools
                    }
        
                    // Ensure the quantity and price are valid (non-zero and positive)
                    if ($tool_qty <= 0 || $tool_price <= 0) {
                        echo "Invalid quantity or price for tool ID $tool_id.<br>";
                        continue;
                    }
        
                    // Insert rental data into tb_rent_tools
                    $rental_date = date("Y-m-d");
                    $insert_rent_query = "INSERT INTO tb_rent_tools (id_tools, qty_rent, price_rent, rental_date, id_off_reservation)
                                          VALUES (?, ?, ?, ?, ?)";
                    $rent_stmt = $conn->prepare($insert_rent_query);
                    if (!$rent_stmt) {
                        echo "Error preparing rental insertion query: " . $conn->error;
                        die();
                    }
                    $rent_stmt->bind_param("iiisi", $tool_id, $tool_qty, $tool_total, $rental_date, $id_off_reservation);
                    if (!$rent_stmt->execute()) {
                        echo "Error executing rental insertion query: " . $rent_stmt->error;
                        $rent_success = false;
                        break;  // Exit loop if any insertion fails
                    }
                }
            }
            if ($rent_success) {
                echo "Tools rented successfully.<br>";
                header("refresh:1;url=offline_reservation.php");
            } else {
                echo "Error renting tools.<br>";
            }
        }
    }



}
?>

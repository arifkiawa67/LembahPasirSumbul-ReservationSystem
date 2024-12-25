<?php
include '../db_connection.php'; 

if (!isset($_GET['id']) || !isset($_GET['action'])) {
    die("Invalid request. Missing parameters.");
}

$id = intval($_GET['id']); 
$action = $_GET['action']; 

if ($id <= 0) {
    die("Invalid ID.");
}

$statusMapping = [
    'complete' => 5,  
    'reject' => 6,    
    'payment_accepted' => 3, 
    'payment_rejected' => 7,
    'start' => 4,    
    'cancel' => 8     
];

if (!array_key_exists($action, $statusMapping)) {
    die("Invalid action.");
}

$newStatus = $statusMapping[$action];

if ($action === 'complete') {
    $query = "UPDATE tb_reservation SET id_status = ?, visit_end_date = NOW() WHERE id_reservation = ?";
} else {
    $query = "UPDATE tb_reservation SET id_status = ? WHERE id_reservation = ?";
}

$stmt = $conn->prepare($query);

if (!$stmt) {
    die("Failed to prepare statement: " . $conn->error);
}

if ($action === 'complete') {
    $stmt->bind_param("ii", $newStatus, $id);
} else {
    $stmt->bind_param("ii", $newStatus, $id);
}


if ($stmt->execute()) {
    echo "Reservation status updated successfully for ID: $id with status: $newStatus.";
    if ($action === 'complete') {
        echo " Visit end date set to NOW().";
    }
    header("refresh:1;url=list_reservation.php");
} else {
    echo "Failed to update reservation status: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>

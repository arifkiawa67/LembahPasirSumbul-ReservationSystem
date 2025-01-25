<?php
function sendNotificationx($target, $message) {
    $token = 'wfxhwdZUv2h3TcUWtY31'; // Ganti dengan token Anda

    // Mengirim pesan menggunakan API
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.fonnte.com/send',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => array(
            'target' => $target,
            'message' => $message,
            'countryCode' => '62', 
        ),
        CURLOPT_HTTPHEADER => array(
            'Authorization: ' . $token
        ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);

    // Log atau tampilkan respons API untuk debugging
    echo "Pesan dikirim ke: $target<br>";
    echo "Response: $response<br>";
    header('Location: list_reservation.php');
    exit;
}
?>

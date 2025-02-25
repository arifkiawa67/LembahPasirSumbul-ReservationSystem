<?php
function sendNotificationUploadBayar($target, $message) {
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

    if (curl_errno($curl)) {
        $errorMessage = curl_error($curl);
        curl_close($curl);

        // Log error and return
        return [
            'status' => false,
            'message' => "CURL Error: $errorMessage"
        ];
    }

    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    // Log or return response based on HTTP status code
    if ($httpCode == 200) {
        return [
            'status' => true,
            'message' => "Notification sent successfully. Response: $response"
        ];
    } else {
        return [
            'status' => false,
            'message' => "Failed to send notification. HTTP Code: $httpCode, Response: $response"
        ];
    }
}
?>

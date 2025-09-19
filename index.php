<?php
// Get raw POST data
$raw = file_get_contents("php://input");
$data = json_decode($raw, true);

// Extract embed
$embed = $data['embed'] ?? null;
$webhook_url = "https://discord.com/api/webhooks/1418392132816863405/6xQVgbqSLOjUcFVFickTm0sc7BayUgYxQ0P-Ms-dD9rnHQglDsBxYI8Wp6Ec0sFJkJ1Y";

if (!$embed) {
    http_response_code(400);
    echo json_encode(["success" => false, "error" => "Missing embed"]);
    exit;
}

// Forward to Discord
$payload = json_encode(["embeds" => [$embed]]);
$ch = curl_init($webhook_url);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

if ($curlError) {
    http_response_code(500);
    echo json_encode(["success" => false, "error" => $curlError]);
    exit;
}

if ($httpCode >= 200 && $httpCode < 300) {
    echo json_encode(["success" => true]);
} else {
    http_response_code($httpCode ?: 500);
    echo json_encode(["success" => false, "status" => $httpCode, "response" => $response]);
}
?>

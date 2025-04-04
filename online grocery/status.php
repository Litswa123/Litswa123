<?php
$env = "sandbox"; // or "live"
$shortcode = "600988";
$type = "4";
$key = "6ZTfjQGGySUWUxLnB4IUzmZy3AbD8Zkp"; 
$secret = "E2fGPbNy9JzHC93N";  
$initiatorName = "testapi";
$password = "ENCRYPTED_PASSWORD_FROM_SAFARICOM"; // Replace with actual encrypted password
$transactionID = "QCS2FC258A";
$remarks = "Transaction Status Query"; 
$results_url = "https://mfc.ke/callback.php"; 
$timeout_url = "https://mfc.ke/callback.php"; 

// Generate Access Token
$access_token_url = ($env == "live") ? "https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials" : "https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials"; 
$credentials = base64_encode($key . ':' . $secret); 

$ch = curl_init($access_token_url);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Basic " . $credentials]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
$response = curl_exec($ch);
curl_close($ch);

$result = json_decode($response);
$token = isset($result->access_token) ? $result->access_token : "N/A";

if ($token == "N/A") {
    die("Failed to get M-Pesa Access Token. Check your credentials.");
}

// Transaction Status Query
$curl_post_data = array( 
    "Initiator" => $initiatorName, 
    "SecurityCredential" => $password, 
    "CommandID" => "TransactionStatusQuery", 
    "TransactionID" => $transactionID, 
    "PartyA" => $shortcode, 
    "IdentifierType" => $type, 
    "ResultURL" => $results_url, 
    "QueueTimeOutURL" => $timeout_url, 
    "Remarks" => $remarks, 
    "Occasion" => "Transaction Status Query",
); 

$data_string = json_encode($curl_post_data);

$endpoint = ($env == "live") ? "https://api.safaricom.co.ke/mpesa/transactionstatus/v1/query" : "https://sandbox.safaricom.co.ke/mpesa/transactionstatus/v1/query"; 

$ch2 = curl_init($endpoint);
curl_setopt($ch2, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $token",
    "Content-Type: application/json"
]);
curl_setopt($ch2, CURLOPT_POST, 1);
curl_setopt($ch2, CURLOPT_POSTFIELDS, $data_string);
curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
$response = curl_exec($ch2);
curl_close($ch2);

echo "Response: " . $response;

$result = json_decode($response);

if (isset($result->ResponseCode) && $result->ResponseCode === "0") {
    echo "Transaction Verification request Sent SUCCESSFULLY";
} else {
    echo "Verification Request UNSUCCESSFUL";
}

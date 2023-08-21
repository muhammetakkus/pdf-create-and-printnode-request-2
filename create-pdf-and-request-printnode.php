<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/vendor/autoload.php';

// POST request data
$data = json_decode(file_get_contents('php://input'), true);

$html = $data['html'];
$targetPrinter = $data['target_printer'];
$api_key = $data['printnode_key'];
$o = $data['o'] ?? 'P';
$pdf = toPDF($html, $o);

// PrintNode işlemleri
use PrintNode\Credentials\ApiKey;
use PrintNode\Client;
use PrintNode\Entity\PrintJob;

$credentials = new ApiKey($api_key);
$client = new Client($credentials);
$printJob = new PrintJob($client);

$printJob->content = base64_encode($pdf);
$printJob->source = rand() . ' - ' . date('Y-m-d H:i:s');
$printJob->title = rand() . ' - ' . date('Y-m-d H:i:s');
$printJob->options = ['paper' => 'USER', 'copies' => 1];
$printJob->printer = $targetPrinter;
$printJob->contentType = 'pdf_base64';
$printJobId = $client->createPrintJob($printJob);

// return $printJobId;
return true;

// Create PDF - MPDF index.php'de oradaki kodu buraya alıp CURL ile istek atmadan çalıştırınca olmuyor!?
function toPDF($html, $o = 'P') {
     $data = [
        "html" => base64_encode($html),
        "o" => $o
    ];

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://pro.tedisyon.com/pdf/index.php',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
        ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);

    return $response; 
}
?>


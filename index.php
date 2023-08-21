<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/vendor/autoload.php';

$data = json_decode(file_get_contents('php://input'), true);

$data['html'] = base64_decode($data['html']);
$o = @$_GET['o'] ? $_GET['o'] : 'P';

$mpdf = new \Mpdf\Mpdf([
    'mode' => 'utf-8',
    'default_font_size' => $o == 'P' ? 12 : 8,
    'format' => $o == 'P' ? [80, 297] : [80, 100],
    'margin_left' => 0,
    'margin_right' => 0,
    'margin_top' => 0,
    'margin_bottom' => 0,
    'margin_header' => 0,
    'margin_footer' => $o == 'P' ? 5 : 1,
    'falseBoldWeight' => 10,
    'dpi' => 96,
    'orientation' => $o == 'P' ? 'P' : 'L',
]);

$mpdf->WriteHTML($data['html']);
$mpdf->Output();


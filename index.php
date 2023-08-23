<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/vendor/autoload.php';

$data = json_decode(file_get_contents('php://input'), true);

$data['html'] = base64_decode($data['html']);
$o = $data['o'] ? $data['o'] : 'P';

$mpdf = new \Mpdf\Mpdf([
    'mode' => 'utf-8',
    'default_font_size' => $o == 'P' ? 12 : 8,
    'format' => $o == 'P' ? [80, 297] : [80, 100],
    //'format' => $o == 'P' ? [80, 297] : 'A4-L',
    'margin_left' => 0,
    'margin_right' => 0,
    'margin_top' => $o == 'P' ? 5 : 10,
    'margin_bottom' => 0,
    'margin_header' => 0,
    'margin_footer' => $o == 'P' ? 5 : 1,
    'falseBoldWeight' => 10,
    'dpi' => 96,
    //'orientation' => $o == 'P' ? 'P' : 'L',
    'orientation' => $o == 'P' ? 'P' : 'L',
]);

$mpdf->WriteHTML($data['html']);
$mpdf->Output();


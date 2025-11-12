<?php
require __DIR__ . '/vendor/autoload.php';

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

$qr = QrCode::create('¡Hola PUMFEST!')
    ->setSize(250)
    ->setMargin(10);

$writer = new PngWriter();
$result = $writer->write($qr);

// Mostrar directamente en el navegador
header('Content-Type: '.$result->getMimeType());
echo $result->getString();

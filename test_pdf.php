<?php
require 'vendor/autoload.php';

$parser = new \Smalot\PdfParser\Parser();
$pdf    = $parser->parseFile('C:\Users\tes_user\Downloads\Pengajuan_Belanja_TEFA - Sheet1.pdf');
$text   = $pdf->getText();
echo $text;

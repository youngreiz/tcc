<?php
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
require __DIR__ .'/dados.php';

echo json_encode(array_slice(lerRanking(), 0, 10),JSON_UNESCAPED_UNICODE);

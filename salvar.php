<?php
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'mensagem' => 'Método não permitido.']);
    exit;
}

require __DIR__ . '/dados.php';

$entrada = json_decode(file_get_contents('php://input'), true);
$nome = trim((string)($entrada['nome'] ?? ''));
$valores = $entrada['valores'] ?? [];

if ($nome === '') {
    http_response_code(422);
    echo json_encode(['ok' => false, 'mensagem' => 'Digite seu nome.']);
    exit;
}

if (mb_strlen($nome) > 30) {
    http_response_code(422);
    echo json_encode(['ok' => false, 'mensagem' => 'O nome pode ter no máximo 30 caracteres.']);
    exit;
}

if (!is_array($valores) || count($valores) !== count($provas)) {
    http_response_code(422);
    echo json_encode(['ok' => false, 'mensagem' => 'Preencha as dez provas.']);
    exit;
}

$total = 0;
foreach ($provas as $i => $prova) {
    if (!isset($valores[$i]) || !is_numeric($valores[$i])) {
        http_response_code(422);
        echo json_encode(['ok' => false, 'mensagem' =>'Preencha todas as provas com valores válidos.']);
        exit;
    }
    $v = (float)$valores[$i];
    if ($v <= 0) {
        http_response_code(422);
        echo json_encode(['ok' => false, 'mensagem' => 'Todos os resultados precisam ser maiores que zero.']);
        exit;
    }
    $total += calcularPontos($prova, $v);
}

$dir = __DIR__ . '/data';
if (!is_dir($dir)) mkdir($dir, 0775, true);
$arq = $dir . '/ranking.json';

$ranking = [];
if (is_file($arq)) {
    $conteudo = file_get_contents($arq);
    $lido = json_decode($conteudo, true);
    if (is_array($lido)) $ranking = $lido;
}

$ranking[] = [
'nome' => $nome,
'pontos' => $total,
'data' => date('c')
];

usort($ranking, function ($a, $b) {
    return ((int)$b['pontos']) <=> ((int)$a['pontos']);
});

$ranking = array_slice($ranking, 0, 100);
file_put_contents($arq, json_encode($ranking, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT), LOCK_EX);

echo json_encode(['ok' => true, 'mensagem' => 'Pontuação salva no ranking!', 'pontos' => $total]);

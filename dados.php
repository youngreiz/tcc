<?php
// Fonte única dos dados do site.
// t: "t" = tempo (menor é melhor) | "c" = salto (metros, convertido em cm) | "m" = lançamento em metros
$provas = [
    ['nome' => '100 m rasos',          'dia' => 1, 'desc' => 'Velocidade',                't' => 't', 'A' => 25.4347, 'B' => 18,   'C' => 1.81, 'ex' => 10.9, 'u' => 's'],
    ['nome' => 'Salto em distância',   'dia' => 1, 'desc' => 'Velocidade e técnica',      't' => 'c', 'A' => 0.14354, 'B' => 220,  'C' => 1.4,  'ex' => 7.4,  'u' => 'm'],
    ['nome' => 'Arremesso do peso',    'dia' => 1, 'desc' => 'Força',                     't' => 'm', 'A' => 51.39,   'B' => 1.5,  'C' => 1.05, 'ex' => 14.5, 'u' => 'm'],
    ['nome' => 'Salto em altura',      'dia' => 1, 'desc' => 'Explosão e coordenação',    't' => 'c', 'A' => 0.8465,  'B' => 75,   'C' => 1.42, 'ex' => 2.03, 'u' => 'm'],
    ['nome' => '400 m rasos',          'dia' => 1, 'desc' => 'Resistência de velocidade', 't' => 't', 'A' => 1.53775, 'B' => 82,   'C' => 1.81, 'ex' => 48.5, 'u' => 's'],
    ['nome' => '110 m com barreiras',  'dia' => 2, 'desc' => 'Ritmo e técnica',           't' => 't', 'A' => 5.74352, 'B' => 28.5, 'C' => 1.92, 'ex' => 14.4, 'u' => 's'],
    ['nome' => 'Lançamento do disco',  'dia' => 2, 'desc' => 'Rotação e força',           't' => 'm', 'A' => 12.91,   'B' => 4,    'C' => 1.1,  'ex' => 44,   'u' => 'm'],
    ['nome' => 'Salto com vara',       'dia' => 2, 'desc' => 'Técnica e ginástica',       't' => 'c', 'A' => 0.2797,  'B' => 100,  'C' => 1.35, 'ex' => 4.9,  'u' => 'm'],
    ['nome' => 'Lançamento do dardo',  'dia' => 2, 'desc' => 'Velocidade e potência',     't' => 'm', 'A' => 10.14,   'B' => 7,    'C' => 1.08, 'ex' => 60,   'u' => 'm'],
    ['nome' => '1.500 m rasos',        'dia' => 2, 'desc' => 'Resistência',               't' => 't', 'A' => 0.03768, 'B' => 480,  'C' => 1.85, 'ex' => 265,  'u' => 's'],
];

$recordes = [
    ['atleta' => 'Kevin Mayer',  'pais' => 'França',         'pontos' => '9.126', 'local' => 'Talence, 2018'],
    ['atleta' => 'Ashton Eaton', 'pais' => 'Estados Unidos', 'pontos' => '9.045', 'local' => 'Pequim, 2015'],
    ['atleta' => 'Roman Šebrle', 'pais' => 'Tchéquia',       'pontos' => '9.026', 'local' => 'Götzis, 2001'],
];

function calcularPontos(array $p, float $v): int
{
    if ($v <= 0) return 0;
    if ($p['t'] === 't') {
     $r = $v < $p['B'] ? $p['A'] * pow($p['B'] - $v, $p['C']) : 0;
    } else {
    $x = $p['t'] === 'c' ? $v * 100 : $v;
     $r = $x > $p['B'] ? $p['A'] * pow($x - $p['B'], $p['C']) : 0;
    }
    return (int) floor($r);
}

function lerRanking():array
{
    $arq = __DIR__ . '/data/ranking.json';
    if (!is_file($arq)) return [];
    $lista = json_decode(file_get_contents($arq), true);
    return is_array($lista) ? $lista : [];
}

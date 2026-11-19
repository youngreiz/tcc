<?php require __DIR__ . '/dados.php'; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="Site educativo sobre decatlo com dez provas, calculadora de pontos, ranking e recordes.">
<title>DECATLO — 10 provas. 2 dias. 1 pontuação.</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<nav aria-label="Principal">
  <a href="#provas">Provas</a><a href="#calculadora">Calculadora</a><a href="#ranking">Ranking</a><a href="#recordes">Recordes</a><a href="#historia">História</a>
</nav>
<header class="hero"><div class="wrap">
  <span class="eyebrow">Atletismo · prova combinada</span>
  <h1>Decatlo</h1>
  <p>Dez provas em dois dias. Velocidade, força, técnica e resistência transformadas em uma única pontuação.</p>
  <div class="hero-actions"><a class="btn primary" href="#calculadora">Calcular meus pontos</a><a class="btn secondary" href="#provas">Conhecer as provas</a></div>
</div></header>
<main>
<section id="provas"><div class="wrap"><h2>As dez provas</h2><p class="lead">A competição é dividida em dois dias. Cada marca é convertida em pontos e a soma das dez provas forma a pontuação final.</p><div class="days">
<?php foreach ([1=>'Primeiro dia',2=>'Segundo dia'] as $dia=>$titulo): ?><div class="day"><h3><?= $titulo ?></h3><ol start="<?= $dia===1?1:6 ?>"><?php foreach($provas as $p): if($p['dia']!==$dia) continue; ?><li><?= htmlspecialchars($p['nome']) ?><small><?= htmlspecialchars($p['desc']) ?></small></li><?php endforeach; ?></ol></div><?php endforeach; ?>
</div></div></section>
<section id="calculadora"><div class="wrap"><h2>Calculadora de pontos</h2><p class="lead">Digite seus resultados. Tempos são informados em segundos; saltos e lançamentos, em metros.</p><div class="calc"><div class="grid" id="campos"></div><aside 
class="total"><span class="label">Sua pontuação</span><strong 
id="total">0</strong><span>pontos no total</span><button type="button" id="exemplo">Preencher exemplo</button><button type="button"                                                                                                                                                                                                                                                                                                                                                                                                                              
id="limpar">Limpar tudo</button></aside><div class="salvar"><label for="nome">Seu nome<input type="text" 
id="nome" maxlength="30" autocomplete="name" placeholder="Digite seu nome"></label><button type="button" id="salvar">Salvar no ranking</button><p 
id="msg" role="status" aria-live="polite"></p></div></div><p class="hint">Dica: use o botão “Preencher exemplo” para testar a calculadora rapidamente.</p></div></section>
<section id="ranking"><div class="wrap"><h2>Ranking do site</h2><p class="lead">As dez maiores pontuações enviadas pela calculadora.</p><ol class="ranking" 
id="lista-ranking"><?php $top=array_slice(lerRanking(),0,10); if(!$top): ?><li class="vazio">Ninguém entrou no ranking ainda.</li><?php else: foreach($top as $i=>$r): ?><li><span><b><?= $i+1 ?>º</b><?= htmlspecialchars($r['nome']) ?></span><strong><?= number_format((int)$r['pontos'],0,',','.') ?></strong></li><?php endforeach; endif; ?></ol></div></section>
<section id="recordes"><div class="wrap"><h2>Melhores marcas</h2><p class="lead">Marcas históricas apresentadas pelos dados deste projeto.</p><div class="tw"><table><thead><tr><th>Atleta</th><th>País</th><th>Pontos</th><th>Local e ano</th></tr></thead><tbody><?php foreach($recordes as $r): ?><tr><td><?= htmlspecialchars($r['atleta']
) ?></td><td><?= htmlspecialchars($r['pais']) ?></td><td><?= htmlspecialchars($r['pontos']) ?></td><td><?= htmlspecialchars($r['local']) ?></td></tr><?php endforeach; ?></tbody></table></div></div></section>
<section id="historia"><div class="wrap"><h2>Um pouco de história</h2><div class="history"><div class="info-card"><strong>1912</strong><p>O decatlo moderno estreou nos Jogos Olímpicos de Estocolmo. O formato reúne dez eventos de atletismo ao longo de dois dias.</p></div><div 
class="info-card"><strong>10</strong><p>provas, uma pontuação final e uma combinação de capacidades que torna o decatlo uma das provas mais completas do atletismo.</p></div></div><a class="back" href="#">↑ Voltar ao topo</a></div></section>
</main>
<footer><div class="wrap">Site educativo sobre decatlo · Calculadora baseada nos coeficientes definidos em <code>dados.php</code> · © <?= date('Y') ?></div></footer>
<script>const PROVAS = <?= json_encode($provas, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) ?>;</script><script src="script.js"></script>
</body></html>

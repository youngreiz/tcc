const campos = document.getElementById('campos');
const totalEl = document.getElementById('total');
const nomeEl = document.getElementById('nome');
const msgEl = document.getElementById('msg');
const btnExemplo = document.getElementById('exemplo');
const btnLimpar = document.getElementById('limpar');
const btnSalvar = document.getElementById('salvar');

const formatar = n => new Intl.NumberFormat('pt-BR').format(Number(n) || 0);

const pontosDaProva = (p, v) => {
if (!Number.isFinite(v) || v <= 0) return 0;
if (p.t === 't') return v < p.B ? Math.floor(p.A * Math.pow(p.B - v, p.C)) : 0;
const x = p.t === 'c' ? v * 100 : v;
return x > p.B ? Math.floor(p.A * Math.pow(x - p.B, p.C)) : 0;
};


function criarCampos(){   parte de colocar segundos ou metros 
campos.innerHTML = PROVAS.map((p, i) => `
<label class="campo">
<span>${i + 1}. ${p.nome}</span>
<small>${p.desc} · ${p.u === 's' ? 'segundos' : 'metros'}</small>
<input type="number" step="0.01" min="0.01" inputmode="decimal" data-index="${i}" placeholder="Ex.: ${p.ex}" aria-label="Resultado em ${p.nome}">
</label>
`).join('');
campos.querySelectorAll('input').forEach(input => input.addEventListener('input', calcular));
}

function valores(){      valores das provas 
return [...campos.querySelectorAll('input')].map(input => Number(input.value));
}

function calcular(){     calculadora funcional do site 
const vals = valores();
const total = PROVAS.reduce((s, p, i) => s + pontosDaProva(p, vals[i]), 0);
totalEl.textContent = formatar(total);
return total;
}

function preencherExemplo(){   alteração 
PROVAS.forEach((p, i) => campos.querySelector(`[data-index="${i}"]`).value = p.ex);
calcular();
msgEl.textContent = 'Exemplo preenchido. Agora você pode alterar qualquer resultado.';
}

function limpar(){     //limpar valores,tempo ou metro//
campos.querySelectorAll('input').forEach(i => {
i.value = '';
i.classList.remove('invalid');
});
nomeEl.value = '';
totalEl.textContent = '0';
msgEl.textContent = '';
}

async function salvar(){   salva ao ranking top10 
const nome = nomeEl.value.trim();
const vals = valores();

if (!nome){
msgEl.textContent = 'Digite seu nome antes de salvar.';
nomeEl.focus();
return;
}
if (nome.length > 30){
msgEl.textContent = 'O nome pode ter no máximo 30 caracteres.';
return;
}

const inputs = [...campos.querySelectorAll('input')];
inputs.forEach(i => i.classList.toggle('invalid', !i.value || Number(i.value) <= 0));

if (vals.some(v => !v || v <= 0)){
msgEl.textContent = 'Preencha as dez provas com valores maiores que zero.';
return;
}

btnSalvar.disabled = true;
msgEl.textContent = 'Salvando sua pontuação...';

try{
const res = await fetch('salvar.php',{
method:'POST',
headers:{'Content-Type':'application/json'},
body:JSON.stringify({nome,valores:vals})
});

const data = await res.json();
if (!res.ok || !data.ok) throw new Error(data.mensagem || 'Não foi possível salvar.');

msgEl.textContent = `${data.mensagem} Você fez ${formatar(data.pontos)} pontos.`;
await carregarRanking();
document.getElementById('ranking').scrollIntoView({behavior:'smooth'});
}catch(e){
msgEl.textContent = e.message || 'Erro ao salvar.';
}finally{
btnSalvar.disabled = false;
}
}

async function carregarRanking(){  ranking top10 pontos baixos ao mais alto 
const lista = document.getElementById('lista-ranking');

try{
const res = await fetch('ranking.php',{cache:'no-store'});
if (!res.ok) throw new Error();

const ranking = await res.json();
lista.innerHTML = ranking.length ? '' : '<li class="vazio">Ninguém entrou no ranking ainda.</li>';

ranking.forEach((r,i) => {
const li = document.createElement('li');
const span = document.createElement('span');
const pos = document.createElement('b');
pos.textContent = `${i+1}º`;
span.append(pos, ` ${r.nome}`);

const strong = document.createElement('strong');
strong.textContent = formatar(r.pontos);
li.append(span,strong);
lista.appendChild(li);
});
}catch{
/* mantém o ranking renderizado pelo PHP */
}
}

btnExemplo.addEventListener('click', preencherExemplo);
btnLimpar.addEventListener('click', limpar);
btnSalvar.addEventListener('click', salvar);

criarCampos();
calcular();
carregarRanking();

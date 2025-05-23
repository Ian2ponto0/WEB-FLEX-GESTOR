import { getList, brl } from '../common/common.js';

// Carrega listas
const receitas = getList('financeiro').filter(l => l.tipo === 'receita');
const despesas = getList('financeiro').filter(l => l.tipo === 'despesa');
const clientes = getList('clientes');
const produtos = getList('produtos');
const funcionarios = getList('funcionarios');
const projetos = getList('projetos');
const vendas = getList('vendas');

// Widgets
const totalReceita = receitas.reduce((sum, r) => sum + parseFloat(r.valor), 0);
const totalDespesa = despesas.reduce((sum, d) => sum + parseFloat(d.valor), 0);
const lucro = totalReceita - totalDespesa;
const vendasMes = vendas.filter(v => {
  const dt = new Date(v.data);
  const hoje = new Date();
  return dt.getMonth() === hoje.getMonth() &&
         dt.getFullYear() === hoje.getFullYear();
}).length;

document.getElementById('w-receita').innerText = brl(totalReceita);
document.getElementById('w-despesa').innerText = brl(totalDespesa);
document.getElementById('w-lucro').innerText   = brl(lucro);
document.getElementById('w-clientes').innerText = clientes.length;
document.getElementById('w-produtos').innerText = produtos.length;
document.getElementById('w-func').innerText      = funcionarios.length;
document.getElementById('w-projetos').innerText  = projetos.filter(p => p.status === 'ativo').length;
document.getElementById('w-vendas').innerText    = vendasMes;

// Helper para meses
const meses = ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'];

// 1) Faturamento Mensal (Receita)
const mensalReceita = new Array(12).fill(0);
receitas.forEach(r => {
  const m = new Date(r.data).getMonth();
  mensalReceita[m] += parseFloat(r.valor);
});
new Chart(
  document.getElementById('faturamentoChart'),
  {
    type: 'line',
    data: { labels: meses, datasets: [{
      label: 'Receita',
      data: mensalReceita,
      borderColor: '#00BFFF',
      fill: false,
      tension: 0.4
    }]},
    options: { responsive:true,
      plugins:{legend:{labels:{color:'white'}}},
      scales:{ x:{ticks:{color:'white'}}, y:{ticks:{color:'white'}} }
    }
  }
);

// 2) Receitas × Despesas
const mensalDespesa = new Array(12).fill(0);
despesas.forEach(d => {
  const m = new Date(d.data).getMonth();
  mensalDespesa[m] += parseFloat(d.valor);
});
new Chart(
  document.getElementById('receitaDespesaChart'),
  {
    type: 'bar',
    data: {
      labels: meses,
      datasets: [
        { label: 'Receitas', data: mensalReceita, backgroundColor: '#00BFFF' },
        { label: 'Despesas', data: mensalDespesa, backgroundColor: '#FF69B4' }
      ]
    },
    options:{ responsive:true,
      plugins:{legend:{labels:{color:'white'}}},
      scales:{ x:{ticks:{color:'white'}}, y:{ticks:{color:'white'}} }
    }
  }
);

// 3) Top 5 Vendas por Produto (quantidade)
const vendasPorProduto = {};
vendas.forEach(v => v.itens.forEach(item => {
  vendasPorProduto[item.nome] = (vendasPorProduto[item.nome] || 0) + item.qtd;
}));
const top5 = Object.entries(vendasPorProduto)
  .sort((a,b)=>b[1]-a[1])
  .slice(0,5);
new Chart(
  document.getElementById('vendasProdutoChart'),
  {
    type: 'pie',
    data: {
      labels: top5.map(t=>t[0]),
      datasets:[{ data: top5.map(t=>t[1]), backgroundColor: ['#9D00FF','#00BFFF','#FFC300','#FF69B4','#7a00cc'] }]
    },
    options:{ plugins:{legend:{labels:{color:'white'}}} }
  }
);

// 4) Status de Projetos
const contaProj = projetos.reduce((acc,p) => {
  acc[p.status] = (acc[p.status]||0)+1;
  return acc;
}, {});
new Chart(
  document.getElementById('projetosStatusChart'),
  {
    type:'doughnut',
    data:{
      labels: Object.keys(contaProj),
      datasets:[{ data: Object.values(contaProj), backgroundColor:['#00BFFF','#FF69B4'] }]
    },
    options:{ plugins:{legend:{labels:{color:'white'}}} }
  }
);

// 5) Funcionários por Departamento
const deptCount = {};
funcionarios.forEach(f => {
  const d = f.departamento || '—';
  deptCount[d] = (deptCount[d]||0)+1;
});
new Chart(
  document.getElementById('funcDeptChart'),
  {
    type:'bar',
    data:{
      labels: Object.keys(deptCount),
      datasets:[{ label:'Funcionários', data:Object.values(deptCount), backgroundColor:'#9D00FF' }]
    },
    options:{ responsive:true,
      plugins:{legend:{display:false}},
      scales:{ x:{ticks:{color:'white'}}, y:{ticks:{color:'white'}} }
    }
  }
);

// 6) Vendas por Categoria (produto.categoria)
const categoriaCount = {};
vendas.forEach(v => v.itens.forEach(item => {
  const prod = produtos.find(p=>p.nome===item.nome);
  const cat = prod?.categoria || '—';
  categoriaCount[cat] = (categoriaCount[cat]||0) + item.qtd;
}));
new Chart(
  document.getElementById('vendasCategoriaChart'),
  {
    type:'doughnut',
    data:{
      labels:Object.keys(categoriaCount),
      datasets:[{ data:Object.values(categoriaCount), backgroundColor:['#FFC300','#00BFFF','#9D00FF','#FF69B4'] }]
    },
    options:{ plugins:{legend:{labels:{color:'white'}}} }
  }
);

// Tabela de últimas vendas
const tbl = document.querySelector('#tbl-recent-sales tbody');
vendas
  .sort((a,b)=>new Date(b.data)-new Date(a.data))
  .slice(0,5)
  .forEach(v => {
    const tr = document.createElement('tr');
    const itens = v.itens.map(i=>`${i.nome}(${i.qtd})`).join(', ');
    tr.innerHTML = `
      <td>${new Date(v.data).toLocaleDateString()}</td>
      <td>${itens}</td>
      <td>${v.total}</td>
    `;
    tbl.appendChild(tr);
  });

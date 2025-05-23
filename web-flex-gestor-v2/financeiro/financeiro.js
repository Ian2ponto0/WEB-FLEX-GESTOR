// financeiro/financeiro.js
import { brl } from '../common/common.js';

const API='financeiro';
const form=document.getElementById('form-fin');
const tbody=document.querySelector('#tbl-fin tbody');
const ctx=document.getElementById('chart-fin');

async function fetchFin(){
  const res=await fetch(`${API}/list.php`);
  return res.ok?res.json():[];
}
async function createFin(data){
  const res=await fetch(`${API}/create.php`,{
    method:'POST',
    headers:{'Content-Type':'application/json'},
    body:JSON.stringify(data)
  });
  return res.json();
}

function renderTable(list){
  tbody.innerHTML='';
  list.forEach(f=>{
    const tr=document.createElement('tr');
    tr.innerHTML=`
      <td>${new Date(f.data_mov).toLocaleString()}</td>
      <td>${f.tipo}</td>
      <td>${f.descricao}</td>
      <td>${brl(parseFloat(f.valor))}</td>
      <td><button class="btn-view" data-id="${f.id}">Ver</button></td>
    `;
    tbody.appendChild(tr);
  });
}

function renderChart(list){
  const meses=['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'];
  const rec=new Array(12).fill(0), desp=new Array(12).fill(0);
  list.forEach(f=>{
    const m=new Date(f.data_mov).getMonth();
    if(f.tipo==='receita') rec[m]+=parseFloat(f.valor);
    else desp[m]+=parseFloat(f.valor);
  });
  new Chart(ctx,{
    type:'bar',
    data:{ labels:meses, datasets:[
      {label:'Receitas',data:rec,backgroundColor:'#00BFFF'},
      {label:'Despesas',data:desp,backgroundColor:'#FF69B4'}
    ]},
    options:{responsive:true,plugins:{legend:{labels:{color:'white'}}},
      scales:{x:{ticks:{color:'white'}},y:{ticks:{color:'white'}}}
    }
  });
}

async function render(){
  const list=await fetchFin();
  renderTable(list);
  renderChart(list);
}

form.addEventListener('submit',async e=>{
  e.preventDefault();
  const data={
    dataMov:document.getElementById('dataMov').value,
    tipo:document.getElementById('tipo').value,
    categoriaFin:document.getElementById('categoriaFin').value,
    descricao:document.getElementById('descricao').value,
    valor:document.getElementById('valor').value,
    formaPagamento:document.getElementById('formaPagamento').value,
    centroCusto:document.getElementById('centroCusto').value,
    obs:document.getElementById('obs').value
  };
  await createFin(data);
  form.reset();
  render();
});

render();

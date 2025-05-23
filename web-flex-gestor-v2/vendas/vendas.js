// vendas/vendas.js
import { brl } from '../common/common.js';

const API='vendas';
const form=document.getElementById('form-venda');
const clienteSel=document.getElementById('clienteId');
const prodSearch=document.getElementById('prodSearch');
const qtdItem=document.getElementById('qtdItem');
const addItemBtn=document.getElementById('addItem');
const results=document.getElementById('results');
const tblItens=document.querySelector('#tbl-itens tbody');
const totalBrutoEl=document.getElementById('totalBruto');
const descontosEl=document.getElementById('descontos');
const totalLiquidoEl=document.getElementById('totalLiquido');
const tblVendas=document.querySelector('#tbl-vendas tbody');

let produtosList=[], clientesList=[], cart=[];

async function fetchProdutos(){
  const res=await fetch('../produtos/list.php');
  produtosList = res.ok?await res.json():[];
}
async function fetchClientes(){
  const res=await fetch('../clientes/list.php');
  clientesList = res.ok?await res.json():[];
}
async function fetchVendas(){
  const res=await fetch(`${API}/list.php`);
  return res.ok?res.json():[];
}

function renderClientes(){
  clienteSel.innerHTML='<option value="">Sem cliente</option>';
  clientesList.forEach(c=>{
    clienteSel.innerHTML += `<option value="${c.id}">${c.nome}</option>`;
  });
}

function searchProd(term){
  return produtosList.filter(p=>p.nome.toLowerCase().includes(term.toLowerCase()));
}

prodSearch.addEventListener('input',()=>{
  results.innerHTML='';
  const list = searchProd(prodSearch.value);
  list.forEach(p=>{
    const div=document.createElement('div');
    div.innerText=`${p.nome} (${brl(parseFloat(p.preco_venda))})`;
    div.style.cursor='pointer';
    div.onclick=()=> addToCart(p);
    results.appendChild(div);
  });
});

function addToCart(p){
  const existing=cart.find(c=>c.id===p.id);
  if(existing) existing.qtd++;
  else cart.push({...p,qtd:parseInt(qtdItem.value,10)});
  renderCart();
  prodSearch.value=''; results.innerHTML='';
}

function renderCart(){
  tblItens.innerHTML=''; let totalBruto=0;
  cart.forEach((c,i)=>{
    const sub=c.qtd*c.preco_venda; totalBruto+=sub;
    const tr=document.createElement('tr');
    tr.innerHTML=`
      <td>${c.nome}</td>
      <td>${c.qtd}</td>
      <td>${brl(parseFloat(c.preco_venda))}</td>
      <td>${brl(sub)}</td>
      <td><button data-i="${i}">❌</button></td>
    `;
    tblItens.appendChild(tr);
    tr.querySelector('button').onclick=()=>{cart.splice(i,1);renderCart();};
  });
  totalBrutoEl.value = brl(totalBruto);
  computeLiquido();
}

function computeLiquido(){
  const bruto = parseFloat(totalBrutoEl.value.replace(/[^0-9\-\.]/g,''))||0;
  const desc = parseFloat(descontosEl.value)||0;
  totalLiquidoEl.value = brl(bruto - desc);
}

descontosEl.addEventListener('input',computeLiquido);

form.addEventListener('submit',async e=>{
  e.preventDefault();
  const data={
    dataVenda:document.getElementById('dataVenda').value,
    clienteId:clienteSel.value||null,
    itens:cart.map(c=>({produto_id:c.id,quantidade:c.qtd,preco_unit:c.preco_venda})),
    totalBruto:parseFloat(totalBrutoEl.value.replace(/[^0-9\-\.]/g,'')),
    descontos:parseFloat(descontosEl.value)||0,
    totalLiquido:parseFloat(totalLiquidoEl.value.replace(/[^0-9\-\.]/g,'')),
    formaPagamento:document.getElementById('formaPagamento').value,
    obs:document.getElementById('obs').value
  };
  await fetch(`${API}/create.php`,{
    method:'POST',
    headers:{'Content-Type':'application/json'},
    body:JSON.stringify(data)
  });
  form.reset(); cart=[]; renderCart(); renderVendas();
});

async function renderVendas(){
  tblVendas.innerHTML='';
  const list=await fetchVendas();
  list.forEach(v=>{
    const tr=document.createElement('tr');
    const cliente = clientesList.find(c=>c.id==v.cliente_id);
    tr.innerHTML=`
      <td>${new Date(v.data_venda).toLocaleString()}</td>
      <td>${cliente?cliente.nome:'–'}</td>
      <td>${brl(parseFloat(v.total_liquido))}</td>
      <td><button data-id="${v.id}" class="btn-view">Ver</button></td>
    `;
    tblVendas.appendChild(tr);
  });
}

async function init(){
  await fetchProdutos();
  await fetchClientes();
  renderClientes();
  render();
  renderVendas();
}

init();

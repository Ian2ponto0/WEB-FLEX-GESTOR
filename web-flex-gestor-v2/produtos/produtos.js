// produtos/produtos.js
import { brl } from '../common/common.js';
const API='produtos';
const form=document.getElementById('form-produto');
const tbody=document.querySelector('#tbl-produtos tbody');
const btnAdv=document.getElementById('btn-adv');
const advSection=document.getElementById('adv-section');
const attrsContainer=document.getElementById('attrs-container');
let attrIdx=0;

btnAdv.addEventListener('click',()=>{
  advSection.style.display=advSection.style.display==='none'?'block':'none';
});

document.getElementById('add-attr').addEventListener('click',()=>{
  const idx=attrIdx++;
  const div=document.createElement('div');
  div.innerHTML=`
    <input placeholder="Nome do atributo" data-name="${idx}" style="width:45%;margin-right:5px;">
    <input placeholder="Valor" data-val="${idx}" style="width:45%;">
  `;
  attrsContainer.appendChild(div);
});

async function fetchProdutos(){
  const res=await fetch(`${API}/list.php`);
  return res.ok?res.json():[];
}
async function createProduto(data){
  const res=await fetch(`${API}/create.php`,{
    method:'POST',
    headers:{'Content-Type':'application/json'},
    body:JSON.stringify(data)
  });
  return res.json();
}
function openModal(html){
  document.getElementById('modal-body').innerHTML=html;
  document.getElementById('prod-modal').style.display='block';
}
document.getElementById('close-modal').onclick=()=>document.getElementById('prod-modal').style.display='none';

async function render(){
  tbody.innerHTML='';
  const list=await fetchProdutos();
  list.forEach(p=>{
    const tr=document.createElement('tr');
    tr.innerHTML=`
      <td>${p.codigo}</td><td>${p.nome}</td>
      <td>${p.categoria||'–'}</td>
      <td>${p.estoque_atual}</td>
      <td>${brl(parseFloat(p.preco_venda))}</td>
      <td><button class="btn-detail" data-id="${p.id}">Ver</button></td>
    `;
    tbody.appendChild(tr);
  });
  document.querySelectorAll('.btn-detail').forEach(btn=>{
    btn.onclick=async()=>{
      const prod=(await fetchProdutos()).find(x=>x.id==btn.dataset.id);
      let html=`
        <h2>${prod.nome}</h2>
        <p><strong>Código:</strong> ${prod.codigo}</p>
        <p><strong>Descrição:</strong> ${prod.descricao}</p>
        <p><strong>Fornecedor:</strong> ${prod.fornecedor}</p>
        <p><strong>Preço Venda:</strong> ${brl(parseFloat(prod.preco_venda))}</p>
        <p><strong>Estoque Atual:</strong> ${prod.estoque_atual}</p>
        <h3>Atributos</h3><ul>`;
      (prod.atributos?JSON.parse(prod.atributos):[]).forEach(a=>{
        html+=`<li>${a.name}: ${a.value}</li>`;
      });
      html+='</ul>';
      html+=`<p><strong>Observações:</strong> ${prod.obs}</p>`;
      openModal(html);
    };
  });
}

form.addEventListener('submit',async e=>{
  e.preventDefault();
  const attrs=Array.from(attrsContainer.children).map(div=>{
    const idx=div.querySelector('[data-name]').dataset.name;
    return {name:div.querySelector(`[data-name="${idx}"]`).value,
            value:div.querySelector(`[data-val="${idx}"]`).value};
  });
  const data={
    codigo:document.getElementById('codigo').value,
    nome:document.getElementById('nome').value,
    descricao:document.getElementById('descricao').value,
    categoria:document.getElementById('categoria').value,
    subcategoria:document.getElementById('subcategoria').value,
    fornecedor:document.getElementById('fornecedor').value,
    ncm_sh:document.getElementById('ncm_sh').value,
    modelo:document.getElementById('modelo').value,
    unidade:document.getElementById('unidade').value,
    peso_gramas:document.getElementById('peso_gramas').value,
    estoqueInicial:document.getElementById('estoqueInicial').value,
    estoqueAtual:document.getElementById('estoqueAtual').value,
    estoqueMinimo:document.getElementById('estoqueMinimo').value,
    precoCusto:document.getElementById('precoCusto').value,
    precoVenda:document.getElementById('precoVenda').value,
    atributos:attrs,
    observacoes:document.getElementById('observacoes')?.value
  };
  await createProduto(data);
  form.reset(); attrsContainer.innerHTML=''; attrIdx=0; advSection.style.display='none';
  render();
});

render();

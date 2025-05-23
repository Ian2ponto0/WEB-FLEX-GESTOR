
/**
 * Utility: get array from localStorage
 */
export function getList(key){
    return JSON.parse(localStorage.getItem(key) || "[]");
}
export function saveList(key, list){
    localStorage.setItem(key, JSON.stringify(list));
}
/**
 * Add item to list in localStorage
 */
export function addItem(key, item){
    const list=getList(key);
    list.push(item);
    saveList(key,list);
}
/**
 * Format currency BRL
 */
export function brl(value){
    return value.toLocaleString('pt-BR',{style:'currency',currency:'BRL'});
}

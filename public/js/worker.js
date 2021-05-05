const edits = document.querySelectorAll('.worker-edit')
const editWorks = document.querySelectorAll('.work-edit')
const delBtn = document.querySelectorAll('.worker-delete')
const delWorkBtn = document.querySelectorAll('.work-delete')
const delPostBtn = document.querySelectorAll('.post-delete')
const delForm = document.querySelector('#worker-delete')
const delWorkForm = document.querySelector('#work-delete')
const delPostForm = document.querySelector('#post-delete')
const editForm = document.querySelector('#work-edit')
const downloadBtn = document.querySelectorAll('.download')
//Delete Worker Handler
function deleteHandler(item) {
    const id = item.parentNode.parentNode.parentNode.querySelector('input[name="id"]')
    const first_name = item.parentNode.parentNode.parentNode.querySelector('input[name="first_name"]')
    const last_name = item.parentNode.parentNode.parentNode.querySelector('input[name="last_name"]')
    const father_name = item.parentNode.parentNode.parentNode.querySelector('input[name="father_name"]')
    const label = delForm.querySelector('label')
    const input = delForm.querySelector('input[name="id"]')
    input.value = id.value
    label.innerHTML = 'Вы действительно хотите уволить сотрудника ' + first_name.value + ' ' + last_name.value + ' ' + father_name.value + '?'
}
//Delete Post Handler
function deletePostHandler(item) {
    const id = item.parentNode.parentNode.parentNode.querySelector('input[name="id"]')
    const name = item.parentNode.parentNode.parentNode.querySelector('.name')
    const label = delPostForm.querySelector('label')
    const input = delPostForm.querySelector('#delete-id')
    console.log(name)
    input.value = id.value
    label.innerHTML = 'Вы действительно хотите удалить должность ' + name.innerHTML + '?'
}
//Delete Work Handler
function deleteWorkHandler(item) {
    const labelWork = item.parentNode.querySelector('label[for="works"]')
    const label = delWorkForm.querySelector('label')
    const input = delWorkForm.querySelector('input[name="id"]')
    input.value = labelWork.dataset.id
    label.innerHTML = 'Вы действительно хотите удалить работу ' + labelWork.innerHTML + '?'
}
//Visualization workers components for edits
function editHandler(item) {
    item.parentNode.querySelector('.input-group').classList.toggle('visually-hidden')
}
//Edits data name Work
function editWorkHandler(item) {
    const id = item.parentNode.querySelector('label[data-id]')
    const name = editForm.querySelector('input[name="name"]')
    const price = editForm.querySelector('input[name="price"]')
    const input = editForm.querySelector('input[name="id"]')
    input.value = id.dataset.id
    name.value = id.innerHTML
    price.value = id.dataset.price
}
//Data in download modal
function downloadHandler(item) {
    const url = item.dataset.contract
    const form = document.querySelector('#downloadForm')
    const contract = form.querySelector('input[name="contract"]')
    contract.value=url
    form.submit()
}
//Печать
function print() {
    const print = document.getElementById('print').cloneNode(true)
    if(print.querySelector('#create')) print.querySelector('#create').remove()
    print.querySelectorAll('input[name="works[]"]').forEach(function (item){
       if(item.getAttribute('checked')!=='checked') item.parentNode.remove()
    })
    print.querySelectorAll('.worker-edit').forEach(function (item){
        item.remove()
    })
    print.querySelectorAll('.work-edit').forEach(function (item){
        item.remove()
    })
    print.querySelectorAll('.work-delete').forEach(function (item){
        item.remove()
    })
    print.querySelectorAll('.btns').forEach(function (item){
        item.remove()
    })
    print.querySelectorAll('input').forEach(function (item){
        item.remove()
    })
    print.querySelectorAll('select').forEach(function (item){
        item.remove()
    })
    CallPrint(print.innerHTML)
}
//Initialization
function init() {
    edits.forEach(function(item) {
        item.addEventListener('click', () => { editHandler(item) })
    })
    editWorks.forEach(function(item) {
        item.addEventListener('click', () => { editWorkHandler(item) })
    })
    delBtn.forEach(function(item) {
        item.addEventListener('click', () => { deleteHandler(item) })
    })
    delPostBtn.forEach(function(item) {
        item.addEventListener('click', () => { deletePostHandler(item) })
    })
    delWorkBtn.forEach(function(item) {
        item.addEventListener('click', () => { deleteWorkHandler(item) })
    })
    downloadBtn.forEach(function(item) {
        item.addEventListener('click', () => { downloadHandler(item) })
    })
}

init()

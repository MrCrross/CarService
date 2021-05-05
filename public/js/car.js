const edits = document.querySelectorAll('.car-edit')
const delBtn = document.querySelectorAll('.car-delete')
const delForm = document.querySelector('#car-delete')

function deleteHandler(item) {
    const id = item.parentNode.parentNode.parentNode.querySelector('input[name="id"]')
    const model = item.parentNode.parentNode.parentNode.querySelector('select[name="model"]').parentNode.parentNode.querySelector('span')
    const customer = item.parentNode.parentNode.parentNode.querySelector('select[name="customer"]').parentNode.parentNode.querySelector('span')
    const state = item.parentNode.parentNode.parentNode.querySelector('input[name="state"]').parentNode.parentNode.querySelector('span')
    const label = delForm.querySelector('label')
    const input = delForm.querySelector('input[name="id"]')
    input.value = id.value
    console.log(model)
    label.innerHTML = 'Вы действительно хотите удалить автомобиль ' + model.innerHTML + ' у клиента ' + customer.innerHTML + ' с госномером ' + state.innerHTML + '?'
}

function editHandler(item) {
    item.parentNode.querySelector('.input-group').classList.toggle('visually-hidden')
}

function print(){
    const print = document.getElementById('print').cloneNode(true)
    print.querySelector('#create').remove()
    print.querySelectorAll('.car-edit').forEach(function (item){
        item.remove()
    })
    print.querySelectorAll('.input-group').forEach(function (item){
        item.remove()
    })
    print.querySelectorAll('.btns').forEach(function (item){
        item.remove()
    })
    CallPrint(print.innerHTML)
}

function init() {
    edits.forEach(function(item) {
        item.addEventListener('click', () => { editHandler(item) })
    })
    delBtn.forEach(function(item) {
        item.addEventListener('click', () => { deleteHandler(item) })
    })
}

init()

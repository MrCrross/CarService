const edits = document.querySelectorAll('.customer-edit')
const delBtn = document.querySelectorAll('.customer-delete')
const delForm = document.querySelector('#customer-delete')

function deleteHandler(item) {
    const id = item.parentNode.parentNode.querySelector('input[name="id"]')
    const first_name = item.parentNode.parentNode.querySelector('input[name="first_name"]')
    const last_name = item.parentNode.parentNode.querySelector('input[name="last_name"]')
    const father_name = item.parentNode.parentNode.querySelector('input[name="father_name"]')
    const label = delForm.querySelector('label')
    const input = delForm.querySelector('input[name="id"]')
    input.value = id.value
    label.innerHTML = 'Вы действительно хотите удалить клиента ' + first_name.value + ' ' + last_name.value + ' ' + father_name.value + '?'
}

function editHandler(item) {
    item.parentNode.querySelector('.input-group').classList.toggle('visually-hidden')
}

function print(){
    const print = document.getElementById('print').cloneNode(true)
    print.querySelector('#create').remove()
    print.querySelectorAll('.customer-edit').forEach(function (item){
        item.remove()
    })
    print.querySelectorAll('.cars').forEach(function (item){
        item.parentNode.parentNode.append(item.options[item.selectedIndex].text)
        item.remove()
    })
    print.querySelectorAll('input').forEach(function (item){
        item.remove()
    })
    print.querySelectorAll('select').forEach(function (item){
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

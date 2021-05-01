const edits = document.querySelectorAll('.car-edit')
const delBtn = document.querySelectorAll('.car-delete')
const delForm = document.querySelector('#car-delete')

function deleteHandler(item) {
    const id = item.parentNode.parentNode.parentNode.querySelector('input[name="id"]')
    const model = item.parentNode.parentNode.parentNode.querySelector('select[name="model"]').parentNode.parentNode.querySelector('span')
    const customer = item.parentNode.parentNode.parentNode.querySelector('select[name="customer"]').parentNode.parentNode.querySelector('span')
    const state = item.parentNode.parentNode.parentNode.querySelector('input[name="state"]').parentNode.parentNode.querySelector('span')
    const label = delForm.querySelector('label')
    const input = delForm.querySelector('#delete-id')
    input.value = id.value
    console.log(model)
    label.innerHTML = 'Вы действительно хотите удалить автомобиль ' + model.innerHTML + ' у клиента ' + customer.innerHTML + ' с госномером ' + state.innerHTML + '?'
}

function editHandler(item) {
    item.parentNode.querySelector('.input-group').classList.toggle('visually-hidden')
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
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

function firmEditHandler(item){
    const form = document.getElementById('form-firm-edit')
    const idInput = form.querySelector('input[name="id"]')
    const nameInput = form.querySelector('input[name="name"]')
    const firm = item.parentNode.querySelector('span[data-id]')
    const id = firm.dataset.id
    const name =firm.innerHTML
    idInput.value = id
    nameInput.value = name
}

function modelEditHandler(item){
    const form = document.getElementById('form-model-edit')
    const idInput = form.querySelector('input[name="id"]')
    const idFirmInput = form.querySelector('input[name="firm_id"]')
    const nameFirmInput = form.querySelector('input[name="firm_name"]')
    const nameInput = form.querySelector('input[name="name"]')
    const yearInput = form.querySelector('input[name="year"]')
    const firm = item.parentNode.parentNode.querySelector('span[data-id]')
    const model = item.parentNode.querySelector('span[data-id]')
    idInput.value = model.dataset.id
    idFirmInput.value = firm.dataset.id
    nameInput.value = model.innerHTML
    nameFirmInput.value = firm.innerHTML
    yearInput.value=model.dataset.year
}

function firmModalEditHandler(e){
    const firm = e.target.parentNode.querySelector('input[name="firm_name"]')
    firm.readOnly= firm.readOnly ? false : true
}

function init() {
    const editFirm = document.querySelectorAll('.firm-edit')
    const editModel = document.querySelectorAll('.model-edit')
    const editFirmModal = document.querySelector('.firmModal-edit')
    editFirmModal.addEventListener('click',firmModalEditHandler)
    editFirm.forEach(function (item){
        item.addEventListener('click',()=> firmEditHandler(item))
    })
    editModel.forEach(function (item){
        item.addEventListener('click',()=> modelEditHandler(item))
    })
    edits.forEach(function(item) {
        item.addEventListener('click', () => { editHandler(item) })
    })
    delBtn.forEach(function(item) {
        item.addEventListener('click', () => { deleteHandler(item) })
    })
}

init()

//Визуалиция инпутов для изменений
function editHandler(item){
    item.parentNode.querySelector('.input-group').classList.toggle('visually-hidden')
}
//Заполнение формы для удаление материала
function deleteHandler(item){
    const id = item.parentNode.parentNode.querySelector('input[name="id"]')
    const name = item.parentNode.parentNode.querySelector('input[name="name"]')
    const label = delForm.querySelector('label')
    const input = delForm.querySelector('input[name="id"]')
    input.value = id.value
    label.innerHTML = 'Вы действительно хотите удалить материал ' + name.value + '?'
}
//Отправка на печать страницы
function print(){
    const print = document.getElementById('print').cloneNode(true)
    print.querySelector('#create').remove()
    print.querySelectorAll('.material-edit').forEach(function (item){
        item.remove()
    })
    print.querySelectorAll('input').forEach(function (item){
        item.remove()
    })
    print.querySelectorAll('.btns').forEach(function (item){
        item.remove()
    })
    CallPrint(print.innerHTML)
}
//Initialize
function init(){
    const edit = document.querySelectorAll('.material-edit')
    const del = document.querySelectorAll('.material-delete')
    edit.forEach(function (item){
        item.addEventListener('click',()=>{editHandler(item)})
    })
    del.forEach(function (item){
        item.addEventListener('click',()=>{deleteHandler(item)})
    })
}

init()

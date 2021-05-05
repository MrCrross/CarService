//Визуалиция инпутов для изменений
function editHandler(item){
    item.parentNode.querySelector('.input-group').classList.toggle('visually-hidden')
}
//Заполнение формы для удаление материала
function deleteHandler(item){
    const delForm =document.getElementById('deleteMaterial')
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
//Замена цвета фона строки, когда мало материала
function trBackground(){
    const tr = document.querySelectorAll('.tr-material')
    tr.forEach(function(item){
        const count = Number(item.querySelector('.countMat').innerHTML)
        if(count<=10){item.classList.add('bg-blood')}
        if(count>10 && count<30){item.classList.add('bg-warning')}
    })
}
//Поиск
function searchHandler(){
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    const search = document.getElementById('btnSearch').parentNode.querySelector('input').value
    fetch("/materials/search", {
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json",
            "X-Requested-With": "XMLHttpRequest",
            "X-CSRF-Token": token
        },
        method: "post",
        credentials: "same-origin",
        body: JSON.stringify({
            search: search
        })
    })
        .then(res=>res.json())
        .then((res)=>{
            const table = document.getElementById('t-material')
            const tr = document.querySelectorAll('.tr-material')
            tr.forEach(function(item){
                item.remove()
            })
            if(res.length!==0){
                res.forEach(function (item,key){
                    reloadTable(table,item,key)
                })
                trBackground()
            }else{
                const trAnswer = document.createElement('tr')
                const tdAnswer = document.createElement('td')
                trAnswer.classList.add('alert')
                trAnswer.classList.add('alert-warning')
                tdAnswer.setAttribute('colspan','4')
                tdAnswer.innerHTML='По Вашему запросу "'+search+'" ничего не найдено. :('
                trAnswer.append(tdAnswer)
                table.querySelector('tbody').append(trAnswer)
            }
        })
}
//отправка созданных форм
function submitHandler(item){
    let tr=''
    if(item.classList.contains('input-group-text')){
        tr = item.parentNode.parentNode.parentNode
    }else {
        tr = item.parentNode.parentNode
    }
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    const id= tr.querySelector('input[name="id"]').value
    const name= tr.querySelector('input[name="name"]').value
    const price= tr.querySelector('input[name="price"]').value
    const count= tr.querySelector('input[name="count"]').value
    fetch('/materials',{
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json",
            "X-Requested-With": "XMLHttpRequest",
            "X-CSRF-Token": token
        },
        method: "PATCH",
        credentials: "same-origin",
        body: JSON.stringify({
            id: id,
            name:name,
            price:price,
            count:count
        })
    })
        .then(()=>{
            location.reload()
        })
}
//Перерисовка таблицы
function reloadTable(table,item,key){
    const trClone = document.getElementById('clone').cloneNode(true)
    trClone.classList.remove('visually-hidden')
    trClone.classList.add('tr-material')
    trClone.id = ''
    trClone.querySelectorAll('input[type="submit"]').forEach(function (item){
        item.classList.add('submit')
    })
    const id = trClone.querySelector('input[name="id"]')
    const row = id.parentNode.querySelector('span')
    const nameM = trClone.querySelector('.nameMaterial')
    const nameInput =trClone.querySelector('input[name="name"]')
    const priceM = trClone.querySelector('.priceMaterial')
    const priceInput =trClone.querySelector('input[name="price"]')
    const countM = trClone.querySelector('.countMat')
    const countInput =trClone.querySelector('input[name="count"]')
    row.innerHTML = ++key
    id.value = item.id
    nameM.innerHTML = item.name
    nameInput.value=item.name
    priceM.innerHTML = item.price
    priceInput.value=item.price
    countM.innerHTML = item.count
    countInput.value=item.count
    table.querySelector('tbody').appendChild(trClone)
}
//Initialize
function init(){
    const table = document.getElementById('t-material')
    const search = document.getElementById('btnSearch')
    search.addEventListener('click',searchHandler)
    table.addEventListener('click',(e)=>{
        if(e.target && e.target.matches('.material-edit')){
            editHandler(e.target)
        }
        if(e.target && e.target.matches('.material-delete')){
            deleteHandler(e.target)
        }
        if(e.target && e.target.matches('.submit')){
            submitHandler(e.target)
        }
    })
    trBackground()
}

init()

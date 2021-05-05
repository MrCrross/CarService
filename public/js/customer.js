function deleteHandler(item) {
    const delForm = document.querySelector('#customer-delete')
    const id = item.parentNode.parentNode.parentNode.querySelector('input[name="id"]')
    const first_name = item.parentNode.parentNode.parentNode.querySelector('input[name="first_name"]')
    const last_name = item.parentNode.parentNode.parentNode.querySelector('input[name="last_name"]')
    const father_name = item.parentNode.parentNode.parentNode.querySelector('input[name="father_name"]')
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

function searchHandler(){
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    const search = document.querySelector('input[type="search"]').value
    fetch("/customers/search", {
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
            const table = document.getElementById('t-customer')
            const tr = document.querySelectorAll('.tr-customer')
            tr.forEach(function(item){
                item.remove()
            })
            if(res.length!==0){
                res.customers.forEach(function (item,key){
                    reloadTable(table,item,res.models,key)
                })
            }else{
                const trAnswer = document.createElement('tr')
                const tdAnswer = document.createElement('td')
                trAnswer.classList.add('alert')
                trAnswer.classList.add('alert-warning')
                tdAnswer.setAttribute('colspan','5')
                tdAnswer.innerHTML='По Вашему запросу "'+search+'" ничего не найдено. :('
                trAnswer.append(tdAnswer)
                table.querySelector('tbody').append(trAnswer)
            }
        })
}

function submitHandler(item){
    let tr=''
    if(item.classList.contains('model') && item.classList.contains('input-group-text')){
        tr = item.parentNode.parentNode.parentNode.parentNode
    }
    else {
        tr = item.parentNode.parentNode.parentNode
    }
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    const id= tr.querySelector('input[name="id"]').value
    const last_name= tr.querySelector('input[name="last_name"]').value
    const first_name= tr.querySelector('input[name="first_name"]').value
    const father_name= tr.querySelector('input[name="father_name"]').value
    const phone= tr.querySelector('input[name="phone"]').value
    const cars= tr.querySelectorAll('input[name="car[]"]')
    const models= tr.querySelectorAll('select[name="model[]"]')
    const states= tr.querySelectorAll('input[name="state[]"]')
    let car=[],model=[],state=[]
    cars.forEach(function (item){
        car.push(item.value)
    })
    models.forEach(function (item){
        model.push(item.value)
    })
    states.forEach(function (item){
        state.push(item.value)
    })
    fetch('/customers',{
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
            last_name:last_name,
            first_name:first_name,
            father_name:father_name,
            phone:phone,
            car:car,
            model:model,
            state:state
        })
    })
        .then(()=>{
            location.reload()
        })
}

//Перерисовка таблицы
function reloadTable(table,item,models,key){
    const trClone = document.getElementById('clone').cloneNode(true)
    trClone.classList.remove('visually-hidden')
    trClone.classList.add('tr-customer')
    trClone.id = ''
    trClone.querySelectorAll('input[type="submit"]').forEach(function (item){
        item.classList.add('submit')
    })
    const tdCar= trClone.querySelector('#customerCars').cloneNode(true)
    trClone.querySelector('#customerCars').remove()
    tdCar.id = ''
    const id = trClone.querySelector('input[name="id"]')
    const row = id.parentNode.querySelector('span')
    const lastName = trClone.querySelector('.lastName')
    const lastInput = trClone.querySelector('input[name="last_name"]')
    const firstName = trClone.querySelector('.firstName')
    const firstInput = trClone.querySelector('input[name="first_name"]')
    const fatherName = trClone.querySelector('.fatherName')
    const fatherInput = trClone.querySelector('input[name="father_name"]')
    const phone = trClone.querySelector('.phone')
    const phoneInput = trClone.querySelector('input[name="phone"]')
    row.innerHTML = ++key
    id.value = item.id
    lastName.innerHTML = item.last_name
    lastInput.value=item.last_name
    firstName.innerHTML = item.first_name
    firstInput.value=item.first_name
    fatherName.innerHTML = item.father_name
    fatherInput.value=item.father_name
    phone.innerHTML = item.phone
    phoneInput.value=item.phone
    item.cars.forEach(function (car){
        const td = tdCar.cloneNode(true)
        const cars = td.querySelector('.car')
        const carInput = td.querySelector('input[name="car[]"]')
        const model = td.querySelector('select[name="model[]"]')
        const state = td.querySelector('input[name="state[]"]')
        cars.innerHTML= car.model.firm.name+" "+car.model.name+" "+car.model.year_release+" "+car.state_number
        carInput.value = car.id
        state.value = car.state_number
        models.forEach(function (it){
            const option = document.createElement('option')
            option.value = it.id
            option.innerHTML=it.firm.name+" "+it.name+" "+it.year_release
            model.append(option)
        })
        trClone.querySelector('.w-25').append(td)
    })
    table.querySelector('tbody').appendChild(trClone)
}

function init() {
    const table = document.getElementById('t-customer')
    const search = document.getElementById('btnSearch')
    search.addEventListener('click',searchHandler)
    table.addEventListener('click',function (e){
        if(e.target && e.target.matches('.customer-edit')){
            editHandler(e.target)
        }
        if(e.target && e.target.matches('.customer-delete')){
            deleteHandler(e.target)
        }
        if(e.target && e.target.matches('.submit')){
            submitHandler(e.target)
        }
    })
}

init()

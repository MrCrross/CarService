function visModelHandler(item){
    const newModel =item.parentNode.parentNode.querySelector('#newModel')
    item.parentNode.parentNode.querySelector('select[name="model_id"]').parentNode.classList.toggle('visually-hidden')
    newModel.classList.toggle('visually-hidden')
    if(!newModel.querySelector('input[name="model_name"]').getAttribute('required')){
        newModel.querySelector('input[name="model_name"]').setAttribute('required','true')
        newModel.querySelector('input[name="model_year"]').setAttribute('required','true')
    }else{
        newModel.querySelector('input[name="model_name"]').removeAttribute('required')
        newModel.querySelector('input[name="model_year"]').removeAttribute('required')
    }
}

function visFirmHandler(item){
    const newFirm =item.parentNode.parentNode.querySelector('#newFirm')
    item.parentNode.parentNode.querySelector('select[name="firm_id"]').parentNode.classList.toggle('visually-hidden')
    newFirm.classList.toggle('visually-hidden')
    if(!newFirm.querySelector('input[name="firm_name"]').getAttribute('required')){
        newFirm.querySelector('input[name="firm_name"]').setAttribute('required','true')
    }else{
        newFirm.querySelector('input[name="firm_name"]').removeAttribute('required')
    }
}

function submitHandler(e){
    e.preventDefault()
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    const result = document.getElementById('result')
    const form = document.querySelector('#formCreateCustomer')
    const formResult = form.parentNode.querySelector('.result')
    const first_name = form.querySelector('input[name="first_name"]').value
    const last_name = form.querySelector('input[name="last_name"]').value
    const father_name = form.querySelector('input[name="father_name"]').value
    const phone = form.querySelector('input[name="phone"]').value
    const model_id = form.querySelector('select[name="model_id"]').value
    const model_name = form.querySelector('input[name="model_name"]').value
    const model_year = form.querySelector('input[name="model_year"]').value
    const firm_id = form.querySelector('select[name="firm_id"]').value
    const firm_name = form.querySelector('input[name="firm_name"]').value
    const state = form.querySelector('input[name="state"]').value
    if(model_id!=='0'){
        submitWithModelId(token,first_name,last_name,father_name,phone,model_id,state,result)
        return
    }
    if(model_id==='0' && firm_id!=='0'){
        submitWithFirmId(token,first_name,last_name,father_name,phone,firm_id,model_name,model_year,state,result)
        return
    }
    if(model_id==='0' && firm_id==='0'){
        submitWithFirmName(token,first_name,last_name,father_name,phone,firm_name,model_name,model_year,state,result)
        return
    }
    formResult.classList.add('alert-danger')
    formResult.classList.remove('visually-hidden')
    formResult.innerHTML='Недостаточно данных'
}
//Отправка данных если такая модель автомобиля есть в базе
function submitWithModelId(token,first_name,last_name,father_name,phone,model_id,state,result){
    fetch('/customer/create',{
        headers:{
            "Content-Type":"application/json",
            "Accept":'application/json',
            "X-Requested-With": "XMLHttpRequest",
            "X-CSRF-Token": token
        },method: "post",
        credentials: "same-origin",
        body: JSON.stringify({
            first_name:first_name,
            last_name:last_name,
            father_name:father_name,
            phone:phone,
            model_id:model_id,
            state:state
        })
    })
        .then(res=>res.json())
        .then((res)=>{
            closeModal("createCustomer")
            result.classList.add('alert-success')
            result.classList.remove('visually-hidden')
            result.innerHTML = res
            location.reload()
        })
        .catch((error)=>{
            closeModal("createCustomer")
            result.classList.add('alert-danger')
            result.classList.remove('visually-hidden')
            result.innerHTML = error
        })
}
//Отправка данных если такой модели автомобиля нет, но есть фирма
function submitWithFirmId(token,first_name,last_name,father_name,phone,firm_id,model_name,model_year,state,result){
    fetch('/customer/create',{
        headers:{
            "Content-Type":"application/json",
            "Accept":'application/json',
            "X-Requested-With": "XMLHttpRequest",
            "X-CSRF-Token": token
        },method: "post",
        credentials: "same-origin",
        body: JSON.stringify({
            first_name:first_name,
            last_name:last_name,
            father_name:father_name,
            phone:phone,
            firm_id:firm_id,
            model_name: model_name,
            model_year: model_year,
            state:state
        })
    })
        .then(res=>res.json())
        .then((res)=>{
            closeModal("createCustomer")
            result.classList.add('alert-success')
            result.classList.remove('visually-hidden')
            result.innerHTML = res
            location.reload()
        })
        .catch((error)=>{
            closeModal("createCustomer")
            result.classList.add('alert-danger')
            result.classList.remove('visually-hidden')
            result.innerHTML = error
        })
}
//Отправка данных если такой модели и фирмы нет
function submitWithFirmName(token,first_name,last_name,father_name,phone,firm_name,model_name,model_year,state,result){
    fetch('/customer/create',{
        headers:{
            "Content-Type":"application/json",
            "Accept":'application/json',
            "X-Requested-With": "XMLHttpRequest",
            "X-CSRF-Token": token
        },method: "post",
        credentials: "same-origin",
        body: JSON.stringify({
            first_name:first_name,
            last_name:last_name,
            father_name:father_name,
            phone:phone,
            firm_name:firm_name,
            model_name: model_name,
            model_year: model_year,
            state:state
        })
    })
        .then(res=>res.json())
        .then((res)=>{
            closeModal("createCustomer")
            result.classList.add('alert-success')
            result.classList.remove('visually-hidden')
            result.innerHTML = res
            location.reload()
        })
        .catch((error)=>{
            closeModal("createCustomer")
            result.classList.add('alert-danger')
            result.classList.remove('visually-hidden')
            result.innerHTML = error
        })
}

function closeModal(modalId) {
    // get modal
    const modal = document.getElementById(modalId);
    // change state like in hidden modal
    modal.classList.remove('show');
    modal.setAttribute('aria-hidden', 'true');
    modal.setAttribute('style', 'display: none');
    // get modal backdrop
    const modalBackdrops = document.getElementsByClassName('modal-backdrop');
    // remove opened modal backdrop
    document.body.removeChild(modalBackdrops[0]);
}

function init(){
//Кнопка отправляющая данные на сервер
    const submit = document.getElementById('formCreateCustomer')
//Кнопка скрывающая select Моделей авто и
// открывающая select Фирм и input'ы Модели
    const visModel = document.querySelectorAll('.visModel')
//Кнопка скрывающая select Фирм авто и
// открывающая input Фирм
    const visFirm = document.querySelectorAll('.visFirm')
    visModel.forEach(function (item){
        item.addEventListener('click',()=>visModelHandler(item))
    })
    visFirm.forEach(function (item){
        item.addEventListener('click',()=>visFirmHandler(item))
    })
    submit.addEventListener('submit',submitHandler)
}

init()

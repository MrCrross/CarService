function searchElement(actual,needed){
    const elem = actual.parentNode.querySelector(needed)
    if(elem){
        return elem
    }else {
        return searchElement(actual.parentNode,needed)
    }
}
function forForm(e){
    const elem = document.querySelector('#'+e.target.getAttribute('list')+' option[value="'+e.target.value+'"]')
    searchElement(e.target,'.formData').value = elem? elem.dataset.value: ''
}
// Получение заказов на основе дат
function getView(e){
    e.preventDefault()
    const formData = new FormData(document.querySelector('#getOrder'))
    formData.forEach(function (item,val){
        console.log(item+ " " +val)
    })
    fetch("/order/view", {
        method: "POST",
        credentials: "same-origin",
        body: formData
    })
        .then(res=>res.json())
        .then((res)=>{
            console.log(res)
            const content =document.getElementById('content')
            const rows = content.querySelectorAll('.row')
            if(rows){
                rows.forEach(function (item){
                    item.remove()
                })
            }
            res.forEach(function (item){
                reloadContent(item)
            })
        })
}

//Обновление данных страницы
function reloadContent(item){
    const content = document.getElementById('content')
    const template = document.getElementById('clone').cloneNode(true)
    const numOrder = template.querySelector('.numOrder')
    const reg = template.querySelector('.dateReg')
    const comp = template.querySelector('.dateComp')
    const customer = template.querySelector('.customer')
    const state = template.querySelector('.state')
    const car = template.querySelector('.car')
    const totalW = template.querySelector('.total-work')
    const totalM = template.querySelector('.total-material')
    const total= template.querySelector('.total')
    const trWork= template.querySelector('.trWork')
    const trMat= template.querySelector('.trMat')
    let tW =0
    let tM =0
    template.id=false
    template.classList.remove('visually-hidden')
    template.querySelector('.trWork').remove()
    template.querySelector('.trMat').remove()
    numOrder.innerHTML= item.id
    reg.innerHTML=item.registration
    comp.innerHTML=item.completed
    customer.innerHTML = item.car.customer.last_name+" "+item.car.customer.first_name+" "+item.car.customer.father_name
    car.innerHTML=item.car.model.firm.name+' '+item.car.model.name+" "+item.car.model.year_release
    state.innerHTML=item.car.state_number
    item.compositions.forEach(function (composition,key){
        const tr = trWork.cloneNode(true)
        const dataWorker = composition.worker
        const dataWork = composition.work
        const num = tr.querySelector('.numWork')
        const worker = tr.querySelector('.worker')
        const nameWork = tr.querySelector('.work')
        const price = tr.querySelector('.priceWork')
        num.innerHTML=++key
        worker.innerHTML = dataWorker.post.name+' '+dataWorker.last_name+" "+dataWorker.first_name+" "+dataWorker.father_name
        nameWork.innerHTML = dataWork.name
        price.innerHTML = dataWork.price
        tW+=Number(price.innerHTML)
        template.querySelector('.bodyWork').append(tr)
    })
    totalW.innerHTML=tW
    item.materials.forEach(function (material,key){
        const tr = trMat.cloneNode(true)
        const num = tr.querySelector('.numMat')
        const name = tr.querySelector('.material')
        const count = tr.querySelector('.countMat')
        const price = tr.querySelector('.priceMat')
        const total = tr.querySelector('.amountMat')
        num.innerHTML= ++key
        name.innerHTML = material.material.name
        count.innerHTML = material.count
        price.innerHTML = material.material.price
        total.innerHTML = Number(count.innerHTML)*Number(price.innerHTML)
        tM+=Number(total.innerHTML)
        template.querySelector('.bodyMat').append(tr)
    })
    totalM.innerHTML=tM
    total.innerHTML=tM+tW
    content.append(template)
}

function init(){
    const orderForm = document.getElementById('getOrder')
    const work = document.querySelector('input[list="works"]')
    const worker = document.querySelector('input[list="workers"]')
    const customer = document.querySelector('input[list="customers"]')
    orderForm.addEventListener('submit',getView)
    work.addEventListener('change',forForm)
    worker.addEventListener('change',forForm)
    customer.addEventListener('change',forForm)
}

init()

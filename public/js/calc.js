// Получение заказов на основе дат
function getContent(e){
    e.preventDefault()
    const startDate = document.getElementById('startDate').value
    const endDate = document.getElementById('endDate').value
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    fetch("/order/calc", {
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json",
            "X-Requested-With": "XMLHttpRequest",
            "X-CSRF-Token": token
        },
        method: "POST",
        credentials: "same-origin",
        body: JSON.stringify({
            start: startDate,
            end: endDate
        })
    })
        .then(res=>res.json())
        .then((res)=>{
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
            reloadTotal()
        })
}

//Обработчик поиска
function searchHandler(){
    const search = document.querySelector('input[type="search"]').value
    const startDate = document.getElementById('startDate').value
    const endDate = document.getElementById('endDate').value
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    if(startDate!=='' && endDate!==''){
        fetch("/order/search", {
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-Requested-With": "XMLHttpRequest",
                "X-CSRF-Token": token
            },
            method: "POST",
            credentials: "same-origin",
            body: JSON.stringify({
                search: search,
                start: startDate,
                end: endDate
            })
        })
            .then(res=>res.json())
            .then((res)=>{
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
                reloadTotal()
            })
    }
}

//Обновление данных страницы
function reloadContent(item){
    const content = document.getElementById('content')
    const template = document.getElementById('clone').cloneNode(true)
    const income = template.querySelector('.income')
    const costs = template.querySelector('.costs')
    const num = income.querySelector('.numOrder')
    const tdWork = template.querySelector('.works')
    const trMaterial = template.querySelector('.tr-material')
    const totalW = income.querySelector('.total-work')
    const totalM = costs.querySelector('.total-material')
    const nameCustomer = income.querySelector('.nameCustomer')
    let tW =0
    let tM =0
    template.id=false
    template.classList.remove('visually-hidden')
    template.querySelector('.works').remove()
    template.querySelector('.tr-material').remove()
    num.innerHTML= item.id
    nameCustomer.innerHTML = item.car.customer.last_name+" "+item.car.customer.first_name+" "+item.car.customer.father_name+": "+
        item.car.model.firm.name+' '+item.car.model.name+" "+item.car.model.year_release+" "+item.car.state_number
    item.compositions.forEach(function (composition){
        const td = tdWork.cloneNode(true)
        const dataWorker = composition.worker
        const dataWork = composition.work
        const worker = td.querySelector('.nameWorker')
        const nameWork = td.querySelector('.nameWork')
        const price = td.querySelector('.priceWork')
        worker.innerHTML = dataWorker.post.name+' '+dataWorker.last_name+" "+dataWorker.first_name+" "+dataWorker.father_name
        nameWork.innerHTML = dataWork.name+":"
        price.innerHTML = dataWork.price
        tW+=Number(price.innerHTML)
        income.querySelector('.td-work').append(td)
    })
    totalW.innerHTML=tW
    item.materials.forEach(function (material){
        const tr = trMaterial.cloneNode(true)
        const name = tr.querySelector('.nameMat')
        const count = tr.querySelector('.countMat')
        const price = tr.querySelector('.priceMat')
        const total = tr.querySelector('.totalMat')
        name.innerHTML = material.material.name
        count.innerHTML = material.count
        price.innerHTML = material.material.price
        total.innerHTML = Number(count.innerHTML)*Number(price.innerHTML)
        tM+=Number(total.innerHTML)
        costs.querySelector('tbody').append(tr)
    })
    totalM.innerHTML=tM
    content.append(template)
}

//Обновление общей стоимости
function reloadTotal(){
    const totals =document.querySelectorAll('.total-content')
    const content = document.querySelector('#content')
    const totalW = content.querySelectorAll('.total-work')
    const totalM = content.querySelectorAll('.total-material')
    let tW = 0
    let tM = 0
    totalM.forEach(function (item){
        tM+=Number(item.innerHTML)
    })
    totalW.forEach(function (item){
        tW+=Number(item.innerHTML)
    })
    totals.forEach(function (item){
        item.classList.remove('visually-hidden')
        item.querySelector('.total').innerHTML=tM+tW
    })

}
function init(){
    const orderForm = document.getElementById('getOrder')
    const search = document.getElementById('btnSearch')
    orderForm.addEventListener('submit',getContent)
    search.addEventListener('click',searchHandler)
}

init()

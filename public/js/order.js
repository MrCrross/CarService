function searchElement(actual,needed){
    const elem = actual.parentNode.querySelector(needed)
    if(elem){
        return elem
    }else {
        return searchElement(actual.parentNode,needed)
    }
}
function searchParentByTag(actual,needed){
    const elem = actual.parentNode
    if(elem.tagName === needed.toUpperCase()){
        return elem
    }else {
        return searchParentByTag(actual.parentNode,needed)
    }
}
// Обработчик клонирование объектов
function duplicateHandler(item) {
    console.log(document.querySelector('input[name="customer"]').value)
    console.log(document.querySelector('input[name="customer"]').innerHTML)
    const tbody = searchElement(item,'tbody')
    const newObj = tbody.querySelector('.clone').cloneNode(true)
    const work =newObj.querySelector('select[name="work[]"]')
    const worker =newObj.querySelector('select[name="worker[]"]')
    const material = newObj.querySelector('select[name="material[]"]')
    const count = newObj.querySelector('input[name="count"]')
    newObj.className=''
    newObj.querySelector('.num').innerHTML=parseInt(tbody.querySelectorAll('tr')[tbody.querySelectorAll('tr').length-1].querySelector('.num').innerHTML)+1
    if(work){work.required=true}
    if(worker){worker.required=true}
    if(material){material.required=true}
    if(count){count.required=true}
    tbody.append(newObj)
}

// Обработчик удаление клона объекта
function duplicateRemoveHandler(item) {
    const tr = searchParentByTag(item,'tr')
    const tbody = searchParentByTag(item,'tbody')
    tr.remove()
    tbody.querySelectorAll('tr').forEach(function (elem,num){
        elem.querySelector('.num').innerHTML=num
    })
}

//Проявление скрытых select'ов
function visuallyHandler(item) {
    const vhElement = searchParentByTag(item,'tr').querySelector('.v-h')
    if (item.value !== '') {
        vhElement.classList.remove('visually-hidden')
    }else{
        vhElement.classList.add('visually-hidden')
    }
}

//Заполнение select'а car
function clientHandler(item){
    const id = item.value
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    if(id!==''){
        fetch("/orders/cars", {
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-Requested-With": "XMLHttpRequest",
                "X-CSRF-Token": token
            },
            method: "post",
            credentials: "same-origin",
            body: JSON.stringify({
                id: id
            })
        })
            .then(res=>res.json())
            .then((res)=>{
                if(res.length!==0){
                    const data =res[0]
                    const cars = document.querySelector('select[name="car"]')
                    if(data.cars.length!==0){
                        cars.querySelectorAll('option').forEach(function (item){
                            item.remove()
                        })
                        const option = document.createElement('option')
                        option.value=""
                        option.innerHTML = 'Выберите автомобиль заказчика'
                        cars.append(option)
                        data.cars.forEach(function (item){
                            const option = document.createElement('option')
                            option.value = item.id
                            option.dataset.state=item.state_number
                            option.innerHTML = item.model.firm.name + ' ' + item.model.name +" "+ item.model.year_release
                            cars.append(option)
                        })
                    }
                }
            })
    }
}

//Заполнение select'а worker[]
function workHandler(item){
    const id = item.value
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    if(id!==''){
        fetch("/orders/workers", {
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-Requested-With": "XMLHttpRequest",
                "X-CSRF-Token": token
            },
            method: "post",
            credentials: "same-origin",
            body: JSON.stringify({
                id: id
            })
        })
            .then(res=>res.json())
            .then((res)=>{
                if(res.length!==0){
                    const data =res[0]
                    const workers = searchParentByTag(item,'tr').querySelector('select[name="worker[]"]')
                    if(data.posts.length!==0){
                        workers.querySelectorAll('option').forEach(function (item){
                            item.remove()
                        })
                        const option = document.createElement('option')
                        option.value=""
                        option.innerHTML = 'Выберите исполнителя работы'
                        workers.append(option)
                        data.posts.forEach(function (post){
                            post.post.workers.forEach(function (worker){
                                const option = document.createElement('option')
                                option.value = worker.id
                                option.innerHTML = post.post.name + ' ' + worker.last_name+" "+worker.first_name +" "+  worker.father_name
                                workers.append(option)
                            })
                        })
                    }
                }
            })
    }
}
//Заполнение максимального количество материала
function checkMaxHandler(item){
    const id = item.value
    if(id!==''){
        const max = item.options[item.selectedIndex].dataset.count
        const inputCount = searchParentByTag(item,'tr').querySelector('input[name="count[]"]')
        inputCount.setAttribute('max',max)
    }
}

//Добавление данных клиента в модальное окно нового автомобиля
function carCreateHandler(item){
    const customer =searchElement(item,'select[name="customer"]')
    const nameCustomer =customer.options[customer.selectedIndex].text
    const form = document.getElementById('formCreateCar')
    const input = form.querySelector('input[name="nameCustomer"]')
    input.dataset.id = customer.value
    input.value = nameCustomer
}

//Вывод данных о работе и исполнителе в соответствующую таблицу для печати
function calcAmount(){
    let totalAmountWork = 0
    let totalAmountMat = 0
    const totalMat = document.getElementById('total-material')
    const totalWork = document.getElementById('total-work')
    const total = document.getElementById('total')
    const priceWorks= document.querySelectorAll('.priceWork')
    const amountMats = document.querySelectorAll('.amountMat')
    priceWorks.forEach(function (item){
        totalAmountWork+= parseInt(item.innerHTML)?parseInt(item.innerHTML):0
    })
    amountMats.forEach(function (item){
        totalAmountMat+= parseInt(item.innerHTML)?parseInt(item.innerHTML):0
    })
    totalMat.innerHTML=totalAmountMat
    totalWork.innerHTML = totalAmountWork
    total.innerHTML = totalAmountWork + totalAmountMat
}

// Сегодняшняя дата для печати
function submitHandler(e){
    e.preventDefault()
    print()
    const form = document.getElementById('createOrderForm')
    const total = document.getElementById('total')
    const input = document.createElement('input')
    input.type='number'
    input.name ='total'
    input.classList.add('visually-hidden')
    input.value=total.innerHTML
    form.append(input)
    form.submit()
}

function forPrint(e){
    searchElement(e,'.print').innerHTML = e.tagName === 'SELECT' ? e.options[e.selectedIndex].text : e.value
}

function print(){
    const print =document.getElementById('print').cloneNode(true)
    print.querySelectorAll('.print').forEach(function (item){
        item.classList.remove('visually-hidden')
    })
    print.querySelectorAll('.noPrint').forEach(function (item){
        item.remove()
    })
    CallPrint(print.innerHTML)
}
// Сегодняшняя дата для печати
function nowDate(){
    const now = new Date()
    const day = now.getDate()<'10'? "0"+now.getDate(): now.getDate()
    const month = now.getMonth()<'10'? "0"+(now.getMonth()+1): (now.getMonth()+1)
    document.getElementById('dateReg').innerHTML = day+"."+month+"."+now.getFullYear()
    document.getElementById('dateComp').innerHTML = day+"."+month+"."+now.getFullYear()
}
//Инициализация
function init() {
    const tds = document.querySelectorAll('form')
    const form = document.getElementById('createOrderForm')
    tds.forEach(function(item) {
        item.addEventListener('click', (e) => {
            if(e.target && e.target.matches('.duplicate')) {
                duplicateHandler(e.target)
            }
            if(e.target && e.target.matches('.remove')) {
                duplicateRemoveHandler(e.target)
                calcAmount()
            }
            if(e.target && e.target.matches('.car-create')) {
                carCreateHandler(e.target)
            }
        })
        item.addEventListener('change',(e)=>{
            if(e.target && e.target.matches('select[name="customer"]')) {
                clientHandler(e.target)
                visuallyHandler(e.target)
                searchElement(e.target, '.state').innerHTML = ''
                forPrint(e.target)
            }
            if(e.target && e.target.matches('select[name="work[]"]')) {
                workHandler(e.target)
                visuallyHandler(e.target)
                const price = e.target.options[e.target.selectedIndex].dataset.price
                searchElement(e.target,'.priceWork').innerHTML = price ? price: ''
                calcAmount()
                forPrint(e.target)
            }
            if(e.target && e.target.matches('select[name="worker[]"]')) {
                forPrint(e.target)
            }
            if(e.target && e.target.matches('select[name="car"]')) {
                const car = e.target.options[e.target.selectedIndex].dataset.state
                searchElement(e.target, '.state').innerHTML = car ? car : ''
                forPrint(e.target)
            }
            if(e.target && e.target.matches('select[name="material[]"]')) {
                checkMaxHandler(e.target)
                const price = e.target.options[e.target.selectedIndex].dataset.price
                const count = searchElement(e.target,'input[name="count[]"]').value
                searchElement(e.target,'.priceMat').innerHTML = price ? price: ''
                searchElement(e.target,'.amountMat').innerHTML = price ? parseInt(price) * parseInt(count) : ''
                calcAmount()
                forPrint(e.target)
            }
            if(e.target && e.target.matches('input[name="count[]"]')) {
                const material = searchElement(e.target,'select[name="material[]"]')
                const price = material.options[material.selectedIndex].dataset.price
                const count = e.target.value
                searchElement(e.target,'.amountMat').innerHTML = price ? parseInt(price) * parseInt(count) : ''
                calcAmount()
                forPrint(e.target)
            }

        })
    })
    form.addEventListener('submit',submitHandler)
    nowDate()
}

init()

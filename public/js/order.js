// Обработчик клонирование объектов
function duplicateHandler(item) {
    const td = item.classList.contains('icon') ? item.parentNode.parentNode.parentNode: item.parentNode.parentNode
    const objDub = td.querySelector('div')
    const remObj = document.createElement('span')
    const remImg = document.createElement('img')
    remImg.classList.add('icon')
    remImg.classList.add('remove')
    remImg.classList.add('mt-2')
    remImg.classList.add('ms-1')
    remImg.src='/image/minus.svg'
    remImg.alt='Удалить'
    remObj.classList.add('remove')
    remObj.append(remImg)
    const newObj = objDub.cloneNode(true)
    if(newObj.querySelector('.w-75')) newObj.querySelector('.w-75').classList.add('visually-hidden')
    newObj.querySelector('.duplicate').remove()
    newObj.append(remObj)
    td.append(newObj)
}

// Обработчик удаление клона объекта
function duplicateRemoveHandler(item) {
    if(item.classList.contains('icon')){
        item.parentNode.parentNode.remove()
    }else{
        item.parentNode.remove()
    }
}

//Проявление скрытых select'ов
function visuallyHandler(item) {
    if (item.parentNode.parentNode.querySelector('div.visually-hidden') && item.value !== '') {
        item.parentNode.parentNode.querySelector('div.visually-hidden').classList.remove('visually-hidden')
    }else if (!item.parentNode.parentNode.querySelector('div.visually-hidden') && item.value ==='') {
        item.parentNode.parentNode.querySelector('div.w-75').classList.add('visually-hidden')
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
                            option.innerHTML = item.model.firm.name + ' ' + item.model.name +" "+ item.model.year_release +" "+item.state_number
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
                    const workers = item.parentNode.parentNode.querySelector('select[name="worker[]"]')
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
        const inputCount = item.parentNode.parentNode.querySelector('input[name="count[]"]')
        inputCount.setAttribute('max',max)
    }
}

//Добавление данных клиента в модальное окно нового автомобиля
function carCreateHandler(item){
    let customer =''
    if(item.classList.contains('icon')){
        customer = item.parentNode.parentNode.parentNode.querySelector('select[name="customer"]')
    }else{
        customer = item.parentNode.parentNode.querySelector('select[name="customer"]')
    }
    const nameCustomer =customer.options[customer.selectedIndex].text
    const form = document.getElementById('formCreateCar')
    const input = form.querySelector('input[name="nameCustomer"]')
    input.dataset.id = customer.value
    input.value = nameCustomer

}

//Вывод данных о клиенте в соответствующую таблицу для печати
function tableClientHandler(){
    const numClient = document.getElementById('numClient')
    const nameClient = document.getElementById('nameClient')
    const carClient = document.getElementById('carClient')
    const stateClient = document.getElementById('stateCarClient')
    const customer = document.getElementById('customer')
    const selCustomer = customer.querySelector('select[name="customer"]')
    const selCar = customer.querySelector('select[name="car"]')
    if(selCustomer.value!=='' && selCar.value!==''){
        const textCar = selCar.options[selCar.selectedIndex].text
        const nameCar = textCar.slice(0,textCar.lastIndexOf(" "))
        const stateCar = textCar.slice(textCar.lastIndexOf(" "))
        numClient.innerHTML = '1'
        nameClient.innerHTML = selCustomer.options[selCustomer.selectedIndex].text
        carClient.innerHTML = nameCar
        stateClient.innerHTML = stateCar
    }else{
        numClient.innerHTML = ''
        nameClient.innerHTML =''
        carClient.innerHTML = ''
        stateClient.innerHTML = ''
    }
}

//Вывод данных о работе и исполнителе в соответствующую таблицу для печати
function tableWorkHandler(){
    let textSumma =0
    const tWork = document.getElementById('t-work').querySelector('tbody')
    const totalWork = document.getElementById('total-work')
    const work = document.getElementById('work')
    const arrSelects = work.querySelectorAll('.works')
    tWork.querySelectorAll('tr').forEach(function (item){
        item.remove()
    })
    totalWork.innerHTML =' '
    arrSelects.forEach(function (item,key){
        const selWork = item.querySelector('select[name="work[]"]')
        const selWorker = item.querySelector('select[name="worker[]"]')
        if(selWork.value!=='' && selWorker.value!==''){
            const tr_work = document.createElement('tr')
            const numWork = document.createElement('td')
            const nameWork = document.createElement('td')
            const nameWorker = document.createElement('td')
            const priceWork = document.createElement('td')
            numWork.innerHTML = ++key
            nameWork.innerHTML = selWork.options[selWork.selectedIndex].text
            nameWorker.innerHTML = selWorker.options[selWorker.selectedIndex].text
            priceWork.innerHTML = selWork.options[selWork.selectedIndex].dataset.price
            textSumma += Number(priceWork.innerHTML)
            tr_work.append(numWork)
            tr_work.append(nameWork)
            tr_work.append(nameWorker)
            tr_work.append(priceWork)
            tWork.append(tr_work)
        }
    })
    totalWork.innerHTML = textSumma
}

//Вывод данных о работе и исполнителе в соответствующую таблицу для печати
function tableMaterialHandler(){
    let textSumma =0
    const tMat = document.getElementById('t-material').querySelector('tbody')
    const totalMat = document.getElementById('total-material')
    const totalWork = document.getElementById('total-work')
    const total = document.getElementById('total')
    const material = document.getElementById('material')
    const arrSelects = material.querySelectorAll('.materials')
    tMat.querySelectorAll('tr').forEach(function (item){
        item.remove()
    })
    totalMat.innerHTML =''
    total.innerHTML =''
    arrSelects.forEach(function (item,key){
        const selMat = item.querySelector('select[name="material[]"]')
        const inputMat = item.querySelector('input[name="count[]"]')
        if(selMat.value!=='' && inputMat.value!==''){
            const tr_mat = document.createElement('tr')
            const numMat = document.createElement('td')
            const nameMat = document.createElement('td')
            const countMat = document.createElement('td')
            const priceMat = document.createElement('td')
            numMat.innerHTML = ++key
            nameMat.innerHTML = selMat.options[selMat.selectedIndex].text
            countMat.innerHTML = inputMat.value
            priceMat.innerHTML = selMat.options[selMat.selectedIndex].dataset.price
            textSumma += Number(priceMat.innerHTML)*Number(countMat.innerHTML)
            tr_mat.append(numMat)
            tr_mat.append(nameMat)
            tr_mat.append(countMat)
            tr_mat.append(priceMat)
            tMat.append(tr_mat)
        }
    })
    totalMat.innerHTML = textSumma
    total.innerHTML =textSumma+Number(totalWork.innerHTML)
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
function print(){
    const print =document.getElementById('print')
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
    const tds = document.querySelectorAll('td')
    const form = document.getElementById('createOrderForm')
    tds.forEach(function(item) {
        item.addEventListener('click', (e) => {
            if(e.target && e.target.matches('.duplicate')) {
                duplicateHandler(e.target)
            }
            if(e.target && e.target.matches('.remove')) {
                duplicateRemoveHandler(e.target)
                tableWorkHandler()
                tableMaterialHandler()
            }
            if(e.target && e.target.matches('.car-create')) {
                carCreateHandler(e.target)
            }
        })
        item.addEventListener('change',(e)=>{
            if(e.target && e.target.matches('select[name="customer"]')) {
                clientHandler(e.target)
                visuallyHandler(e.target)
                tableClientHandler()
            }
            if(e.target && e.target.matches('select[name="work[]"]')) {
                workHandler(e.target)
                visuallyHandler(e.target)
                tableWorkHandler()
            }
            if(e.target && e.target.matches('select[name="car"]')) {
                tableClientHandler()
            }
            if(e.target && e.target.matches('select[name="worker[]"]')) {
                tableWorkHandler()
            }
            if(e.target && e.target.matches('select[name="material[]"]')) {
                checkMaxHandler(e.target)
                tableMaterialHandler()
            }
            if(e.target && e.target.matches('input[name="count[]"]')) {
                tableMaterialHandler()
            }

        })
    })
    form.addEventListener('submit',submitHandler)
    nowDate()
}

init()

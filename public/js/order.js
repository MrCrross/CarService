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
    if (item.parentNode.parentNode.querySelector('div.visually-hidden') && item.value !== '0') {
        item.parentNode.parentNode.querySelector('div.visually-hidden').classList.remove('visually-hidden')
    }else if (!item.parentNode.parentNode.querySelector('div.visually-hidden') && item.value ==='0') {
        item.parentNode.parentNode.querySelector('div.w-75').classList.add('visually-hidden')
    }
}

//Заполнение select'а car
function clientHandler(item){
    const id = item.value
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    if(id!=='0'){
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
                        data.cars.forEach(function (item){
                            const option = document.createElement('option')
                            option.value = item.id
                            option.innerHTML = item.model.firm.name + ' ' + item.model.name +" "+ item.state_number
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
    if(id!=='0'){
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
                        data.posts.forEach(function (post){
                            post.post.workers.forEach(function (worker){
                                const option = document.createElement('option')
                                option.value = worker.id
                                option.innerHTML = post.post.name + ' ' + worker.first_name +" "+ worker.last_name+" "+ worker.father_name
                                workers.append(option)
                            })
                        })
                    }
                }
            })
    }
}

function init() {
    const tds = document.querySelectorAll('td')
    tds.forEach(function(item) {
        item.addEventListener('click', (e) => {
            if(e.target && e.target.matches('.duplicate')) {
                duplicateHandler(e.target)
            }
            if(e.target && e.target.matches('.remove')) {
                duplicateRemoveHandler(e.target)
            }
        })
        item.addEventListener('change',(e)=>{
            if(e.target && e.target.matches('select[name="customer"]')) {
                clientHandler(e.target)
                visuallyHandler(e.target)
            }
            if(e.target && e.target.matches('select[name="work[]"]')) {
                workHandler(e.target)
                visuallyHandler(e.target)
            }
        })
    })
}

init()

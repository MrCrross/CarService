//Delete Worker Handler
function deleteHandler(item) {
    const delForm = document.querySelector('#worker-delete')
    const id = item.parentNode.parentNode.parentNode.querySelector('input[name="id"]')
    const first_name = item.parentNode.parentNode.parentNode.querySelector('input[name="first_name"]')
    const last_name = item.parentNode.parentNode.parentNode.querySelector('input[name="last_name"]')
    const father_name = item.parentNode.parentNode.parentNode.querySelector('input[name="father_name"]')
    const label = delForm.querySelector('label')
    const input = delForm.querySelector('input[name="id"]')
    input.value = id.value
    label.innerHTML = 'Вы действительно хотите уволить сотрудника ' + first_name.value + ' ' + last_name.value + ' ' + father_name.value + '?'
}
//Delete Post Handler
function deletePostHandler(item) {
    const delPostForm = document.querySelector('#post-delete')
    const id = item.parentNode.parentNode.parentNode.querySelector('input[name="id"]')
    const name = item.parentNode.parentNode.parentNode.querySelector('.name')
    const label = delPostForm.querySelector('label')
    const input = delPostForm.querySelector('#delete-id')
    console.log(name)
    input.value = id.value
    label.innerHTML = 'Вы действительно хотите удалить должность ' + name.innerHTML + '?'
}
//Delete Work Handler
function deleteWorkHandler(item) {
    const delWorkForm = document.querySelector('#work-delete')
    const labelWork = item.parentNode.querySelector('label[for="works"]')
    const label = delWorkForm.querySelector('label')
    const input = delWorkForm.querySelector('input[name="id"]')
    input.value = labelWork.dataset.id
    label.innerHTML = 'Вы действительно хотите удалить работу ' + labelWork.innerHTML + '?'
}
//Visualization workers components for edits
function editHandler(item) {
    item.parentNode.querySelector('.input-group').classList.toggle('visually-hidden')
}
//Edits data name Work
function editWorkHandler(item) {
    const editForm = document.querySelector('#work-edit')
    const id = item.parentNode.querySelector('label[data-id]')
    const name = editForm.querySelector('input[name="name"]')
    const price = editForm.querySelector('input[name="price"]')
    const input = editForm.querySelector('input[name="id"]')
    input.value = id.dataset.id
    name.value = id.innerHTML
    price.value = id.dataset.price
}
//Data in download modal
function downloadHandler(item) {
    const url = item.dataset.contract
    const form = document.querySelector('#downloadForm')
    const contract = form.querySelector('input[name="contract"]')
    contract.value=url
    form.submit()
}
//Печать
function print() {
    const print = document.getElementById('print').cloneNode(true)
    if(print.querySelector('#create')) print.querySelector('#create').remove()
    print.querySelectorAll('input[name="works[]"]').forEach(function (item){
       if(item.getAttribute('checked')!=='checked') item.parentNode.remove()
    })
    print.querySelectorAll('.worker-edit').forEach(function (item){
        item.remove()
    })
    print.querySelectorAll('.download').forEach(function (item){
        item.remove()
    })
    print.querySelectorAll('.work-edit').forEach(function (item){
        item.remove()
    })
    print.querySelectorAll('.work-delete').forEach(function (item){
        item.remove()
    })
    print.querySelectorAll('.btns').forEach(function (item){
        item.remove()
    })
    print.querySelectorAll('input').forEach(function (item){
        item.remove()
    })
    print.querySelectorAll('select').forEach(function (item){
        item.remove()
    })
    CallPrint(print.innerHTML)
}

function searchHandler(){
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    const search = document.querySelector('input[type="search"]').value
    fetch("/workers/search", {
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
            const table = document.getElementById('t-worker')
            const tr = document.querySelectorAll('.tr-worker')
            tr.forEach(function(item){
                item.remove()
            })
            if(res.length!==0){
                res.workers.forEach(function (item,key){
                    reloadTable(table,item,res.posts,key)
                })
            }else{
                const trAnswer = document.createElement('tr')
                const tdAnswer = document.createElement('td')
                trAnswer.classList.add('alert')
                trAnswer.classList.add('alert-warning')
                tdAnswer.setAttribute('colspan','9')
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
    const post= tr.querySelector('select[name="post"]').value
    const contract= tr.querySelector('input[name="contract"]').files[0]
    let obj ={}
    if(post!=='' && contract!==''){
        obj = {
            id: id,
            last_name:last_name,
            first_name:first_name,
            father_name:father_name,
            phone:phone,
            post:post,
            contract:contract
        }
    }else{
        obj = {
            id: id,
            last_name:last_name,
            first_name:first_name,
            father_name:father_name,
            phone:phone,
        }
    }
    fetch('/workers',{
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json",
            "X-Requested-With": "XMLHttpRequest",
            "X-CSRF-Token": token
        },
        method: "PATCH",
        credentials: "same-origin",
        body: JSON.stringify(obj)
    })
        .then(()=>{
            location.reload()
        })
}

//Перерисовка таблицы
function reloadTable(table,item,posts,key){
    const trClone = document.getElementById('clone').cloneNode(true)
    trClone.classList.remove('visually-hidden')
    trClone.classList.add('tr-worker')
    trClone.id = ''
    trClone.querySelectorAll('input[type="submit"]').forEach(function (item){
        item.classList.add('submit')
    })
    const tdWork= trClone.querySelector('.works').cloneNode(true)
    const tdContract= trClone.querySelector('.contracts').cloneNode(true)
    const tdOrderShow= trClone.querySelector('.orderShow').cloneNode(true)
    const tdWorkerShow= trClone.querySelector('.workerShow').cloneNode(true)
    trClone.querySelector('.works').remove()
    trClone.querySelector('.contracts').remove()
    trClone.querySelector('.orderShow').remove()
    trClone.querySelector('.workerShow').remove()

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
    const post = trClone.querySelector('.post')
    const postSel = trClone.querySelector('select[name="post"]')
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
    post.innerHTML = item.post.name
    const option = document.createElement('option')
    option.value = ""
    option.innerHTML="Выберите должность"
    postSel.append(option)
    posts.forEach(function (it){
        const option = document.createElement('option')
        option.value = it.id
        option.innerHTML=it.name
        postSel.append(option)
    })
    item.post.works.forEach(function (work){
        const td = tdWork.cloneNode(true)
        const label = td.querySelector('label')
        label.dataset.id =work.work.id
        label.dataset.price=work.work.price
        label.innerHTML=work.work.name
        trClone.querySelector('.td-work').append(td)
    })
    item.contracts.forEach(function (contract){
        const td = tdContract.cloneNode(true)
        const name = td.querySelector('.contract')
        const download = td.querySelector('.download')
        name.innerHTML= contract.post.name+":"+contract.post_change
        download.dataset.contract=contract.contract
        trClone.querySelector('.td-contract').append(td)
    })
    item.orders.forEach(function (order){
        const td = tdOrderShow.cloneNode(true)
        td.href="/orders/"+order.order_id
        td.innerHTML = '№ '+order.order_id
        trClone.querySelector('.td-order').append(td)
    })
    tdWorkerShow.href='/workers/'+item.id
    trClone.querySelector('.td-order').append(tdWorkerShow)
    table.querySelector('tbody').appendChild(trClone)
}

//Initialization
function init() {
    const table = document.getElementById('t-worker')
    const search = document.getElementById('btnSearch')
    search.addEventListener('click',searchHandler)
    table.addEventListener('click',function (e){
        if(e.target && e.target.matches('.worker-edit')){
            editHandler(e.target)
        }
        if(e.target && e.target.matches('.work-edit')){
            editWorkHandler(e.target)
        }
        if(e.target && e.target.matches('.worker-delete')){
            deleteHandler(e.target)
        }
        if(e.target && e.target.matches('.post-delete')){
            deletePostHandler(e.target)
        }
        if(e.target && e.target.matches('.work-delete')){
            deleteWorkHandler(e.target)
        }
        if(e.target && e.target.matches('.submit')){
            submitHandler(e.target)
        }
        if(e.target && e.target.matches('.download')){
            downloadHandler(e.target)
        }
    })
}

init()

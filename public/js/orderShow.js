function print(){
    const print =document.getElementById('print')
    CallPrint(print.innerHTML)
}
function init(){
    const priceWork = document.querySelectorAll('.priceWork')
    const priceMat = document.querySelectorAll('.priceMat')
    const totalMat = document.querySelector('#total-material')
    const totalWork = document.querySelector('#total-work')
    let total_w = 0
    let total_m = 0
    priceWork.forEach(function (item){
        total_w+=Number(item.innerHTML)
    })
    priceMat.forEach(function (item){
        total_m+=Number(item.innerHTML) * Number(item.parentNode.querySelector('.countMat').innerHTML)
    })
    totalWork.innerHTML = total_w
    totalMat.innerHTML = total_m
    // total.innerHTML = String(total_w+total_m)
}
init()

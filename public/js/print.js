// Функция печати
function CallPrint(data) {
    const prtContent = data
    const prtCSS = '<link rel="stylesheet" href="http://'+location.host+'/css/app.css" type="text/css" /> ' +
        '<link rel="stylesheet" href="http://'+location.host+'/css/style.css" type="text/css" />'
    const WinPrint = window.open('','','left=50,top=50,width=800,height=640,toolbar=0,scrollbars=1,status=0')
    WinPrint.document.write('<html><head>')
    WinPrint.document.write(prtCSS)
    WinPrint.document.write('</head><body >')
    WinPrint.document.write(prtContent)
    WinPrint.document.write('</body></html>')
    WinPrint.document.close()
    WinPrint.focus()
    setTimeout(function check(){
        if (WinPrint.document.readyState ==='complete')
        {
            WinPrint.print()
            // WinPrint.close()
        } else{
            setTimeout(check,100)
        }
    },100)

}

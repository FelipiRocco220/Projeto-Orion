let popup = false;
window.addEventListener("scroll", (e) => {
    let scroll = this.scrollY;
    console.log(scroll);
    if(scroll > 100) {
        if(popup == false) {
            console.log('icon');
            popup = true;
        }
    } else {
        popup = false;
    }
});
function goTop(){
    window.scrollTo(0, 0);
}

let last_pop;
function addPopup(text, type = 1){
    if(last_pop){
        last_pop.style.bottom = '50px';
        last_pop = null;
    }

    var test_popup = document.createElement('div');
    test_popup.innerText = text;
    test_popup.classList.add('popup');
    switch(type){
        case 2:
            test_popup.classList.add('warning');
            break;
        case 1:
            test_popup.classList.add('sucess');
            break;
        case 0:
            test_popup.classList.add('danger');
            break;
    }
    test_popup.style.bottom = '5px';
    test_popup.style.opacity = 1;
    test_popup.style.transition = '1s opacity, .3s bottom';
    document.body.appendChild(test_popup);
    setTimeout(() => {
        if(test_popup) test_popup.style.opacity = 0;
        setTimeout(() => {
            if(test_popup) test_popup.remove();
        }, 1100);
    }, 2000);
    last_pop = test_popup;
}
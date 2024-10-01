var tag_element = document.getElementById("tag");

tag_element.oninput = () => {
    var selectionStart = tag_element.selectionStart;
    var selectionEnd = tag_element.selectionEnd

    tag_element.value = tag_element.value.replaceAll(' ', '_');

    var text = '';
    for(var i = 0; i < tag_element.value.length; i++){
        var char = tag_element.value.charAt(i);
        if(char == '_' && char == tag_element.value.charAt(i - 1)){
            if(selectionStart - 1 == i) selectionEnd -= 1;
        } else text += char;
    }
    tag_element.value = text;
    var newSelectionStart = selectionStart + tag_element.value.length;
    tag_element.setSelectionRange(newSelectionStart, selectionEnd);
};

var birthday_element = document.getElementById("birthday");
var age = document.getElementById("age");
birthday_element.oninput = () => {
    var date = new Date();
    var birth = birthday_element.value.split('-');
    for(var i = 0; i < birth.length; i++) birth[i] = parseInt(birth[i]);

    var years = date.getFullYear() - birth[0];
    if(birth[1] > date.getMonth() + 1) years -= 1;
    else if(birth[1] == date.getMonth() + 1 && birth[2] > date.getDate()) years -= 1;
    
    age.innerText = '';
    if(Number.isInteger(years) && years > 0 && years < 120){
        age.innerText = '(';
        if(years == 1) age.innerText += years + ' Ano';
        else age.innerText += years + ' Anos';
        age.innerText += ')';
    }
};
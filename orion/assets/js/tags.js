const tagInput = document.getElementById('tagSelector');
const tagContainer = document.getElementsByClassName('tags')[0];
const selectedTags = [];

tagInput.oninput = function(){
    if(!this.value || isNaN(this.value)) return;
    var name = null;
    for(var i = 0; i < tagInput.children.length; i++){
        var value = tagInput.children[i].value;
        var tagData = parseInt(this.value);
        if(value == tagData){
            if(selectedTags.includes(tagData)) return;
            name = tagInput.children[i].innerText;
            selectedTags.push(tagData);
            break;
        }
    };
    
    if(!name) return; // error?

    var div = document.createElement('div');
    div.classList.add('tag');
    div.onclick = function(){
        this.remove();
    }
    div.innerText = name;
    tagContainer.appendChild(div);
    this.value = null;
}
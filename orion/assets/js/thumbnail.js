const input = document.getElementById("thumb");
const cont = document.getElementById("novel_thumb");

input.oninput = function(e){
    var selectedFile = e.target.files[0];
    if(!selectedFile) {
        cont.innerHTML = null;
        return;
    }
    var reader = new FileReader();

    reader.onload = function(e) {
        cont.innerHTML = null;
        var src = e.target.result;
        var img = document.createElement("img");
        img.src = src;
        cont.appendChild(img);
    };

    reader.readAsDataURL(selectedFile);
}
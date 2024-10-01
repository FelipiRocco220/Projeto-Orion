const pfp = document.getElementById("pfp");

const name = document.getElementById("name");
const tag = document.getElementById("tag");
const email = document.getElementById("email");
const button = document.getElementById("submit");

var input;
var fileImage;

pfp.onclick = () => {
    input = document.createElement('input');
    input.setAttribute('type', 'file');
    input.setAttribute('accept', '.png,.jpg,.jpeg');
    input.click();
    input.onchange = (e) => {
        if (!e?.target?.files?.length) return;
        const fileUrl = URL.createObjectURL(e.target.files[0]);
        pfp.style.backgroundImage = "url(" + fileUrl + ")";
        fileImage = e.target.files[0];
        input = null;
    };
};

button.onclick = () => {
    var formData = new FormData();
    formData.append('pfp', fileImage);
    formData.append('name', name.value);
    formData.append('tag', tag.value);
    formData.append('email', email.value);

    try {
        $.ajax({
            url: '../assets/includes/functions/edit/edit.php',
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(r) {
                console.log(r);
                if(r.length == 0) addPopup("Sucesso ao editar informações", 1);
                else addPopup(r, 0);
            }
        });
    } catch (error) {
        addPopup('Erro ao editar...', 0);
    }
}
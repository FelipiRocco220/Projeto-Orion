const read_cont = document.getElementsByClassName("read_container")[0];

var mode_btn = document.createElement("button");
mode_btn.id = 'modeBtn';
mode_btn.classList.add("dark");

function udpateBtn(mode){
    if(mode == 'dark') mode_btn.innerHTML = '<i class="bi bi-brightness-low-fill"></i>';
    else if(mode == 'light') mode_btn.innerHTML = '<i class="bi bi-moon-fill"></i>';
    localStorage.setItem('read_mode', mode);
}

var mode = localStorage.getItem('read_mode');
if(!mode){
    localStorage.setItem('read_mode', 'dark');
    read_cont.classList.add('dark');
    mode = 'dark';
} else {
    if(mode == 'dark') read_cont.classList.add('dark');
    else if(mode == 'light') read_cont.classList.add('light');
}

udpateBtn(mode);

mode_btn.onclick = function(){
    if(read_cont.classList.contains('dark')){
        read_cont.classList.remove('dark');
        read_cont.classList.add('light');
        udpateBtn('light');
    } else {
        read_cont.classList.remove('light');
        read_cont.classList.add('dark');
        udpateBtn('dark');
    }
};
read_cont.appendChild(mode_btn);
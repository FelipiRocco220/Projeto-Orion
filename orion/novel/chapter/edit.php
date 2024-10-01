<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../../assets/css/main.css">
    <style>
        .edit{
            width: 800px;
        }
        .controls{
            border: 1px solid #c4c4c4;

            display: flex;
            align-items: center;
        }
        .controls > button{
            width: 40px;
            height: 40px;
            background-color: transparent;
            border: none;
            font-size: 16px;

            display: flex;
            justify-content: center;
            align-items: center;
        }
        .controls > button:hover{
            background-color: #00000017;
            cursor: pointer;
        }
        .controls > input[type=color]{
            width: 30px;
            height: 30px;
            padding: 0;
            border: none;
            outline: none;
            background: transparent;
        }
        .controls > select{
            margin: 0 10px;
        }

        .container{
            height: 400px;
            border: 1px solid #d7d7d7;
            padding: 5px 10px;
            overflow: auto;
            font-family: Arial, sans-serif;
        }
        .container > p{
            margin: 20px 0;
        }
        .container:focus{
            outline: none;
        }

        button.active{
            background-color: #252525;
            color: white;
        }
        button.active:hover{
            background-color: black;
        }

        .fala{
            display: flex;
        }
        .fala > *:first-of-type{
            margin-right: 7px;
        }
    </style>
</head>
<body>
    <?php
    include_once('../../assets/includes/dbc.inc.php');
    include_once('../../assets/includes/session.inc.php');

    if(empty($_GET["c"])){
        die("screw you >:(");
    }
    $chapter_id = $_GET["c"];
    if(!is_numeric($chapter_id)) {
        die("screw you >:(");
    }
    $chapter_id = (float) $chapter_id;
    if(floor($chapter_id) != $chapter_id) {
        die("screw you >:(");
    }

    $query = "select file, visibility from novels_chapters where id = :chapter_id;";
    $cmd = $db->prepare($query);
    $cmd->bindParam(":chapter_id", $chapter_id);
    $cmd->execute();
    $curChapter = $cmd->fetch(PDO::FETCH_ASSOC);

    echo "<select id='view'>";
    $views = ["Privado", "Publico", "Não listado"];
    for($i = 0; $i < count($views); $i++){
        $name = $views[$i];
        if($curChapter["visibility"] == $i) echo"<option selected value='$i'>$name</option>";
        else echo"<option value='$i'>$name</option>";
    }
    echo"</select>";

    echo"</br></br>";
    ?>
    <div class="edit">
        <div class="controls">
            <button id="undo"><i class="bi bi-arrow-90deg-left"></i></button>
            <button id="redo"><i class="bi bi-arrow-90deg-right"></i></button>
            <select id="textStyle">
                <option value="0">Normal</option>
                <option value="1">Parágrafo</option>
                <option value="2">Titulo 1</option>
                <option value="3">Titulo 2</option>
                <option value="4">Titulo 3</option>
            </select>
            <button id="bold"><i class="bi bi-type-bold"></i></button>
            <button id="italian"><i class="bi bi-type-italic"></i></button>
            <button id="underline"><i class="bi bi-type-underline"></i></button>
            <button id="ul"><i class="bi bi-list-ul"></i></button>
            <button id="ol"><i class="bi bi-list-ol"></i></button>
            <button id="text-l"><i class="bi bi-text-left"></i></button>
            <button id="text-c"><i class="bi bi-text-center"></i></button>
            <button id="text-r"><i class="bi bi-text-right"></i></button>
            <button id="text-j"><i class="bi bi-justify"></i></i></button>
            <button id="image"><i class="bi bi-image"></i></button>
            <input type="color" id="color">
        </div>
    
        <div class="container" id="cont" contenteditable="true" spellcheck="false">
            <?php
            $myfile = fopen("../../assets/source/" . $curChapter["file"], "r");
            while ($line = fgets($myfile)) {
                echo($line);
            }
            fclose($myfile);
            ?>
        </div>
    </div>

    </br>
    <button id="save">Salvar</button>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../../assets/js/main.js"></script>
    <script>
        const view = document.getElementById("view");

        const ub = document.getElementById('undo');
        const rb = document.getElementById('redo');
        const tsb = document.getElementById('textStyle');
        const imgb = document.getElementById('image');
        const ib = document.getElementById('italian');
        const bb = document.getElementById('bold');
        const dlb = document.getElementById('underline');
        const ulb = document.getElementById('ul');
        const olb = document.getElementById('ol');
        const ic = document.getElementById('color');

        const saveBtn = document.getElementById('save');

        const tal = document.getElementById('text-l');
        const tac = document.getElementById('text-c');
        const tar = document.getElementById('text-r');
        const taj = document.getElementById('text-j');

        const elements = ['div', 'p', 'h1', 'h2', 'h3'];
        var italic = false;
        var bold = false;

        saveBtn.onclick = function(){
            saveBtn.disabled = true;
            if(view.value < 0 || view.value > 2) {
                addPop
                return;
            }
            $.ajax({
            url: '../../assets/includes/novel/save.php',
            method: 'POST',
            data: { novelText: page.innerHTML, chapterID: <?php echo $chapter_id; ?>, view: view.value },
            success: function(r) {
                console.log(r);
                if(r.length == 0){
                    addPopup("Sucesso ao editar informações", 1);
                }
                else {
                    addPopup("Erro ao salvar informações.", 0);
                }
            }
            });
            setTimeout(() => {
                saveBtn.disabled = false;
            }, 1000);
        }
        
        ub.onclick = function(){            
            performAction("undo");
            update();
        }
        rb.onclick = function(){            
            performAction("redo");
            update();
        }
        tsb.onchange = function(){
            var lists = [document.queryCommandState('insertUnorderedList'), document.queryCommandState('insertOrderedList')];
            if(lists[0] == true) performAction("insertUnorderedList");
            if(lists[1] == true) performAction("insertOrderedList");

            performAction('formatblock', elements[this.value]);

            if(lists[0] == true) performAction("insertUnorderedList");
            if(lists[1] == true) performAction("insertOrderedList");

            update();
        }
        ib.onclick = function(){            
            performAction("italic");
            update();
        }
        bb.onclick = function(){
            performAction("bold");
            update();
        }
        dlb.onclick = function(){
            performAction("underline");
            update();
        }
        ulb.onclick = function(){
            performAction("insertUnorderedList");
            update();
        }
        olb.onclick = function(){
            performAction("insertOrderedList");
            update();
        }
        
        tal.onclick = function(){
            performAction("justifyLeft");
            update();
        };
        tac.onclick = function(){
            performAction("justifyCenter");
            update();
        };
        tar.onclick = function(){
            performAction("justifyRight");
            update();
        };
        taj.onclick = function(){
            performAction("justifyFull");
            update();
        };

        ic.oninput = function(){
            performAction("foreColor", this.value);
        }
        
        const page = document.getElementById('cont');

        const update = function(){
            checkBtn(document.queryCommandState('bold'), bb);
            checkBtn(document.queryCommandState('italic'), ib);
            checkBtn(document.queryCommandState('underline'), dlb);
            checkBtn(document.queryCommandState('insertUnorderedList'), ulb);
            checkBtn(document.queryCommandState('insertOrderedList'), olb);

            checkBtn(document.queryCommandState('justifyLeft'), tal);
            checkBtn(document.queryCommandState('justifyCenter'), tac);
            checkBtn(document.queryCommandState('justifyRight'), tar);
            checkBtn(document.queryCommandState('justifyFull'), taj);

            var target = document.queryCommandValue('formatblock');
            for(var i = 0; i < elements.length; i++){
                if(target == elements[i]){
                    tsb.value = i;
                    break;
                }
            }

            var rgb = document.queryCommandValue('foreColor').split('(')[1].split(')')[0].split(', ');
            ic.value = rgbToHex(rgb[0], rgb[1], rgb[2]);
        }
        page.onclick = update;
        page.onkeydown = update;

        imgb.onclick = function(){
            var div = document.createElement('div');
            div.classList.add('image');
            cont.appendChild(div);

            var input = document.createElement('input');
            input.type = 'file';
            input.onchange = function(){
                if (input.value !== '') {
                    imgSrc = window.URL.createObjectURL(input.files[0]);
                    var img = new Image();
                    img.src = imgSrc;
                    input.parentElement.appendChild(img);
                    this.remove();
                }
            }
            div.appendChild(input);
        }

        function setCaretPosition(elem, caretPos) {
            if(elem != null) {
                if(elem.createTextRange) {
                    var range = elem.createTextRange();
                    range.move('character', caretPos);
                    range.select();
                }
                else {
                    if(elem.selectionStart) {
                        elem.focus();
                        elem.setSelectionRange(caretPos, caretPos);
                    }
                    else
                        elem.focus();
                }
            }
        }

        function performAction(command, value = null) {
            setCaretPosition(page, 0);
            document.execCommand(command, false, value);
        }
        function checkBtn(bool, btn){
            if(bool == true) btn.classList.add('active');
            else btn.classList.remove('active');
        }
        function componentToHex(c) {
            c = parseInt(c);
            var hex = c.toString(16);
            return hex.length == 1 ? "0" + hex : hex;
        }

        function rgbToHex(r, g, b) {
            return "#" + componentToHex(r) + componentToHex(g) + componentToHex(b);
        }
    </script>
</body>
</html>
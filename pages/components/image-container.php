<?php function create_image_container($images){
    $rootDir = realpath($_SERVER["DOCUMENT_ROOT"]);
    if(($images->num_rows !==0 )){
?>
    <div class="image-container">
        <?php foreach($images as $immagine){ ?>
            <?php if(file_exists($rootDir.$immagine["nomeFile"])) {?>
                <div class ="image" >
                    <img  src="<?= $immagine["nomeFile"]?>"> </img>
                </div>
            <?php }else{?>
                <div class ="image" >
                    <img  alt = "immagine non trovata" src="https://placehold.co/800x600"> </img>
                </div>
            <?php }?>
        <?php } ?>
        <div class="image-button">
            <button id="prev" onclick="previousImage()"><-</button>
            <button id="next" onclick="nextImage()">-></button>
        </div>
    </div>
<?php }}?>
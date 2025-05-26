<?php function create_image_container($images){
    if(isset($images)){
?>
    <div class="image-container">
        <?php foreach($images as $immagini): ?>
            <img class ="image" src="<?= $immagini["nomeFile"]?>"> </img>
        <?php endforeach ?>
        <div class="image-button">
            <button id="prev" onclick="previousImage()"><-</button>
            <button id="next" onclick="nextImage()">-></button>
        </div>
    </div>
<?php }}?>
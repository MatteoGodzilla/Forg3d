<?php function analytics_info($infos){ ?>
        <div id = "stats">
            <?php foreach($infos as $name=>$value){ ?>
                <p><?=$name?> <?=$value?></p>
            <?php } ?>
        </div>
<?php } ?>




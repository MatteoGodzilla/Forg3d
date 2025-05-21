

<?php function create_error_message(String $message){ ?>
    <div id="error-popup">
    <p><?= $message ?></p>
    </div>
<?php } ?>

<?php function create_success_message(String $message){ ?>
    <div id="success-popup">
       <p><?= $message ?></p>
    </div>
<?php } ?>

<?php function create_warning_message(String $message){ ?>
    <div id="warning-popup">
    <p><?= $message ?></p>
    </div>
<?php } ?>

<?php function create_popup($message ,int $type){
    switch($type){
        case AlertType::SUCCESS->value: create_success_message($message);break;
        case AlertType::WARNING->value: create_warning_message($message);break;
        case AlertType::ERROR->value: create_error_message($message);break;
    }
}
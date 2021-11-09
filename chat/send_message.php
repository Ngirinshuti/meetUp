<?php 

require_once __DIR__ . '/../classes/init.php';
require_once __DIR__ . '/../forms/Validator.php';
require_once __DIR__ . '/Message.php';

$validator = new Validator();


$validator->methodPost(function (Validator $validator){
    $validator->addRules([
        'message' => ['not_empty' => true],
        'reciever' => [],
        'group_id' => []
    ])->addData($_POST)->validate();

    $validator->isValid(function(Validator $validator) {
        $reciever = $validator->valid_data['reciever'];
        $group_id = $validator->valid_data['group_id'];
        $message = $validator->valid_data['message'];

        // if ($)

        
        $msg = Message::create(
            sender: me()->username,
            body: $message,
            group_id: $group_id ?: null,
            reciever: $reciever ?: null
        );

        sendJson(fn() => $msg);
        
    });


    $validator->isInvalid(function(Validator $validator) {
        sendJson(fn() => $validator->getErrors());
    });

});


// sendJson(fn () => "HERE");
function sendJson(Closure $callback)
{
    header("Content-Type: application/json", true, 200);
    // exit("HERE");
    exit(json_encode($callback()));
}



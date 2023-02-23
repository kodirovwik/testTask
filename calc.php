<?php
$request = json_decode(file_get_contents('php://input'));
header('Content-Type: application/json');
$bottleQuantity = $request->bottleQuantity;
if (!is_numeric($bottleQuantity)) {
    http_response_code(400);
    exit();
}
$result = [
    'small_box' => 0,
    'medium_box' => 0,
    'big_box' => 0,
];

const BIG_BOX_CAPACITY = 12;
const MEDIUM_BOX_CAPACITY = 6;
const SMALL_BOX_CAPACITY = 3;

while ($bottleQuantity > 0) {
    if(trySmallBox($bottleQuantity)) {
        $result ['small_box'] = 1;
        $bottleQuantity -= SMALL_BOX_CAPACITY;
    }
    elseif (tryMediumBox($bottleQuantity)) {
        $result ['medium_box'] = 1;
        $bottleQuantity -= MEDIUM_BOX_CAPACITY;
    }
    else {
        if ($bottleQuantity <= BIG_BOX_CAPACITY) {
            $result ['big_box'] += 1;
            $bottleQuantity -= BIG_BOX_CAPACITY;
        }
        else {
            $big_box_quantity = fillBigBox($bottleQuantity);
            $result ['big_box'] = $big_box_quantity;
            $bottleQuantity -= $big_box_quantity * BIG_BOX_CAPACITY;
        }
    }
}

function trySmallBox ($bottleQuantity) {
    return $bottleQuantity / SMALL_BOX_CAPACITY <= 1;
}

function tryMediumBox ($bottleQuantity) {
    return $bottleQuantity / MEDIUM_BOX_CAPACITY <= 1;
}

function fillBigBox ($bottleQuantity) {
    return intdiv($bottleQuantity, BIG_BOX_CAPACITY);
}



echo json_encode([
    'result' => $result
]);

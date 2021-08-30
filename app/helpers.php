<?php

function resource_data($resource) {
    return $resource->response()->getData(true);
}

function get_model_name_from_controller($controller) {
    $arr = preg_split('/(?=[A-Z])/', $controller, -1, PREG_SPLIT_NO_EMPTY);

    return ! empty($arr)? $arr[0] : '';
}

<?php

function resource_data($resource) {
    return $resource->response()->getData(true);
}

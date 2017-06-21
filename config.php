<?php

$publicDir = realpath(dirname(CRAFT_BASE_PATH).'/public');

return [
    'publicDir' => $publicDir ?: 'public',
];

<?php

namespace Formatters\Json;

/**
 * @param mixed $data
 * @return string
 */
function jsonFormatter($data): string
{
    $res = json_encode($data, JSON_FORCE_OBJECT | JSON_PRETTY_PRINT);

    return $res !== false ? $res : '';
}

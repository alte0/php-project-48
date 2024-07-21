<?php

namespace Formatters\Json;

function jsonFormatter($data)
{
    return json_encode($data, JSON_FORCE_OBJECT | JSON_PRETTY_PRINT);
}

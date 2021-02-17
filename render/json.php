<?php

# JSON renderer

header('Content-type: text/json;charset=utf8');
include __DIR__ . "/../views/{$action}.json.phtml";
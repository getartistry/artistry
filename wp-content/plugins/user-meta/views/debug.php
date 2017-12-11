<?php
namespace UserMeta;

global $userMeta;

if (!empty($_GET["option"])) {
    $userMeta->dump($userMeta->getData($_GET["option"]));
} else {
    phpinfo();
}


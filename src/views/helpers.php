<?php
function render($view, $params = [])
{
    extract($params);
    ob_start();
    require __DIR__ . "/$view.php";
    $content = ob_get_clean();
    require __DIR__ . "/layout.php";
}

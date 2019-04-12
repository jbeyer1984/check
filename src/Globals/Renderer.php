<?php


namespace Check\Globals;


class Renderer
{
    public function render($templatePathArray, array $parameters = [])
    {
        $fullPathArray = array_merge([VIEW], $templatePathArray);
        extract($parameters);
        ob_start();
        include_once(implode(DIRECTORY_SEPARATOR, $fullPathArray));
        echo ob_get_clean();
    }
}
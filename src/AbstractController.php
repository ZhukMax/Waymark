<?php

namespace Zhukmax\Waymark;

/**
 * Class AbstractController
 * @package Zhukmax\Waymark
 */
abstract class AbstractController
{
    /** @var Interfaces\TplEngineInterface */
    protected $tpl;

    /**
     * AbstractController constructor.
     * @param $tpl
     */
    public function __construct($tpl)
    {
        $this->tpl = $tpl;
    }
}

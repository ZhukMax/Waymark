<?php

namespace Zhukmax\SimpleRouter;

/**
 * Class AbstractController
 * @package Zhukmax\SimpleRouter
 */
abstract class AbstractController
{
    /** @var TplEngineInterface */
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

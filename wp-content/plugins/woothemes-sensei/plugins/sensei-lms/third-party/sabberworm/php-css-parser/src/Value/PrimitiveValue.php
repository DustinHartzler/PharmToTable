<?php

namespace Sensei\ThirdParty\Sabberworm\CSS\Value;

abstract class PrimitiveValue extends \Sensei\ThirdParty\Sabberworm\CSS\Value\Value
{
    /**
     * @param int $iLineNo
     */
    public function __construct($iLineNo = 0)
    {
        parent::__construct($iLineNo);
    }
}

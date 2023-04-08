<?php

namespace Sensei\ThirdParty\Sabberworm\CSS;

interface Renderable
{
    /**
     * @return string
     */
    public function __toString();
    /**
     * @return string
     */
    public function render(\Sensei\ThirdParty\Sabberworm\CSS\OutputFormat $oOutputFormat);
    /**
     * @return int
     */
    public function getLineNo();
}

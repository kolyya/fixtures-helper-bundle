<?php

namespace Kolyya\FixturesHelperBundle\Service;

class UploaderHelper
{
    private $kernelProjectDir;

    public function __construct($kernelProjectDir)
    {
        $this->kernelProjectDir = $kernelProjectDir;
    }

    public function getKernelProjectDir()
    {
        return $this->kernelProjectDir;
    }
}

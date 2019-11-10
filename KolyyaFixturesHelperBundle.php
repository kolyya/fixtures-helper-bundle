<?php

namespace Kolyya\FixturesHelperBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class KolyyaFixturesHelperBundle extends Bundle
{
    public function getContainerExtension()
    {
        if (null === $this->extension) {
            $this->extension = $this->createContainerExtension();
        }

        return $this->extension;
    }
}
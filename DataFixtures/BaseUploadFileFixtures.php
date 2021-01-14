<?php

namespace Kolyya\FixturesHelperBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Kolyya\FixturesHelperBundle\DataFixtures\Traits\UploadFileTrait;

/**
 * @deprecated Use "extends Fixture implements UploadFileFixturesInterface"
 */
abstract class BaseUploadFileFixtures extends Fixture implements UploadFileFixturesInterface
{
    use UploadFileTrait;
}

<?php

namespace Kolyya\FixturesHelperBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Kolyya\FixturesHelperBundle\Service\UploaderHelper;

abstract class BaseUploadFileFixtures extends Fixture implements UploadFileFixturesInterface
{
    protected $uploaderHelper;

    public function __construct(UploaderHelper $uploaderHelper)
    {
        $this->uploaderHelper = $uploaderHelper;
    }

    public function load(ObjectManager $manager)
    {
    }

    public function getPath()
    {
        return sprintf('%s%s/', $this->uploaderHelper->getKernelProjectDir(), $this->getAssetPath());
    }

    /**
     * Should return relative path to the directory with files
     * @return string
     * @example /assets/fixtures/product
     */
    public function getAssetPath(): string
    {
        return '';
    }
}
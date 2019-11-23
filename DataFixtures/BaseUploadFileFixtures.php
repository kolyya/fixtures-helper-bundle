<?php

namespace Kolyya\FixturesHelperBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Kolyya\FixturesHelperBundle\Service\UploaderHelper;
use Symfony\Component\HttpFoundation\File\UploadedFile;

abstract class BaseUploadFileFixtures extends Fixture implements UploadFileFixturesInterface
{
    protected $uploaderHelper;

    public function __construct(UploaderHelper $uploaderHelper)
    {
        $this->uploaderHelper = $uploaderHelper;
    }

    protected function getUploadedFile(string $imageName): UploadedFile
    {
        $imagePath = sprintf('%s%s', $this->getPath(), $imageName);
        copy($imagePath, sprintf('%s.tmp', $imagePath));

        return new UploadedFile($imagePath . '.tmp', $imageName, null, null, true);
    }

    public function getPath()
    {
        return sprintf('%s%s/', $this->uploaderHelper->getKernelProjectDir(), $this->getAssetPath());
    }
}
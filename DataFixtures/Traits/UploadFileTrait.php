<?php

namespace Kolyya\FixturesHelperBundle\DataFixtures\Traits;

use Kolyya\FixturesHelperBundle\Service\UploaderHelper;
use Symfony\Component\HttpFoundation\File\UploadedFile;

trait UploadFileTrait
{
    private $kernelProjectDir;

    public function __construct(UploaderHelper $uploaderHelper)
    {
        $this->kernelProjectDir = $uploaderHelper->getKernelProjectDir();
    }

    public function getUploadedFile(string $imageName): UploadedFile
    {
        $imagePath = sprintf('%s%s', $this->getPath(), $imageName);
        copy($imagePath, sprintf('%s.tmp', $imagePath));

        return new UploadedFile($imagePath . '.tmp', $imageName, null, null, true);
    }

    public function getPath(): string
    {
        return sprintf('%s%s/', $this->kernelProjectDir, $this->getAssetPath());
    }

    public function clearDirectories(array $paths): void
    {
        foreach ($paths as $path) {
            $files = glob($this->kernelProjectDir . $path[0], $path[1]);
            foreach ($files as $file) {
                if (file_exists($file)) {
                    unlink($file);
                }
            }
        }
    }
}

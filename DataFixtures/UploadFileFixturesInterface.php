<?php

namespace Kolyya\FixturesHelperBundle\DataFixtures;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface UploadFileFixturesInterface
{
    /**
     * Should return relative path to the directory with files
     * @return string
     * @example /assets/fixtures/product
     */
    public function getAssetPath(): string;

    public function getUploadedFile(string $imageName): UploadedFile;

    public function clearDirectories(array $paths): void;
}

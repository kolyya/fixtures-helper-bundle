<?php

namespace Kolyya\FixturesHelperBundle\DataFixtures;

interface UploadFileFixturesInterface
{
    /**
     * Should return relative path to the directory with files
     * @example /assets/fixtures/product
     * @return string
     */
    public function getAssetPath(): string;
}
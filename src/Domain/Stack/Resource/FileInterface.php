<?php declare(strict_types=1);

namespace Kiboko\Cloud\Domain\Stack\Resource;

interface FileInterface
{
    /**
     * @return resource
     */
    public function asResource();

    public function asBlob(): string;

    public function getPath(): string;

    public function getDirectory(): string;
}
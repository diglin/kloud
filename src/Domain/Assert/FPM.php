<?php

declare(strict_types=1);

namespace Kiboko\Cloud\Domain\Assert;

use Kiboko\Cloud\Domain\Packaging;

final class FPM implements ConstraintInterface
{
    private Packaging\RepositoryInterface $repository;

    public function __construct(
        Packaging\RepositoryInterface $repository
    ) {
        $this->repository = $repository;
    }

    public function apply(\Traversable $tagRepository): \Traversable
    {
        foreach ($tagRepository as $tag) {
            if (!preg_match('/^(\d+\.\d+)-fpm(?:-|$)/', (string) $tag, $matches)) {
                continue;
            }

            $constraint = sprintf('^%s', $matches[1]);

            yield new FPMConstraint($this->repository, $tag, $constraint);
        }
    }
}

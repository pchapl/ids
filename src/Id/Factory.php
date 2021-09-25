<?php

declare(strict_types=1);

namespace PChapl\DoctrineIdBundle\Id;

interface Factory
{
    public function generate(): string;
}

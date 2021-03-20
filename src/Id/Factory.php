<?php

declare(strict_types=1);

namespace pchapl\DoctrineIdBundle\Id;

interface Factory
{
    public function generate(): string;
}

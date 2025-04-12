<?php

namespace App\Doctrine;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;

class SoftDeleteFilter extends SQLFilter
{
    public function addFilterConstraint(ClassMetadata $targetEntity, string $targetTableAlias): string
    {
        if (!$targetEntity->reflClass->hasProperty('isDeleted')) {
            return '';
        }

        return sprintf('%s.is_deleted = 0', $targetTableAlias);
    }

}

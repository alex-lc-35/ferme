<?php

namespace App\EventListener;

use App\Attribute\AutoTitleCase;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use ReflectionClass;
use ReflectionProperty;


class TitleCaseListener
{
    public function prePersist(object $entity, LifecycleEventArgs $args): void
    {
        $this->applyTitleCase($entity);
    }

    public function preUpdate(object $entity, LifecycleEventArgs $args): void
    {
        $this->applyTitleCase($entity);
    }

    private function applyTitleCase(object $entity): void
    {
        $reflection = new ReflectionClass($entity);

        foreach ($reflection->getProperties() as $property) {
            if (!$this->shouldFormat($property)) {
                continue;
            }

            $property->setAccessible(true);
            $value = $property->getValue($entity);

            if (is_string($value)) {
                $formatted = mb_convert_case(trim($value), MB_CASE_TITLE, 'UTF-8');
                $property->setValue($entity, $formatted);
            }
        }
    }

    private function shouldFormat(ReflectionProperty $property): bool
    {
        return count($property->getAttributes(AutoTitleCase::class)) > 0;
    }
}

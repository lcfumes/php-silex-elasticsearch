<?php

namespace Domain\Collectors;

class ClientCollector extends \SplObjectStorage
{
    public function add($clientEntity)
    {
        if (!$clientEntity instanceof \Domain\Entities\ClientEntity) {
            throw new \InvalidArgumentException('Invalid Argument to ClientCollector');
        }
        $this->attach($clientEntity);
    }

    public function toArray()
    {
        $return = [];

        foreach ($this as $item) {
            $return[] = $item->toArray();
        }

        return $return;
    }
}

<?php
namespace Domain\Collectors;

class ClientCollector extends \SplObjectStorage
{

    public function attach($clientEntity) {
        if (!$clientEntity instanceof \Domain\Entities\ClientEntity) {
            throw new InvalidArgumentException('Invalid Argument to ClientCollector');
        }
        parent::attach($clientEntity);
    }

    public function toArray() {
        $return = [];

        foreach ($this as $item) {
            $return[] = $item->toArray();
        }

        return $return;
    }
}

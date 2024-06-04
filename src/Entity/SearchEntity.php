<?php

namespace App\Entity;

class SearchEntity
{
    private ?string $search = null;

    public function getSearch(): ?string
    {
        return $this->search;
    }

    public function setSearch(?string $query): self
    {
        $this->search = $query;
        return $this;
    }
}

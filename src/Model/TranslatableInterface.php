<?php

namespace App\Model;

interface TranslatableInterface
{
    public function getLabelFr(): ?string;

    public function getLabelEn(): ?string;
}

<?php

namespace directapi\common\containers;

use Symfony\Component\Validator\Constraints as Assert;

class ArrayOfString
{
    /**
     * @var string[]
     * @Assert\NotBlank()
     */
    public $Items;
}
<?php

namespace directapi\services\adgroups\models;


use directapi\services\adgroups\enum\CarrierEnum;
use directapi\services\adgroups\enum\DeviceTypeEnum;
use Symfony\Component\Validator\Constraints as Assert;

class MobileAppAdGroupAdd
{
    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $StoreUrl;

    /**
     * @var DeviceTypeEnum[]
     * @Assert\NotBlank()
     * @Assert\Valid()
     */
    public $TargetDeviceType;

    /**
     * @var CarrierEnum
     * @Assert\NotBlank()
     */
    public $TargetCarrier;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $TargetOperatingSystemVersion;
}
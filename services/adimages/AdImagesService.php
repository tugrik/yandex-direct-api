<?php

namespace directapi\services\adimages;

use directapi\common\criterias\IdsCriteria;
use directapi\common\criterias\LimitOffset;
use directapi\services\BaseService;

class AdImagesService extends BaseService
{
    public function get($SelectionCriteria, array $FieldNames, LimitOffset $Page)
    {
        $params = [
            'SelectionCriteria' => $SelectionCriteria,
            'FieldNames' => $FieldNames
        ];
        if ($Page) {
            $params['Page'] = $Page;
        }

        return $this->doGet($params, 'AdImages', false);
    }

    protected function getName()
    {
        return 'adimages';
    }

    public function add(array $AdImages)
    {
        return false;
//        return parent::doAdd($params);
    }

    public function delete(IdsCriteria $SelectionCriteria)
    {
        return false;
    }

}
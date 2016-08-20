<?php

namespace directapi\services\adextensions;

use directapi\common\criterias\IdsCriteria;
use directapi\common\criterias\LimitOffset;
use directapi\services\BaseService;

class AdExtensionsService extends BaseService
{
    /**
     * @param IdsCriteria $SelectionCriteria
     * @param array $FieldNames
     * @param LimitOffset $Page
     * @return array []
     */
    public function get(IdsCriteria $SelectionCriteria, array $FieldNames, LimitOffset $Page)
    {
        $params = [
            'SelectionCriteria' => $SelectionCriteria,
            'FieldNames' => $FieldNames,
            'CalloutFieldNames' => ['CalloutText']
        ];
        if ($Page) {
            $params['Page'] = $Page;
        }

        return $this->doGet($params, 'AdExtensions', false);
    }

    protected function getName()
    {
        return 'adextensions';
    }

    public function add(array $SitelinksSets)
    {
        return false;
//        return parent::doAdd($params);
    }

    public function delete(IdsCriteria $SelectionCriteria)
    {
        return parent::delete($SelectionCriteria);
    }

}
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
            'FieldNames'        => $FieldNames,
            'CalloutFieldNames' => ['CalloutText']
        ];
        if ($Page) {
            $params['Page'] = $Page;
        }

        return parent::doGet($params, 'SitelinksSets', false);
    }

    protected function getName()
    {
        return 'AdExtensions';
    }
}
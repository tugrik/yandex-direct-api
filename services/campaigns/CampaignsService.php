<?php

namespace directapi\services\campaigns;

use directapi\common\criterias\IdsCriteria;
use directapi\common\criterias\LimitOffset;
use directapi\common\results\ActionResult;
use directapi\services\BaseService;
use directapi\services\campaigns\criterias\CampaignsSelectionCriteria;
use directapi\services\campaigns\enum\CampaignFieldEnum;
use directapi\services\campaigns\enum\MobileAppCampaignFieldEnum;
use directapi\services\campaigns\enum\TextCampaignFieldEnum;
use directapi\services\campaigns\models\CampaignAddItem;
use directapi\services\campaigns\models\CampaignGetItem;
use directapi\services\campaigns\models\CampaignUpdateItem;

class CampaignsService extends BaseService
{
    public static $name = 'Campaigns';

    /**
     * @param CampaignAddItem[] $Campaigns
     *
     * @return ActionResult[]
     */
    public function add(array $Campaigns)
    {
        $params = [
            'Campaigns' => $Campaigns
        ];
        return parent::doAdd($params);
    }

    /**
     * @inheritdoc
     */
    public function archive(IdsCriteria $SelectionCriteria)
    {
        return parent::archive($SelectionCriteria);
    }

    /**
     * @inheritdoc
     */
    public function delete(IdsCriteria $SelectionCriteria)
    {
        return parent::archive($SelectionCriteria);
    }

    public function get(
        CampaignsSelectionCriteria $SelectionCriteria,
        array $FieldNames,
        array $TextCampaignFieldNames = [],
        array $MobileAppCampaignFieldNames = [],
        array $DynamicTextCampaignFieldNames = [],
        LimitOffset $Page = null
    ) {
        $params = [
            'SelectionCriteria' => $SelectionCriteria,
            'FieldNames'        => $FieldNames,
        ];
        if ($TextCampaignFieldNames) {
            $params['TextCampaignFieldNames'] = $TextCampaignFieldNames;
        }
        if ($MobileAppCampaignFieldNames) {
            $params['MobileAppCampaignFieldNames'] = $MobileAppCampaignFieldNames;
        }
        if ($DynamicTextCampaignFieldNames) {
            $params['DynamicTextCampaignFieldNames'] = $DynamicTextCampaignFieldNames;
        }
        if ($Page) {
            $params['Page'] = $Page;
        }
        return parent::doGet($params, 'Campaigns', null);
    }

    /**
     * @inheritdoc
     */
    public function resume(IdsCriteria $SelectionCriteria)
    {
        return parent::resume($SelectionCriteria);
    }

    /**
     * @inheritdoc
     */
    public function suspend(IdsCriteria $SelectionCriteria)
    {
        return parent::suspend($SelectionCriteria);
    }

    /**
     * @inheritdoc
     */
    public function unarchive(IdsCriteria $SelectionCriteria)
    {
        return parent::unarchive($SelectionCriteria);
    }

    /**
     * @param CampaignUpdateItem[] $Campaigns
     *
     * @return ActionResult[]
     */
    public function update($Campaigns)
    {
        $params = [
            'Campaigns' => $Campaigns
        ];
        return parent::doUpdate($params);
    }

    protected function getName()
    {
        return 'campaigns';
    }
}
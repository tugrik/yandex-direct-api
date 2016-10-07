<?php

namespace directapi;

use directapi\exceptions\DirectApiException;
use directapi\exceptions\RequestValidationException;
use directapi\services\adextensions\AdExtensionsService;
use directapi\services\adgroups\AdGroupsService;
use directapi\services\adimages\AdImagesService;
use directapi\services\ads\AdsService;
use directapi\services\bidmodifiers\BidModifiersService;
use directapi\services\bids\BidsService;
use directapi\services\campaigns\CampaignsService;
use directapi\services\changes\ChangesService;
use directapi\services\keywords\KeywordsService;
use directapi\services\sitelinks\SitelinksService;
use directapi\services\vcards\VCardsService;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validation;

class DirectApiService
{
    private $token;
    private $login;
    private $clientLogin;
    private $apiUrl = 'https://api.direct.yandex.com/json/v5/';

    public $units = 0;
    public $lastCallCost = 0;
    public $unitsLimit = 0;

    /**
     * @var AdGroupsService
     */
    private $adGroupsService;

    /**
     * @var AdsService
     */
    private $adsService;

    /**
     * @var BidModifiersService
     */
    private $bidModifiersService;

    /**
     * @var BidsService
     */
    private $bidsService;

    /**
     * @var CampaignsService
     */
    private $campaignsService;

    /**
     * @var ChangesService
     */
    private $changesService;

    /**
     * @var KeywordsService
     */
    private $keywordsService;

    /**
     * @var SitelinksService
     */
    private $sitelinksService;

    /**
     * @var VCardsService
     */
    private $vcardsService;

    /**
     * @var AdImagesService
     */
    private $adImagesService;

    public function __construct($login, $token, $clientLogin)
    {
        $this->token = $token;
        $this->login = $login;
        $this->clientLogin = $clientLogin;
    }

    /**
     * @return AdGroupsService
     */
    public function getAdGroupsService()
    {
        if (!$this->adGroupsService) {
            $this->adGroupsService = new AdGroupsService($this);
        }
        return $this->adGroupsService;
    }

    /**
     * @return AdsService
     */
    public function getAdsService()
    {
        if (!$this->adsService) {
            $this->adsService = new AdsService($this);
        }
        return $this->adsService;
    }

    /**
     * @return AdImagesService
     */
    public function getAdImagesService()
    {
        if (!$this->adImagesService) {
            $this->adImagesService = new AdImagesService($this);
        }
        return $this->adImagesService;
    }

    /**
     * @return BidModifiersService
     */
    public function getBidModifiersService()
    {
        if (!$this->bidModifiersService) {
            $this->bidModifiersService = new BidModifiersService($this);
        }
        return $this->bidModifiersService;
    }

    /**
     * @return BidsService
     */
    public function getBidsService()
    {
        if (!$this->bidsService) {
            $this->bidsService = new BidsService($this);
        }
        return $this->bidsService;
    }

    /**
     * @return CampaignsService
     */
    public function getCampaignsService()
    {
        if (!$this->campaignsService) {
            $this->campaignsService = new CampaignsService($this);
        }
        return $this->campaignsService;
    }

    /**
     * @return ChangesService
     */
    public function getChangesService()
    {
        if (!$this->changesService) {
            $this->changesService = new ChangesService($this);
        }
        return $this->changesService;
    }

    /**
     * @return KeywordsService
     */
    public function getKeywordsService()
    {
        if (!$this->keywordsService) {
            $this->keywordsService = new KeywordsService($this);
        }
        return $this->keywordsService;
    }

    /**
     * @return SitelinksService
     */
    public function getSitelinksService()
    {
        if (!$this->sitelinksService) {
            $this->sitelinksService = new SitelinksService($this);
        }
        return $this->sitelinksService;

    }

    private $adExtensionsService;
    /**
     * @return AdExtensionsService
     */
    public function getAdExtensionsService()
    {
        if (!$this->adExtensionsService) {
            $this->adExtensionsService = new AdExtensionsService($this);
        }
        return $this->adExtensionsService;

    }

    /**
     * @return VCardsService
     */
    public function getVCardsService()
    {
        if (!$this->vcardsService) {
            $this->vcardsService = new VCardsService($this);
        }
        return $this->vcardsService;
    }

    /**
     * @param string $serviceName
     * @param string $method
     * @param array  $params
     * @return mixed
     * @throws DirectApiException
     */
    public function call($serviceName, $method, array $params = [])
    {
        $request = [
            'method' => $method,
        ];
        if ($params) {
            $errors = [];
            $this->validate($params, $errors);
            if ($errors) {
                throw new RequestValidationException($errors);
            }
            $request['params'] = $params;
        }

        $data = $this->getResponse($serviceName, $method, $request);
        return $data->result;
    }

    private $validator;

    /**
     * @return \Symfony\Component\Validator\ValidatorInterface
     */
    private function getValidator()
    {
        if (!$this->validator) {

            $this->validator = Validation::createValidatorBuilder()
                ->enableAnnotationMapping()
                ->getValidator();

        }
        return $this->validator;
    }

    public function validate($params, &$errors)
    {
        if (is_array($params) || is_object($params)) {
            foreach ($params as $key => $value) {
                if (!is_array($value) && !is_object($value)) {
                    continue;
                }
                $result = $this->getValidator()->validate($value, null, true);
                if ($result) {
                    foreach ($result as $error) {
                        if (!isset($errors[$key])) {
                            $errors[$key] = [];
                        }
                        /**
                         * @var ConstraintViolation $error
                         */
                        $errors[$key][$error->getPropertyPath()] = $error->getMessage();
                    }
                }
            }
        }
    }

    private $ch;

    /**
     * @return resource
     */
    private function getCurl()
    {
        if (!$this->ch) {
            $this->ch = curl_init();
            curl_setopt(
                $this->ch,
                CURLOPT_HTTPHEADER,
                [
                    'Content-Type: application/json; charset=utf-8',
                    'Authorization: Bearer ' . $this->token,
                    'Client-Login: ' . $this->clientLogin,
                    'Accept-Language: ru',
                ]
            );
            curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($this->ch, CURLOPT_POST, 1);
            //curl_setopt($this->ch, CURLINFO_HEADER_OUT, 1);
            curl_setopt($this->ch, CURLOPT_HEADER, 1);
            //curl_setopt($this->ch, CURLOPT_VERBOSE, 1);

            curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($this->ch, CURLOPT_TIMEOUT, 1200); //max to 20 minutes
        }
        return $this->ch;
    }

    /**
     * @var \JsonMapper
     */
    private $mapper;

    /**
     * @return \JsonMapper
     */
    public function getMapper()
    {
        if (!$this->mapper) {
            $this->mapper = new \JsonMapper();
        }

        return $this->mapper;
    }

    /**
     * @param $serviceName
     * @param $method
     * @param $request
     * @return object
     * @throws \directapi\exceptions\DirectApiException
     */
    public function getResponse($serviceName, $method, $request)
    {
        $curl = $this->getCurl();
        curl_setopt($curl, CURLOPT_URL, $this->apiUrl . $serviceName);
        $request = json_encode($request, JSON_UNESCAPED_UNICODE);
        $request = preg_replace('/,\s*"[^"]+":null|"[^"]+":null,?/', '', $request);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
        $response = curl_exec($curl);

        $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $header = substr($response, 0, $header_size);
//        $info = curl_getinfo($curl);
        //d($info);die;

        $body = substr($response, $header_size);
        $data = json_decode($body);
        $regex = '/Units: (\d+)\/(\d+)\/(\d+)/';
        if (preg_match($regex, $header, $matches)) {
            list(, $cost, $last, $limit) = $matches;
            $this->units = $last;
            $this->lastCallCost = $cost;
            $this->unitsLimit = $limit;
        }

        if (isset($data->error)) {
            if ($data->error->error_code == 506) //concurrent limit
            {
                usleep(100);
                return $this->getResponse($serviceName, $method, $request);
            }
            throw new DirectApiException($data->error->error_string . ' ' . $data->error->error_detail . ' (' . $serviceName . ', ' . $method . ')',
                $data->error->error_code);
        }
        if (!is_object($data)) {
            throw new DirectApiException('Ошибка при получении данных компании (' . $serviceName . ', ' . $method . ')'
                . var_export($request, true));
        }
        return $data;
    }
}
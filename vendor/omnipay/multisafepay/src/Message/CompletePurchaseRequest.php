<?php
/**
 * MultiSafepay XML Api Complete Purchase Request.
 */

namespace Omnipay\MultiSafepay\Message;

use SimpleXMLElement;

/**
 * MultiSafepay XML Api Complete Purchase Request.
 *
 * @deprecated This API is deprecated and will be removed in
 * an upcoming version of this package. Please switch to the Rest API.
 */
class CompletePurchaseRequest extends PurchaseRequest
{
    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        $this->validate('transactionId');

        $data = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><status/>');
        $data->addAttribute('ua', $this->userAgent);

        $merchant = $data->addChild('merchant');
        $merchant->addChild('account', $this->getAccountId());
        $merchant->addChild('site_id', $this->getSiteId());
        $merchant->addChild('site_secure_code', $this->getSiteCode());

        $transaction = $data->addChild('transaction');
        $transaction->addChild('id', $this->getTransactionId());

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function sendData($data)
    {
        $httpResponse = $this->httpClient->request(
            'POST',
            $this->getEndpoint(),
            $this->getHeaders(),
            $data->asXML()
        );

        $this->response = new CompletePurchaseResponse(
            $this,
            simplexml_load_string($httpResponse->getBody()->getContents())
        );

        return $this->response;
    }
}

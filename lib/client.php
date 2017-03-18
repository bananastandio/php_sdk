<?php

namespace Banana;

class Client
{
    protected $publicKey;
    protected $secretKey;
    protected $httpClient;
    protected $visitorId;
    protected $apiUrl = "https://app.bananastand.io/api/v1/";

    public function __construct($publicKey, $secretKey = null)
    {
        $this->secretKey = $secretKey;
        $this->publicKey = $publicKey;
        $this->loadVisitorId();
        $this->createHttpClient();
    }

    public function pushOrderEvent($productId, $visitorId = null, $customerId = null)
    {
        return $this->pushEvent('order', $productId, $visitorId, $customerId);
    }

    public function pushAddToCartEvent($productId, $visitorId = null, $customerId = null)
    {
        return $this->pushEvent('add_to_cart', $productId, $visitorId, $customerId);
    }

    public function pushViewEvent($productId, $visitorId = null, $customerId = null)
    {
        return $this->pushEvent('view', $productId, $visitorId, $customerId);
    }

    public function pushEvent($eventType, $productId, $visitorId = null, $customerId = null)
    {
        $uri = "stores/{$this->publicKey}/";
        $uri .= "push_event/{$eventType}/";
        $uri .= "p/{$productId}/c/{$customerId}.png";
        $uri .= "?". $this->getVisitorIdParams($visitorId);
        return $this->httpClient->get($uri);
    }

    public function getProductPageHtml($productId, $visitorId = null)
    {
        $uri = "stores/{$this->publicKey}/content/product_page.html";
        $uri .= "?product_id={$productId}&" . $this->getVisitorIdParams($visitorId);
        $resp = $this->httpClient->get($uri);
        return $resp->getBody();
    }

    public function setApiUrl($apiUrl)
    {
        $this->apiUrl = $apiUrl;
        $this->createHttpClient();
    }

    public function get($resourcePath)
    {
        return $this->httpClient->get($resourcePath);
    }

    public function delete($resourcePath)
    {
        return $this->httpClient->delete($resourcePath);
    }

    private function getVisitorIdParams($visitorId = null)
    {
        $paramStr = "visitor_id=";
        if (!empty($visitorId)) {
            $paramStr .= $visitorId;
        } else {
            $paramStr .= $this->visitorId;
        }
        return $paramStr;
    }

    protected function createHttpClient()
    {
        $headers = array('X-Public-key' => $this->publicKey, 'Content-Type' => 'application/json');
        if (!empty($this->secretKey)) {
            $headers['X-Secret-Key'] = $this->secretKey;
        }

        $this->httpClient = new \GuzzleHttp\Client(array(
            'base_uri' => $this->apiUrl,
            'headers' => $headers
        ));
    }

    protected function loadVisitorId()
    {
        if (!empty($_COOKIES['banana_stand_visitor_id'])) {
            $this->visitorId = $_COOKIES['banana_stand_visitor_id'];
        }

        if (empty($this->visitorId)) {
            $this->visitorId = uniqid();
            $_COOKIES['banana_stand_visitor_id'] = $this->visitorId;
        }
    }
}

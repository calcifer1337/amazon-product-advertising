<?php declare(strict_types=1);

namespace Calcifer1337\AmazonProductAdvertising;

class Client {

    protected $amazonPartnerTag;
    protected $amazonAccessKey;
    protected $amazonSecretKey;

    public function __construct(string $partnerTag, string $accessKey, string $secretKey)
    {
        $this->amazonPartnerTag = $partnerTag;
        $this->amazonAccessKey = $accessKey;
        $this->amazonSecretKey = $secretKey;
    }

    public function createRequest()
    {
        $searchItemRequest = new SearchItemsRequest ();
        $searchItemRequest->PartnerType = "Associates";
        // Put your Partner tag (Store/Tracking id) in place of Partner tag
        $searchItemRequest->PartnerTag = $this->amazonPartnerTag;
        $searchItemRequest->Keywords = "Harry";
        $searchItemRequest->SearchIndex = "All";
        $searchItemRequest->Resources = ["Images.Primary.Small","ItemInfo.Title","Offers.Listings.Price"];
        $host = "webservices.amazon.com";
        $path = "/paapi5/searchitems";
        $payload = \json_encode($searchItemRequest);
        //Put your Access Key in place of <ACCESS_KEY> and Secret Key in place of <SECRET_KEY> in double quotes
        $awsv4 = new AwsV4($this->amazonAccessKey, $this->amazonSecretKey);
        $awsv4->setRegionName("us-east-1");
        $awsv4->setServiceName("ProductAdvertisingAPI");
        $awsv4->setPath ($path);
        $awsv4->setPayload ($payload);
        $awsv4->setRequestMethod ("POST");
        $awsv4->addHeader ('content-encoding', 'amz-1.0');
        $awsv4->addHeader ('content-type', 'application/json; charset=utf-8');
        $awsv4->addHeader ('host', $host);
        $awsv4->addHeader ('x-amz-target', 'com.amazon.paapi5.v1.ProductAdvertisingAPIv1.SearchItems');
        $headers = $awsv4->getHeaders ();
        $headerString = "";
        foreach ( $headers as $key => $value ) {
            $headerString .= $key . ': ' . $value . "\r\n";
        }
        $params = array (
            'http' => array (
                'header' => $headerString,
                'method' => 'POST',
                'content' => $payload
            )
        );
        $stream = stream_context_create ( $params );

        $fp = @fopen ( 'https://'.$host.$path, 'rb', false, $stream );

        if (! $fp) {
            throw new Exception ( "Exception Occured" );
        }
        $response = @stream_get_contents ( $fp );
        if ($response === false) {
            throw new Exception ( "Exception Occured" );
        }
        echo $response;
    }
}
<?php declare(strict_types=1);


namespace Calcifer1337\Tests\AmazonProductAdvertising;


class BasicProductSearchTest extends AbstractTest
{
    /**
     * @test
     */
    public function basicSearch()
    {
        $this->createClient();

        $result = $this->apiClient->search();

        $this->assertNotNull($result);
    }
}
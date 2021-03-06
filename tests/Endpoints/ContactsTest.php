<?php

/*
 * This file is part of the fw4/whise-api library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Whise\Api\Tests\Endpoints;

use Whise\Api\Tests\ApiTestCase;
use Whise\Api\Endpoints\Contacts;
use Whise\Api\Endpoints\ContactsOrigins;
use Whise\Api\Endpoints\ContactsTitles;
use Whise\Api\Endpoints\ContactsTypes;

class ContactsTest extends ApiTestCase
{
    public function testList(): void
    {
        $endpoint = new Contacts(self::$api);
        
        $this->queueResponse('{
            "contacts": [1, 2, 3],
            "totalCount": 3
        }');
        $items = $endpoint->list();
        
        $this->assertCount(3, $items);
    }
    
    public function testUpdate(): void
    {
        $endpoint = new Contacts(self::$api);
        
        $this->queueResponse('{"foo": "bar"}');
        $response = $endpoint->update([]);
        
        $this->assertEquals('bar', $response->foo);
    }
    
    public function testCreate(): void
    {
        $endpoint = new Contacts(self::$api);
        
        $this->queueResponse('{"foo": "bar"}');
        $response = $endpoint->create([]);
        
        $this->assertEquals('bar', $response->foo);
    }
    
    public function testDelete(): void
    {
        $endpoint = new Contacts(self::$api);
        
        $this->queueResponse('{"foo": "bar"}');
        $response = $endpoint->delete(1);
        
        $this->assertEquals('bar', $response->foo);
    }
    
    public function testDeleteMultiple(): void
    {
        $endpoint = new Contacts(self::$api);
        
        $this->queueResponse('{"foo": "bar"}');
        $response = $endpoint->delete([1, 2]);
        
        $this->assertEquals('bar', $response->foo);
    }
    
    public function testOrigins(): void
    {
        $endpoint = new Contacts(self::$api);
        $this->assertTrue($endpoint->origins() instanceof ContactsOrigins);
    }
    
    public function testTitles(): void
    {
        $endpoint = new Contacts(self::$api);
        $this->assertTrue($endpoint->titles() instanceof ContactsTitles);
    }
    
    public function testTypes(): void
    {
        $endpoint = new Contacts(self::$api);
        $this->assertTrue($endpoint->types() instanceof ContactsTypes);
    }
}

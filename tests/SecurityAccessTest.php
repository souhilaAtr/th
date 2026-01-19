<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityAccessTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
       $client->request(  'GET', '/evenement/new');

        $this->assertResponseRedirects('/login');
        // $this->assertSelectorTextContains('h1', 'Hello World');
    }
     public function testAnonymousCannotAccessCategorieNew(): void
    {
        $client = static::createClient();
        $client->request('GET', '/categorie/new');

        $this->assertResponseRedirects('/login');
    }
}

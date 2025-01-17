<?php

namespace App\Tests\WebTests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ShowPublicPasteTest extends WebTestCase
{
    public function testShowForm(): void
    {
        $client = static::createClient();
        static::bootKernel();

        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');
    }

    public function testUnCorrectFillingForm(): void
    {
        $client = static::createClient();
        static::bootKernel();

        $crawler = $client->request('GET', '/');

        $form = $crawler->selectButton('Cooking')->form();
        $client->submit($form);

        $this->assertResponseIsUnprocessable();
        $this->assertSelectorTextContains('div>ul>li', 'This value should not be blank.');
    }

    public function testCorrectFillingForm(): void
    {
        $client = static::createClient();
        static::bootKernel();

        $crawler = $client->request('GET', '/');
        $form = $crawler->selectButton('Cooking')->form();
        $form['paste[title]']->setValue('Paste test title');
        $form['paste[content]']->setValue('Paste test content');

        $client->submit($form);

        $this->assertResponseRedirects();
    }

    public function testSubmitPasteWithoutTitle(): void
    {
        $client = static::createClient();
        static::bootKernel();

        $crawler = $client->request('GET', '/');
        $form = $crawler->selectButton('Cooking')->form();
        $form['paste[content]']->setValue('Paste test content');

        $client->submit($form);

        $this->assertResponseRedirects();
    }
}
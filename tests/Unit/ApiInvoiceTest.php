<?php

namespace Tests\Unit;

use Tests\TestCase;

/**
 * Tests for ApiInvoiceTest Cron Jobs
 *
 * Class spTest
 * @package Test\Services
 */
class ApiInvoiceTest extends TestCase
{

    /**
     * Test generate invoice
     *
     * @param string generate:api_invoice
     *
     * @ApiInvoiceTest boot
     */
    public function testGenerateInvoice()
    {
        $this->artisan('generate:api_invoice');
        $this->assertTrue(true);
    }
}
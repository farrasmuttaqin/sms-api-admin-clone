<?php

namespace Tests\Unit;

use Tests\TestCase;

/**
 * Tests for ApiReportTest Cron Jobs
 *
 * Class spTest
 * @package Test\Services
 */
class ApiReportTest extends TestCase
{

    /**
     * Test generate report
     *
     * @param string generate:api_report
     *
     * @ApiReportTest boot
     */
    public function testGenerateReport()
    {
        $this->artisan('generate:api_report');
        $this->assertTrue(true);
    }
}
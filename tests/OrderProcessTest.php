<?php


namespace Tests;


use LeadGenerator\Generator;
use LeadGenerator\Lead;
use OrderProcessing\OrderProcess;
use PHPUnit\Framework\TestCase;
use Throwable;

class OrderProcessTest extends TestCase
{

    /**
     * @throws Throwable
     */
    public function testProcess()
    {
        $start = time();

        $countOrders = 10000;

        $orders = [];
        (new Generator())->generateLeads($countOrders, function (Lead $lead) use (&$orders) {
            $orders[] = $lead;
        });
        (new OrderProcess($orders))->run();

        $end = round((time() - $start) / 60, 1);
        echo "exec time: $end min.";
        $this->assertTrue($end < 10, 'longer than 10 minutes');
    }
}

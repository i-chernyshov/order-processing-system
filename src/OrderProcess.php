<?php


namespace OrderProcessing;


use Generator;
use LeadGenerator\Lead;
use Recoil\ReferenceKernel\ReferenceKernel;
use Spatie\Async\Pool;
use Throwable;

class OrderProcess
{
    private $chunkedOrders;

    public function __construct(array $orders)
    {
        $this->chunkedOrders = array_chunk($orders, 100);
    }

    /**
     * @throws Throwable
     */
    public function run(): void
    {
        ReferenceKernel::start(function () {
            foreach ($this->chunkedOrders as $chunk) {
                yield $this->orderProcess($chunk);
            }
        });
    }

    private function orderProcess(array $chunk): Generator
    {
        $childPool = Pool::create()->concurrency(100);
        foreach ($chunk as $order) {
            $childPool[] = async(function () use ($order) {
                sleep(2);
                return $order;
            })
                ->then(function (Lead $order) {
                    $this->logOrder($order);
                })
                ->catch(function () use ($order) {
                    $this->logOrder($order);
                })
                ->timeout(function () use ($order) {
                    $this->logOrder($order);
                });
        }
        await($childPool);
        yield;
    }

    private function logOrder(Lead $order): void
    {
        $date = date('d.m.Y H:i:s');
        Logger::log("$order->id | $order->categoryName | $date");
    }
}
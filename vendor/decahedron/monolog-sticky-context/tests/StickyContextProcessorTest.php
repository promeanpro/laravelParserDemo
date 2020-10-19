<?php

use Decahedron\StickyLogging\StickyContext;
use Monolog\Handler\TestHandler;
use Decahedron\StickyLogging\StickyContextProcessor;

class StickyContextProcessorTest extends \PHPUnit\Framework\TestCase
{
    public static function setUpBeforeClass()
    {
        require __DIR__ .'/../vendor/autoload.php';
        parent::setUpBeforeClass();
    }

    public function tearDown()
    {
        StickyContext::flush();
        StickyContext::defaultStack('sticky');
    }

    public function test_it_does_not_add_sticky_data_if_none_is_available()
    {
        $handler = new TestHandler;
        $logger = new Monolog\Logger('sticky', [$handler], [new StickyContextProcessor]);
        $logger->info('some data');

        $this->assertEmpty($handler->getRecords()[0]['extra']);
    }

    public function test_it_adds_all_sticky_data_existing_at_the_given_point_in_time()
    {
        $handler = new TestHandler;
        $logger = new Monolog\Logger('sticky', [$handler], [new StickyContextProcessor]);
        $logger->info('some data');

        $this->assertEmpty($handler->getRecords()[0]['extra']);

        StickyContext::add('user', 1);
        $logger->info('Data with sticky context');

        $this->assertEquals([
            'sticky' => ['user' => 1]
        ], $handler->getRecords()[1]['extra']);
    }

    public function test_the_sticky_context_does_not_lose_data_when_disabled()
    {
        $handler = new TestHandler;
        $logger = new Monolog\Logger('sticky', [$handler], [new StickyContextProcessor]);
        StickyContext::add('user', 1);
        $logger->info('some data');

        $this->assertEquals([
            'sticky' => ['user' => 1]
        ], $handler->getRecords()[0]['extra']);

        StickyContext::disable();
        $logger->info('foo');
        $this->assertEmpty($handler->getRecords()[1]['extra']);
        StickyContext::enable();
        $logger->info('bar');
        $this->assertEquals([
            'sticky' => ['user' => 1]
        ], $handler->getRecords()[2]['extra']);
    }

    public function test_clearing_the_sticky_context_gets_rid_of_all_sticky_data()
    {
        $handler = new TestHandler;
        $logger = new Monolog\Logger('sticky', [$handler], [new StickyContextProcessor]);
        StickyContext::add('user', 1);
        $logger->info('some data');

        $this->assertEquals([
            'sticky' => ['user' => 1]
        ], $handler->getRecords()[0]['extra']);

        StickyContext::flush();
        $logger->info('foo');
        $this->assertEmpty($handler->getRecords()[1]['extra']);
    }

    public function test_it_can_store_context_data_from_multiple_stacks()
    {
        $handler = new TestHandler;
        $logger = new Monolog\Logger('sticky', [$handler], [new StickyContextProcessor]);
        $logger->info('some data');

        $this->assertEmpty($handler->getRecords()[0]['extra']);

        StickyContext::add('user', 1);
        StickyContext::stack('request')->add('id', 'vu4y8k');
        $logger->info('Data with sticky context');

        $this->assertEquals([
            'sticky' => ['user' => 1],
            'request' => ['id' => 'vu4y8k'],
        ], $handler->getRecords()[1]['extra']);
    }
}
<?php

use PHPUnit\Framework\TestCase;

class TicketTest extends TestCase 
{
    public function testTicketProperties()
    {
        // Creating a Ticket instance with dummy values
        $ticket = new Ticket("Tribune A", 50, 100);

        // Assertions to check property values
        $this->assertEquals("Tribune A", $ticket->getTribune());
        $this->assertEquals(50, $ticket->getPrices());
        $this->assertEquals(100, $ticket->getStock());
    }
}

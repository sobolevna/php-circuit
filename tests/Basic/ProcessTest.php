<?php 

namespace Circuit\Tests\Basic;

use Circuit\{Factory, Interfaces, Basic};

class ProcessTest extends \PHPUnit\Framework\TestCase {

    public function testConstruct() {
        $state = new Basic\State();
        $process = new Basic\Process($state);
        $handler = new Basic\Handler(function($state){
            $newState = new Basic\State($state);
            $newState->setDescription('new state');
            return $newState;
        });
        $this->assertEquals($state, $process->getState());
        $continuedProcess = $process->process($handler);
        $this->assertEquals($continuedProcess, $process);
        $this->assertEquals($state, $process->getState()->getPreviousState());
        $this->assertEquals('new state', $process->getState()->getDescription());
        try {
            $state->setProperty('foo', 'bar');
        }
        catch (\Exception $e) {
            $this->assertTrue($e instanceof \Circuit\Exceptions\StateException);
        }
    }

    
}
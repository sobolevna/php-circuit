<?php 

namespace Circuit\Tests\Structured;

use Circuit\{Factory, Interfaces, Structured};

class ProcessTest extends \PHPUnit\Framework\TestCase {

    public function testConstruct() {
        $state = new Structured\State();
        $process = new Structured\Process($state);
        $handler = new Structured\Handler(function($state){
            $newState = new Structured\State($state);
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

    public function testBranch() {
        $state = new Structured\State();
        $process = new Structured\Process($state);
        $handler = new Structured\Handler(function($state){
            $newState = new Structured\State($state);
            $newState->setDescription('new state '.date('c'));
            return $newState;
        });
        $mergeHandler = new Structured\Handler(function($originalState, $stateToMerge){
            $newState = new Structured\State($originalState);
            $newState->setDescription('merged state');
            $newState->setMergedState($stateToMerge);            
            return $newState;
        });
        $process->process($handler);
        $newProcess = $process->branch();
        $this->assertTrue($newProcess->getState() == $process->getState());
        $newProcess->process($handler);
        $process->mergeProcess($mergeHandler, $newProcess);
        $this->assertTrue($newProcess->isFinished());
        $this->assertTrue($process->getState()->getMergedState() === $newProcess->getState());
        $this->assertTrue($process->getState()->isImmutable());
        $this->assertTrue($process->getState()->getMergedState()->isImmutable());
    }
}
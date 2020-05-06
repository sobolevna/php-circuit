<?php 

namespace Circuit\Structured;

use Circuit\{Interfaces, Traits, Exceptions, };

class Handler implements Interfaces\Handler, Interfaces\Descriptable {

    use Traits\HasDescription;

    protected $description = 'Structured handler';

    public function __construct(callable $callback) {
        $this->callback = $callback;
    }

    public function handle(Interfaces\State $state, array $params = []) : Interfaces\State {
        array_unshift($params, $state);
        $ret = \call_user_func_array($this->callback, $params);
        if ($ret->getPreviousState() !== $state) {
            throw new Exceptions\ProcessException('Returned state is disconnected from previous');
        }
        return $ret;
    }
    
}
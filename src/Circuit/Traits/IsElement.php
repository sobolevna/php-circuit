<?php 

namespace Circuit\Traits; 

trait IsElement {
    /**
     * @property Connection[]
     */
    protected $connections = [];

    public function getConnections() : array {
        return $this->connections;
    }

    public function connect(Interfaces\Element $element) : Interfaces\Connection {
        $connection = new Connection([$this, $element]);
        $this->connections[] = $connection;
        return $connection;
    }
}
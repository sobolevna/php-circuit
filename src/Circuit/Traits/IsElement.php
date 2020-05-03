<?php 

namespace Circuit\Traits; 

use Circuit\Interfaces\{Element, Connection};
use Circuit\Exceptions\ElementConnectionException;

trait IsElement {

    /**
     * @var Connection[]
     */
    protected $connections = [];

    /**
     * @return Connection[]
     */
    public function getConnections() : array {
        return $this->connections;
    }

    /**
     * @param Element $element
     * @return Connection
     */
    public function connect(Element $element) : Connection {
        $connection = new $this->connectionClass([$this, $element]);
        return $this->addConnection($connection);
    }

    public function addConnection (Connection $connection) : Connection {
        if (!($connection instanceof $this->connectionClass)) {
            //throw new ElementConnectionException("You are trying to use invalid connection type for this element");
        }
        if (!in_array($this, $connection->getElements(), true)) {
            throw new ElementConnectionException("This connection does not contains this element");
        }
        if (!in_array($connection, $this->connections, true)) {
            $this->connections[] = $connection;
        }
        return $connection;
    }
}
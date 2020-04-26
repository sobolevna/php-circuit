<?php 

namespace Circuit\Interfaces;

interface Element {

    /**
     * @return Connections[]
     */
    public function getConnections() : array;

    /**
     * @param Element $element
     * @return Connection
     */
    public function connect(Element $element) : Connection;

    /**
     * @param Element $element
     * @return Connection
     */
    public function addConnection(Connection $connection) : Connection;
}
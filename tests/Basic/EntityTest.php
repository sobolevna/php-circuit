<?php 

namespace Circuit\Tests\Basic; 

use Circuit\Basic;

/**
 * @coversDefaultClass Circuit\Basic\Entity;
 */
class EntityTest extends \PHPUnit\Framework\TestCase {

    public function setUp() : void {
        $this->core = new Basic\Core();
        $this->limitation = new Basic\Limitation();
        $this->particularity = new Basic\Particularity();
        $this->entity = new Basic\Entity($this->core, $this->limitation, $this->particularity);
    }

    /**
     * @covers Basic\Core::__construct
     * @covers Basic\Limitation::__construct
     * @covers Basic\Particularity::__construct
     * @covers Basic\::__construct
     * @covers ::getCore
     * @covers ::getLimitation
     * @covers ::getParticularity
     */
    public function testConstruct() {
        $this->assertEquals($this->entity->getCore(), $this->core);
        $this->assertEquals($this->entity->getLimitation(), $this->limitation);
        $this->assertEquals($this->entity->getParticularity(), $this->particularity);
    }
    
    /**
     * @covers ::getDescription
     * @covers ::setDescription
     */
    public function testHasDescription() {
        $description = 'New description';
        $this->entity->setDescription($description);
        $this->assertEquals($this->entity->getDescription(), $description);
    }
}
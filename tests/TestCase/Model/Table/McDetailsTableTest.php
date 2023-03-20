<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\McDetailsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\McDetailsTable Test Case
 */
class McDetailsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\McDetailsTable
     */
    public $McDetails;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.McDetails',
        'app.Lists',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('McDetails') ? [] : ['className' => McDetailsTable::class];
        $this->McDetails = TableRegistry::getTableLocator()->get('McDetails', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->McDetails);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}

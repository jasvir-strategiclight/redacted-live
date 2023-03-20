<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\StickersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\StickersTable Test Case
 */
class StickersTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\StickersTable
     */
    public $Stickers;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Stickers',
        'app.Images',
        'app.UserStickers',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Stickers') ? [] : ['className' => StickersTable::class];
        $this->Stickers = TableRegistry::getTableLocator()->get('Stickers', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Stickers);

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

<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UserStickersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\UserStickersTable Test Case
 */
class UserStickersTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\UserStickersTable
     */
    public $UserStickers;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.UserStickers',
        'app.Stickers',
        'app.Users',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('UserStickers') ? [] : ['className' => UserStickersTable::class];
        $this->UserStickers = TableRegistry::getTableLocator()->get('UserStickers', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->UserStickers);

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

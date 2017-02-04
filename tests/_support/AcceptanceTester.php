<?php


/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
*/
class AcceptanceTester extends \Codeception\Actor
{
    use _generated\AcceptanceTesterActions;

   /**
    * Define custom actions here
    */
    /**
     * @Given There is no task named :arg1
     */
    public function thereIsNoTaskNamed($arg1)
    {
//        $this->amOnPage('/task/list');
//        $this->cantSee($arg1);
    }

    /**
     * @When I create a new task named :arg1
     */
    public function iCreateANewTaskNamed($arg1)
    {
//        $this->amOnPage('/task/create');
//        $this->fillField('create_task_form[name]', $arg1);
//        $this->click('create_task_form[submit]');
    }

    /**
     * @Then The task :arg1 should be created
     */
    public function theTaskShouldBeCreated($arg1)
    {
//        $this->amOnPage('/task/list');
//        $this->see($arg1);
    }

    /**
     * @Then The status of task :arg1 should be :arg2
     */
    public function theStatusOfTaskShouldBe($arg1, $arg2)
    {
//        $this->amOnPage('/task/list');
//        $this->see($arg1 . ' (' . $arg2 . ')');
    }

    /**
     * @Given There is a task named :arg1
     */
    public function thereIsATaskNamed($arg1)
    {
//        $this->amOnPage('/task/list');
//        $this->see($arg1);
    }

    /**
     * @Then The new task :arg1 should not be created
     */
    public function theNewTaskShouldNotBeCreated($arg1)
    {
//        $this->see($arg1 . " is already existed");
    }

}

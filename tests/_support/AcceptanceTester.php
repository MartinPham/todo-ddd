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
        throw new \Codeception\Exception\Incomplete("Step `There is no task named :arg1` is not defined");
    }

    /**
     * @When I create a new task named :arg1
     */
    public function iCreateANewTaskNamed($arg1)
    {
        throw new \Codeception\Exception\Incomplete("Step `I create a new task named :arg1` is not defined");
    }

    /**
     * @Then The task :arg1 should be created
     */
    public function theTaskShouldBeCreated($arg1)
    {
        throw new \Codeception\Exception\Incomplete("Step `The task :arg1 should be created` is not defined");
    }

    /**
     * @Then The status of task :arg1 should be :arg2
     */
    public function theStatusOfTaskShouldBe($arg1, $arg2)
    {
        throw new \Codeception\Exception\Incomplete("Step `The status of task :arg1 should be :arg2` is not defined");
    }

    /**
     * @Given There is a task named :arg1
     */
    public function thereIsATaskNamed($arg1)
    {
        throw new \Codeception\Exception\Incomplete("Step `There is a task named :arg1` is not defined");
    }

    /**
     * @Then The new task :arg1 should not be created
     */
    public function theNewTaskShouldNotBeCreated($arg1)
    {
        throw new \Codeception\Exception\Incomplete("Step `The new task :arg1 should not be created` is not defined");
    }

}

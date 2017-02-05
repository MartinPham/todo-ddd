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
     * @Given There is no task named :arg1
     */
    public function thereIsNoTaskNamed($arg1)
    {
        $this->amOnPage('/task/list');
        $this->dontSee($arg1);
    }

    /**
     * @When I create a new task named :arg1
     */
    public function iCreateANewTaskNamed($arg1)
    {
        $this->amOnPage('/task/list');
        $this->click('Create new');
        $this->fillField('create_task_form[name]', $arg1);
        $this->click('create_task_form[submit]');
    }

    /**
     * @Then The task :arg1 should be created
     */
    public function theTaskShouldBeCreated($arg1)
    {
        $this->see($arg1);
    }

    /**
     * @Then The status of task :arg1 should be :arg2
     */
    public function theStatusOfTaskShouldBe($arg1, $arg2)
    {
        $this->see($arg1 . ' (' . $arg2 . ')');
    }

    /**
     * @Given There is a task named :arg1
     */
    public function thereIsATaskNamed($arg1)
    {
        $this->amOnPage('/task/list');
        $this->see($arg1);
    }

    /**
     * @Then The new task :arg1 should not be created
     */
    public function theNewTaskShouldNotBeCreated($arg1)
    {
        $this->see($arg1 . " is already existed");
    }

    /**
     * @Given There are tasks:
     */
    public function thereAreTasks(\Behat\Gherkin\Node\TableNode $tableNode)
    {
        $this->amOnPage('/task/list');
        foreach ($tableNode as $row) {
            $name = $row['name'];
            $status = $row['status'];

            $this->see($name . ' (' . $status . ')');
        }
    }

    /**
     * @When I list tasks
     */
    public function iListTasks()
    {
        $this->amOnPage('/task/list');
    }

    /**
     * @Then I should see remaining tasks:
     */
    public function iShouldSeeRemainingTasks(\Behat\Gherkin\Node\TableNode $tableNode)
    {
        foreach ($tableNode as $row) {
            $name = $row['name'];

            $this->see($name . ' (remaining)');
        }
    }

    /**
     * @Then I should see completed tasks:
     */
    public function iShouldSeeCompletedTasks(\Behat\Gherkin\Node\TableNode $tableNode)
    {
        foreach ($tableNode as $row) {
            $name = $row['name'];

            $this->see($name . ' (completed)');
        }
    }

    /**
     * @Given There is a task named :arg1 with status :arg2
     */
    public function thereIsATaskNamedWithStatus($arg1, $arg2)
    {
        $this->amOnPage('/task/list');
        $this->see($arg1 . ' (' . $arg2 . ')');
    }

    /**
     * @When I modify task :arg1 with name :arg2 and status :arg3
     */
    public function iModifyTaskWithNameAndStatus($arg1, $arg2, $arg3)
    {
        $this->click("a[data-edit='" . $arg1 . "']");
        $this->fillField('update_task_form[name]', $arg2);
        $this->selectOption('update_task_form[status]', $arg3);
        $this->click('update_task_form[submit]');
    }


    /**
     * @Then The task :arg1 should have name :arg2 and status :arg3
     */
    public function theTaskShouldHaveNameAndStatus($arg1, $arg2, $arg3)
    {
        $this->dontSee($arg1);
        $this->see($arg2 . ' (' . $arg3 . ')');
    }


    /**
     * @When I modify task :arg1 with status :arg2
     */
    public function iModifyTaskWithStatus($arg1, $arg2)
    {
        if ($arg2 === 'remaining') {
            $this->click("a[data-redo='" . $arg1 . "']");
        } else if ($arg2 === 'completed') {
            $this->click("a[data-done='" . $arg1 . "']");
        } else {
            throw new \Exception('Invalid status');
        }

    }

    /**
     * @Then The task :arg1 should have status :arg2
     */
    public function theTaskShouldHaveStatus($arg1, $arg2)
    {
        $this->see($arg1 . ' (' . $arg2 . ')');
    }

    /**
     * @When I remove task :arg1
     */
    public function iRemoveTask($arg1)
    {
        $this->click("a[data-remove='" . $arg1 . "']");
    }

    /**
     * @Then The task :arg1 should be deleted
     */
    public function theTaskShouldBeDeleted($arg1)
    {
        $this->dontSee($arg1);
    }


    /**
     * @When I cleanup completed tasks
     */
    public function iCleanupCompletedTasks()
    {
        $this->click('Clean');
    }

    /**
     * @Then The completed tasks should be removed:
     */
    public function theCompletedTasksShouldBeRemoved(\Behat\Gherkin\Node\TableNode $tableNode)
    {
        foreach ($tableNode as $row) {
            $name = $row['name'];

            $this->dontSee($name);
        }
    }

}

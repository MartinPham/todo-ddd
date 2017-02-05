Feature: Removing task
  In order to remove a task
  As an user
  I can remove the task I created

  Scenario: Removing task
    Given There is a task named "Buying salt" with status "remaining"
    When I remove task "Buying salt"
    Then The task "Buying sugar" should be deleted

  Scenario: Cleanup completed task
    Given There are tasks:
      | name              | status    |
      | Buying salt       | remaining |
      | Buying milk       | remaining |
      | Go to supermarket | completed |
    When I cleanup completed tasks
    Then The completed tasks should be removed:
      | name              |
      | Go to supermarket |
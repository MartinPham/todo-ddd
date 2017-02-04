Feature: CreatingTask
  In order to create a new task
  As an user
  I can create new task with name

  Scenario: Create new task
    Given There is no task named "buying sugar"
    When I create a new task named "buying sugar"
    Then The task "buying sugar" should be created
    And The status of task "buying sugar" should be "remaining"

  Scenario: Create new task with existed name
    Given There is a task named "buying sugar"
    When I create a new task named "buying sugar"
    Then The new task "buying sugar" should not be created

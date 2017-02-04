Feature: CreatingTask
  In order to create a new task
  As an user
  I can create new task with name

  Scenario: Create new task
    Given There is no task named "Buying sugar"
    When I create a new task named "Buying sugar"
    Then The task "Buying sugar" should be created
    And The status of task "Buying sugar" should be "remaing"

  Scenario: Create new task with existed name
    Given There is a task named "Buying sugar"
    When I create a new task named "Buying sugar"
    Then The new task "Buying sugar" should not be created

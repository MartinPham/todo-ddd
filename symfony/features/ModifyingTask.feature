Feature: Modifying task
  In order to modify task detail
  As an user
  I can edit the task I created

  Scenario: Editing task
    Given There is a task named "Buying salt" with status "remaining"
    When I modify task "Buying salt" with name "Buying salt and pepper" and status "completed"
    Then The task "Buying sugar" should have name "Buying salt and pepper" and status "completed"

  Scenario: Complete task status
    Given There is a task named "Buying salt" with status "remaining"
    When I modify task "Buying salt" with status "completed"
    Then The task "Buying salt" should have status "completed"

  Scenario: Redo task status
    Given There is a task named "Go to supermarket" with status "completed"
    When I modify task "Go to supermarket" with status "remaining"
    Then The task "Go to supermarket" should have status "remaining"
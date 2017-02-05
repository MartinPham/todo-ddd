Feature: Listing task
  In order to see list of tasks
  As an user
  I can see tasks I created

  Scenario: Listing remaining and completed task
    Given There are tasks:
      | name              | status    |
      | Buying salt       | remaining |
      | Buying milk       | remaining |
      | Go to supermarket | completed |
    When I list tasks
    Then I should see remaining tasks:
      | name        |
      | Buying salt |
      | Buying milk |
    And I should see completed tasks:
      | name              |
      | Go to supermarket |
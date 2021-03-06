todo
====

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/MartinPham/todo-ddd/badges/quality-score.png?bd=2017-02-04-12-46&b=master)](https://scrutinizer-ci.com/g/MartinPham/todo-ddd/?branch=master)

[![Build Status](https://scrutinizer-ci.com/g/MartinPham/todo-ddd/badges/build.png?bd=2017-02-04-12-46&b=master)](https://scrutinizer-ci.com/g/MartinPham/todo-ddd/build-status/master)

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/0e23630d-1f08-495d-b8f5-d039e76b8bf8/big.png)](https://insight.sensiolabs.com/projects/0e23630d-1f08-495d-b8f5-d039e76b8bf8)

###Description
todo is an application which allow people can add new task, see all the tasks added, modify existing tasks, mark tasks done/remaing, or remove tasks. Every task has name and status (completed/not-completed). Task should be unique.

###Usecase
- List/Read
	- See all remaning tasks
	- See all completed tasks
- Create/Update/Delete
	- Add new task
	- Mark task as completed/remaning
	- Edit existing task (name and status)
	- Remove existing task
	- Clean all completed tasks

###Story
```
Feature: Creating task
  In order to create a new task
  As an user
  I can create new task with name

  Scenario: Create new task
    Given There is no task named "Buying sugar"
    When I create a new task named "Buying sugar"
    Then The task "Buying sugar" should be created
    And The status of task "Buying sugar" should be "remaining"

  Scenario: Create new task with existed name
    Given There is a task named "Buying salt"
    When I create a new task named "Buying salt"
    Then The new task "Buying salt" should not be created
```

```
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
```

```
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
```

```
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
```

###Domain
- Task
	- id
	- name
	- status (completed/remaning)
	- createdAt
	- updatedAt
- Factory
	- TaskFactory
		- > createFromName
- Exception
	- TaskNameIsEmptyException
	- TaskNameIsAlreadyExistedException
	- TaskIsNotFoundException
- Specification
	- TaskNameIsNotEmptySpecification
		- > isSatisfiedBy 
	- TaskNameIsUniqueSpecification
		- > isSatisfiedBy 
- Repository
	- TaskRepositoryInterface
 		- > findAll
 		- > find
 		- > findAllByStatus
 		- > findByName
		- > save
		- > remove
 		
###Application
- Task
	- Exception
		- TaskCannotBeSavedException  
		- TaskCannotBeRemovedException  
	- Query
	    - > getTaskById
		- > getAllRemainingTasks
		- > getAllCompletedTasks
	- Command
		- > addNewTask
		- > completeTask
		- > redoTask
		- > editTask
		- > removeTask
		- > cleanAllCompletedTasks

###Infrastructure
- Persistence
	- DoctrineORM
		- Repository  
			- TaskRepository 
				- > findAll
	 			- > find
	 			- > findAllByStatus
				- > save
				- > remove

	- Eloquent
		- Repository
			- TaskRepository
				- > findAll
	 			- > find
	 			- > findAllByStatus
				- > save
				- > remove


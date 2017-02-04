todo
====

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
      And The status of task "Buying sugar" should be "remaing"
      
  Scenario: Create new task with existed name
    Given There is a task named "Buying sugar"
     When I create a new task named "Buying sugar"
     Then The new task "Buying sugar" should not be created
```

```
Feature: Listing task
  In order to see list of tasks
  As an user
  I can see tasks I created

  Scenario: Listing remaining and completed task
    Given There are tasks
      | name				| status	|
	  | Buying sugar		| remaining	|
	  | Buying milk			| remaining	|
	  | Go back to Italy	| completed	|
	 When I list tasks
     Then I should see remaining tasks
      | name				
	  | Buying sugar	|
	  | Buying milk		|
	  And I should see completed tasks
      | name				
	  | Go back to Italy	|
```

```
Feature: Modifying task
  In order to modify task detail
  As an user
  I can edit the task I created

  Scenario: Editing task
    Given There is a task named "Buying sugar" with status "remaining"
	 When I modify task with name "Buying sugar and salt" and status "completed"
     Then the task "Buying sugar" should have name "Buying sugar and salt" and status "completed"

  Scenario: Complete task status
    Given There is a task named "Buying sugar" with status "remaining"
	 When I modify task with status "completed"
     Then the task "Buying sugar" should have status "completed"
     
  Scenario: Redo task status
    Given There is a task named "Buying sugar" with status "completed"
	 When I modify task with status "remaining"
     Then the task "Buying sugar" should have status "remaining"
```

```
Feature: Removing task
  In order to remove a task
  As an user
  I can remove the task I created

  Scenario: Removing task
    Given There is a task named "Buying sugar" with status "remaining"
	 When I remove task "Buying sugar"
     Then the task "Buying sugar" should be deleted
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

###Web
- Frontend
	- DefaultController
	- TaskController
		- > listAction
		- > createAction
		- > updateStatusAction
		- > updateAction
		- > deleteAction 
		- > cleanAction

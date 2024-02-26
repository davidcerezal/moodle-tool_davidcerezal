@tool @tool_davidcerezal @javascript
Feature: Creating, editing and deleting entries
  In order to manage entries
  As a teacher
  I need to be able to add, edit and delete entries

  Background:
    Given the following "courses" exist:
      | shortname | fullname |
      | C1        | Course 1 |
    And the following "users" exist:
      | username | firstname | lastname | email |
      | teacher1 | Theo | Teacher | teacher1@example.com |
    And the following "course enrolments" exist:
      | user | course | role |
      | teacher1 | C1 | editingteacher |      

  Scenario: create an entry
    When I am on the "C1" "Course" page logged in as "admin"
    And I navigate to "Awesome plugin" in current page administration
    And I click on "Editar" "link"
    And I set the following fields to these values:
      | Name      | test entry 1 |
      | Completed | 0            |
    And I press "Save changes"
    Then I should see "David Admin plugin's  table"
    And the following should exist in the "tool_davidcerezal_table" table:
      | Name         | Completed |
      | test entry 1 | No        |
    And I log out

  Scenario: edit an entry
    When I am on the "C1" "Course" page logged in as "admin"
    And I navigate to "Awesome plugin" in current page administration
    And I click on "Editar" "link"
    And I set the following fields to these values:
      | Name      | test entry 1 |
      | Completed | 0            |
    And I press "Save changes"
    And I click on "Editar" "link" in the "test entry 1" "table_row"
    And I set the following fields to these values:
      | Completed | 1            |
    And I press "Save changes"
    Then I should see "David Admin plugin's  table"
    And the following should exist in the "tool_davidcerezal_table" table:
      | Name         | Completed |
      | test entry 1 | Yes       |
    And I log out

  Scenario: delete an entry
    When I am on the "C1" "Course" page logged in as "admin"
    And I navigate to "Awesome plugin" in current page administration
    And I click on "Editar" "link"
    And I set the following fields to these values:
      | Name      | test entry 1 |
      | Completed | 0            |
    And I press "Save changes"
    And I click on "Eliminar" "link" in the "test entry 1" "table_row"
    And I click on "Delete" "button" in the ".modal-dialog .modal-footer" "css_element"
    Then I should see "There are no shared resources to display at this time."
    And the following should exist in the "tool_davidcerezal_table" table:
      | Name         | Completed |
    And I log out

  Scenario: delete cancel not delete an entry
    When I am on the "C1" "Course" page logged in as "admin"
    And I navigate to "Awesome plugin" in current page administration
    And I click on "Editar" "link"
    And I set the following fields to these values:
      | Name      | test entry 1 |
      | Completed | 0            |
    And I press "Save changes"
    And I click on "Eliminar" "link" in the "test entry 1" "table_row"
    And I click on "Cancel" "button" in the ".modal-dialog .modal-footer" "css_element"
    Then I should see "David Admin plugin's  table"
    And the following should exist in the "tool_davidcerezal_table" table:
      | Name         | Completed |
      | test entry 1 | No       |
    And I log out

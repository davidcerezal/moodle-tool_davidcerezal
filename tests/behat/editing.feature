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

  Scenario: create an entry
    When I am on the "C1" "Course" page logged in as "admin"
    And I navigate to "David Cerezal Demo" in current page administration
    And I click on "Editar row" "link" 
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
    And I navigate to "David Cerezal Demo" in current page administration
    And I click on "Editar row" "link" 
    And I set the following fields to these values:
      | Name      | test entry 1 |
      | Completed | 0            |
    And I press "Save changes"
    And I click on "Editar row" "link" in the "test entry 1" "table_row"
    And I set the following fields to these values:
      | Completed | 1            |
    And I press "Save changes"
    Then I should see "David Admin plugin's  table"
    And the following should exist in the "tool_davidcerezal_table" table:
      | Name         | Completed |
      | test entry 1 | Yes       |
    And I log out    
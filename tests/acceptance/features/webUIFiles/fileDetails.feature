@webUI @insulated @disablePreviews
Feature: User can open the details panel for any file or folder
  As a user
  I want to be able to open the details panel of any file or folder
  So that the details of the file or folder are visible to me

  Background:
    Given user "user1" has been created
    And user "user1" has logged in using the webUI
    And the user has browsed to the files page

  Scenario: View different areas of the details panel in files page
    When the user opens the file action menu of the file "lorem.txt" in the webUI
    And the user clicks the details file action in the webUI
    Then the details dialog should be visible in the webUI
    And the thumbnail should be visible in the details panel
    When the user switches to "sharing" tab in details panel using the webUI
    Then the "sharing" details panel should be visible
    When the user switches to "comments" tab in details panel using the webUI
    Then the "comments" details panel should be visible
    When the user switches to "versions" tab in details panel using the webUI
    Then the "versions" details panel should be visible

  Scenario: View different areas of the details panel in favorites page
    When the user marks the file "lorem.txt" as favorite using the webUI
    And the user browses to the favorites page
    And the user opens the file action menu of the file "lorem.txt" in the webUI
    And the user clicks the details file action in the webUI
    Then the details dialog should be visible in the webUI
    And the thumbnail should be visible in the details panel
    When the user switches to "sharing" tab in details panel using the webUI
    Then the "sharing" details panel should be visible
    When the user switches to "comments" tab in details panel using the webUI
    Then the "comments" details panel should be visible
    When the user switches to "versions" tab in details panel using the webUI
    Then the "versions" details panel should be visible

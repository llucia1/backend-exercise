Feature: Search books

  Scenario: Search returns results
    When I request GET "/api/v1/books/search/Test"
    Then the response status code should be 200
    And the response should be valid JSON

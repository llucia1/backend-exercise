Feature: Get book by id

  Scenario: Get an existing book
    When I request GET "/api/v1/books/1"
    Then the response status code should be 200
    And the response should be valid JSON
    And the JSON should contain key "id"
    And the JSON should contain key "title"
    And the JSON should contain key "subjects"
    And the JSON should contain key "authors"

  Scenario: Get a non-existing book
    When I request GET "/api/v1/books/9999"
    Then the response status code should be 404
    And the response should be valid JSON
    And the JSON should contain key "error"

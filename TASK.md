Technical Test
==============

Introduction
------------

The technical test is your opportunity to showcase skills and demonstrate familiarity with PHP
features, object oriented programming and good coding practices.

The problem we ask you to solve is described in Gherkin language in the section “Acceptance
Criteria”. It should give you a bit of a feeling of how we work, since this is how we describe features in
our projects.

The task
--------

You should implement a simple command line utility which will load an order from an XML file and
update its total.
New total should be calculated considering the promotional offer, chosen based on an option passed
in to the command. The path to an XML file should be passed in as an argument.

An  example  XML  file  is  given:

```xml
    <?xml version="1.0" encoding="UTF-8"?>
    <order>
        <products>
            <product title="Rimmel Lasting Finish Lipstick 4g" price="4.99">
                <category>Lipstick</category>
            </product>
            <product title="Sebamed Anti-Dandruff Shampoo 200ml" price="4.99">
                <category>Shampoo</category>
            </product>
        </products>
        <total>9.98</total>
    </order>
```

Acceptance criteria
-------------------

    Feature: Applying promotional offers to an order
      In order to research best marketing strategies
      As a Marketing Person
      I need to test applying promotional offers on orders

    Scenario: The cheapest product is given for free with "3 for the price of 2" offer
      Given the "3 for the price of 2" offer is enabled
      When the following products are put on the order
        | Category | Title                                      | Price |
        | Lipstick | Rimmel Lasting Finish Lipstick 4g          |  4.99 |
        | Lipstick | bareMinerals Marvelous Moxie Lipstick 3.5g | 13.95 |
        | Lipstick | Rimmel Kate Lasting Finish Matte Lipstick  |  5.49 |
      Then I should get the "Rimmel Lasting Finish Lipstick 4g" for free
      And the order total should be "19.44"

    Scenario: "3 for the price of 2" is disabled
      Given the "3 for the price of 2" offer is disabled
      When the following products are put on the order
        | Category | Title                                      | Price |
        | Lipstick | Rimmel Lasting Finish Lipstick 4g          |  4.99 |
        | Lipstick | bareMinerals Marvelous Moxie Lipstick 3.5g | 13.95 |
        | Lipstick | Rimmel Kate Lasting Finish Matte Lipstick  |  5.49 |
      Then I should not get anything for free
      And the order total should be "24.43"

    Scenario: "Buy Shampoo & get Conditioner for 50% off" offer
      Given the "Buy Shampoo & get Conditioner for 50% off" offer is enabled
      When the following products are put on the order
        | Category     | Title                                  | Price |
        | Shampoo      | Sebamed Anti-Dandruff Shampoo 200ml    |  4.99 |
        | Conditioner  | L'Oréal Paris Hair Conditioner 250ml   |  5.50 |
      Then I should get a 50% discount on "L'Oréal Paris Hair Conditioner 250ml"
      And the order total should be "7.74"

Feature: Set up plugin settings
    In order for a the site to generate a feed
    As a website administrator
    I need to be able to access the plugin settings

Scenario: I want to log in
    Given I go to "/wp-login.php"
    And I fill in "log" with "testadmin"
    And I fill in "pwd" with "testadmin"
	When I press "Log In"
	Then the url should match "/wp-admin/"
    And I should see "Dashboard"
    When I click the element with CSS selector ".toplevel_page_woocommerce"
    And I should see "Orders"
    When I follow "Settings"
    Then I should see "General Options"
    When I follow "Product Feeds"
    And I should see "Notes about Google"

    # Check that the field visibility works.
 #    When I check "woocommerce_gpf_config[product_fields][availability]"
 #    Then I should see the "div.woocommerce_gpf_config_availability" element
 #    And I should see the "div.woocommerce_gpf_config_availability select.woocommerce-gpf-store-default" element
 #    When I uncheck "woocommerce_gpf_config[product_fields][availability]"
 #    Then I should not see the "div.woocommerce_gpf_config_availability" element

 #    When I check "woocommerce_gpf_config[product_fields][availability_date]"
 #    Then I should see the "div.woocommerce_gpf_config_availability_date" element
 #    And I should not see the "div.woocommerce_gpf_config_availability_date select.woocommerce-gpf-store-default" element
 #    When I uncheck "woocommerce_gpf_config[product_fields][availability_date]"
 #    Then I should not see the "div.woocommerce_gpf_config_availability_date" element

 #    When I check "woocommerce_gpf_config[product_fields][condition]"
 #    Then I should see the "div.woocommerce_gpf_config_condition" element
 #    And I should see the "div.woocommerce_gpf_config_condition select.woocommerce-gpf-store-default" element
 #    When I uncheck "woocommerce_gpf_config[product_fields][condition]"
 #    Then I should not see the "div.woocommerce_gpf_config_condition" element

 #    When I check "woocommerce_gpf_config[product_fields][brand]"
 #    Then I should see the "div.woocommerce_gpf_config_brand" element
 #    And I should see the "div.woocommerce_gpf_config_brand input.woocommerce-gpf-store-default" element
 #    And I should see the "div.woocommerce_gpf_config_brand select.woocommerce-gpf-prepopulate" element
 #    When I uncheck "woocommerce_gpf_config[product_fields][brand]"
 #    Then I should not see the "div.woocommerce_gpf_config_brand" element

 #    When I check "woocommerce_gpf_config[product_fields][mpn]"
 #    Then I should see the "div.woocommerce_gpf_config_mpn" element
 #    And I should not see the "div.woocommerce_gpf_config_availability_date select.woocommerce-gpf-store-default" element
 #    And I should see the "div.woocommerce_gpf_config_mpn select.woocommerce-gpf-prepopulate" element
 #    When I uncheck "woocommerce_gpf_config[product_fields][mpn]"
 #    Then I should not see the "div.woocommerce_gpf_config_mpn" element

 #    When I check "woocommerce_gpf_config[product_fields][product_type]"
 #    Then I should see the "div.woocommerce_gpf_config_product_type" element
 #    And I should see the "div.woocommerce_gpf_config_product_type input.woocommerce-gpf-store-default" element
 #    And I should see the "div.woocommerce_gpf_config_product_type select.woocommerce-gpf-prepopulate" element
 #    When I uncheck "woocommerce_gpf_config[product_fields][product_type]"
 #    Then I should not see the "div.woocommerce_gpf_config_product_type" element

 #    When I check "woocommerce_gpf_config[product_fields][google_product_category]"
 #    Then I should see the "div.woocommerce_gpf_config_google_product_category" element
	# And I should see the "div.woocommerce_gpf_config_google_product_category input.woocommerce-gpf-store-default" element
 #    When I uncheck "woocommerce_gpf_config[product_fields][google_product_category]"
 #    Then I should not see the "div.woocommerce_gpf_config_google_product_category" element

 #    When I check "woocommerce_gpf_config[product_fields][gtin]"
 #    Then I should see the "div.woocommerce_gpf_config_gtin" element
 #    And I should see the "div.woocommerce_gpf_config_gtin select.woocommerce-gpf-prepopulate" element
 #    When I uncheck "woocommerce_gpf_config[product_fields][gtin]"
 #    Then I should not see the "div.woocommerce_gpf_config_gtin" element

 #    When I check "woocommerce_gpf_config[product_fields][gender]"
 #    Then I should see the "div.woocommerce_gpf_config_gender" element
 #    And I should see the "div.woocommerce_gpf_config_gender select.woocommerce-gpf-store-default" element
 #    When I uncheck "woocommerce_gpf_config[product_fields][gender]"
 #    Then I should not see the "div.woocommerce_gpf_config_gender" element

 #    When I check "woocommerce_gpf_config[product_fields][age_group]"
 #    Then I should see the "div.woocommerce_gpf_config_age_group" element
 #    And I should see the "div.woocommerce_gpf_config_age_group select.woocommerce-gpf-store-default" element
 #    When I uncheck "woocommerce_gpf_config[product_fields][age_group]"
 #    Then I should not see the "div.woocommerce_gpf_config_age_group" element

 #    When I check "woocommerce_gpf_config[product_fields][color]"
 #    Then I should see the "div.woocommerce_gpf_config_color" element
 #    And I should see the "div.woocommerce_gpf_config_color select.woocommerce-gpf-prepopulate" element
 #    When I uncheck "woocommerce_gpf_config[product_fields][color]"
 #    Then I should not see the "div.woocommerce_gpf_config_color" element

 #    When I check "woocommerce_gpf_config[product_fields][size]"
 #    Then I should see the "div.woocommerce_gpf_config_size" element
 #    When I uncheck "woocommerce_gpf_config[product_fields][size]"
 #    Then I should not see the "div.woocommerce_gpf_config_size" element

 #    When I check "woocommerce_gpf_config[product_fields][size_type]"
 #    Then I should see the "div.woocommerce_gpf_config_size_type" element
 #    And I should see the "div.woocommerce_gpf_config_size_type select.woocommerce-gpf-store-default" element
 #    When I uncheck "woocommerce_gpf_config[product_fields][size_type]"
 #    Then I should not see the "div.woocommerce_gpf_config_size_type" element

 #    When I check "woocommerce_gpf_config[product_fields][size_system]"
 #    Then I should see the "div.woocommerce_gpf_config_size_system" element
 #    And I should see the "div.woocommerce_gpf_config_size_system select.woocommerce-gpf-store-default" element
 #    When I uncheck "woocommerce_gpf_config[product_fields][size_system]"
 #    Then I should not see the "div.woocommerce_gpf_config_size_system" element

 #    When I check "woocommerce_gpf_config[product_fields][identifier_exists]"
 #    Then I should see the "div.woocommerce_gpf_config_identifier_exists" element
 #    And I should see the "div.woocommerce_gpf_config_identifier_exists select.woocommerce-gpf-store-default" element
 #    When I uncheck "woocommerce_gpf_config[product_fields][identifier_exists]"
 #    Then I should not see the "div.woocommerce_gpf_config_identifier_exists" element

 #    When I check "woocommerce_gpf_config[product_fields][adwords_grouping]"
 #    Then I should see the "div.woocommerce_gpf_config_adwords_grouping" element
 #    And I should see the "div.woocommerce_gpf_config_adwords_grouping input.woocommerce-gpf-store-default" element
 #    When I uncheck "woocommerce_gpf_config[product_fields][adwords_grouping]"
 #    Then I should not see the "div.woocommerce_gpf_config_adwords_grouping" element

 #    When I check "woocommerce_gpf_config[product_fields][adwords_labels]"
 #    Then I should see the "div.woocommerce_gpf_config_adwords_labels" element
 #    And I should see the "div.woocommerce_gpf_config_adwords_labels input.woocommerce-gpf-store-default" element
 #    And I should see the "div.woocommerce_gpf_config_adwords_labels select.woocommerce-gpf-prepopulate" element
 #    When I uncheck "woocommerce_gpf_config[product_fields][adwords_labels]"
 #    Then I should not see the "div.woocommerce_gpf_config_adwords_labels" element

 #    When I check "woocommerce_gpf_config[product_fields][bing_category]"
 #    Then I should see the "div.woocommerce_gpf_config_bing_category" element
 #     And I should see the "div.woocommerce_gpf_config_bing_category input.woocommerce-gpf-store-default" element
 #    When I uncheck "woocommerce_gpf_config[product_fields][bing_category]"
 #    Then I should not see the "div.woocommerce_gpf_config_bing_category" element

 #    When I check "woocommerce_gpf_config[product_fields][upc]"
 #    Then I should see the "div.woocommerce_gpf_config_upc" element
 #    And I should see the "div.woocommerce_gpf_config_upc select.woocommerce-gpf-prepopulate" element
 #    When I uncheck "woocommerce_gpf_config[product_fields][upc]"
 #    Then I should not see the "div.woocommerce_gpf_config_upc" element

 #    When I check "woocommerce_gpf_config[product_fields][isbn]"
 #    Then I should see the "div.woocommerce_gpf_config_isbn" element
 #    And I should see the "div.woocommerce_gpf_config_isbn select.woocommerce-gpf-prepopulate" element
 #    When I uncheck "woocommerce_gpf_config[product_fields][isbn]"
 #    Then I should not see the "div.woocommerce_gpf_config_isbn" element

 #    When I check "woocommerce_gpf_config[product_fields][delivery_label]"
 #    Then I should see the "div.woocommerce_gpf_config_delivery_label" element
 #    And I should see the "div.woocommerce_gpf_config_delivery_label input.woocommerce-gpf-store-default" element
 #    When I uncheck "woocommerce_gpf_config[product_fields][delivery_label]"
 #    Then I should not see the "div.woocommerce_gpf_config_delivery_label" element

 #    When I check "woocommerce_gpf_config[product_fields][custom_label_0]"
 #    Then I should see the "div.woocommerce_gpf_config_custom_label_0" element
 #    And I should see the "div.woocommerce_gpf_config_custom_label_0 input.woocommerce-gpf-store-default" element
 #    And I should see the "div.woocommerce_gpf_config_custom_label_0 select.woocommerce-gpf-prepopulate" element
 #    When I uncheck "woocommerce_gpf_config[product_fields][custom_label_0]"
 #    Then I should not see the "div.woocommerce_gpf_config_custom_label_0" element

 #    When I check "woocommerce_gpf_config[product_fields][custom_label_1]"
 #    Then I should see the "div.woocommerce_gpf_config_custom_label_1" element
 #    And I should see the "div.woocommerce_gpf_config_custom_label_1 input.woocommerce-gpf-store-default" element
 #    And I should see the "div.woocommerce_gpf_config_custom_label_1 select.woocommerce-gpf-prepopulate" element
 #    When I uncheck "woocommerce_gpf_config[product_fields][custom_label_1]"
 #    Then I should not see the "div.woocommerce_gpf_config_custom_label_1" element

 #    When I check "woocommerce_gpf_config[product_fields][custom_label_2]"
 #    Then I should see the "div.woocommerce_gpf_config_custom_label_2" element
 #    And I should see the "div.woocommerce_gpf_config_custom_label_2 input.woocommerce-gpf-store-default" element
 #    And I should see the "div.woocommerce_gpf_config_custom_label_2 select.woocommerce-gpf-prepopulate" element
 #    When I uncheck "woocommerce_gpf_config[product_fields][custom_label_2]"
 #    Then I should not see the "div.woocommerce_gpf_config_custom_label_2" element

 #    When I check "woocommerce_gpf_config[product_fields][custom_label_3]"
 #    Then I should see the "div.woocommerce_gpf_config_custom_label_3" element
 #    And I should see the "div.woocommerce_gpf_config_custom_label_3 input.woocommerce-gpf-store-default" element
 #    And I should see the "div.woocommerce_gpf_config_custom_label_3 select.woocommerce-gpf-prepopulate" element
 #    When I uncheck "woocommerce_gpf_config[product_fields][custom_label_3]"
 #    Then I should not see the "div.woocommerce_gpf_config_custom_label_3" element

 #    When I check "woocommerce_gpf_config[product_fields][custom_label_4]"
 #    Then I should see the "div.woocommerce_gpf_config_custom_label_4" element
 #    And I should see the "div.woocommerce_gpf_config_custom_label_4 input.woocommerce-gpf-store-default" element
 #    And I should see the "div.woocommerce_gpf_config_custom_label_4 select.woocommerce-gpf-prepopulate" element
 #    When I uncheck "woocommerce_gpf_config[product_fields][custom_label_4]"
 #    Then I should not see the "div.woocommerce_gpf_config_custom_label_4" element

 #    When I check "woocommerce_gpf_config[product_fields][promotion_id]"
 #    Then I should see the "div.woocommerce_gpf_config_promotion_id" element
 #    And I should see the "div.woocommerce_gpf_config_promotion_id input.woocommerce-gpf-store-default" element
 #    And I should see the "div.woocommerce_gpf_config_promotion_id select.woocommerce-gpf-prepopulate" element
 #    When I uncheck "woocommerce_gpf_config[product_fields][promotion_id]"
 #    Then I should not see the "div.woocommerce_gpf_config_promotion_id" element

    # Enable all of the fields
    When I check "woocommerce_gpf_config[product_fields][availability]"
    And I check "woocommerce_gpf_config[product_fields][availability_date]"
    And I check "woocommerce_gpf_config[product_fields][condition]"
    And I check "woocommerce_gpf_config[product_fields][brand]"
    And I check "woocommerce_gpf_config[product_fields][mpn]"
    And I check "woocommerce_gpf_config[product_fields][product_type]"
    And I check "woocommerce_gpf_config[product_fields][google_product_category]"
    And I check "woocommerce_gpf_config[product_fields][gtin]"
    # And I check "woocommerce_gpf_config[product_fields][gender]"
    # And I check "woocommerce_gpf_config[product_fields][age_group]"
    # And I check "woocommerce_gpf_config[product_fields][color]"
    # And I check "woocommerce_gpf_config[product_fields][size]"
    # And I check "woocommerce_gpf_config[product_fields][size_type]"
    # And I check "woocommerce_gpf_config[product_fields][size_system]"
    # And I check "woocommerce_gpf_config[product_fields][identifier_exists]"
    # And I check "woocommerce_gpf_config[product_fields][adwords_grouping]"
    # And I check "woocommerce_gpf_config[product_fields][adwords_labels]"
    # And I check "woocommerce_gpf_config[product_fields][bing_category]"
    # And I check "woocommerce_gpf_config[product_fields][upc]"
    # And I check "woocommerce_gpf_config[product_fields][isbn]"
    # And I check "woocommerce_gpf_config[product_fields][delivery_label]"
    # And I check "woocommerce_gpf_config[product_fields][custom_label_0]"
    # And I check "woocommerce_gpf_config[product_fields][custom_label_1]"
    # And I check "woocommerce_gpf_config[product_fields][custom_label_2]"
    # And I check "woocommerce_gpf_config[product_fields][custom_label_3]"
    # And I check "woocommerce_gpf_config[product_fields][custom_label_4]"
    # And I check "woocommerce_gpf_config[product_fields][promotion_id]"

    # Enter some store-wide defaults
 	When I select "in stock" from "_woocommerce_gpf_data[availability]"

 	And I select "new" from "_woocommerce_gpf_data[condition]"

 	And I fill in "_woocommerce_gpf_data[brand]" with "Test Brand"
 	And I select "tax:product_cat" from "_woocommerce_gpf_prepopulate[brand]"

 	And I select "field:sku" from "_woocommerce_gpf_prepopulate[mpn]"

 	And I fill in "_woocommerce_gpf_data[product_type]" with "Electronics > Audio"
 	And I select "tax:product_cat" from "_woocommerce_gpf_prepopulate[product_type]"

 	And I fill in "_woocommerce_gpf_data[google_product_category]" with "Electronics > Audio"

 	And I select "field:sku" from "_woocommerce_gpf_prepopulate[gtin]"

 	And I press "Save changes"
 	Then I should see "Your settings have been saved."
# ###########################################################################
# replaces:
#   test_foo_bar    -> testFooBar
#   test__foo__bar  -> testFooBar
#   prov_foo_bar    -> provFooBar
#   prov__foo__bar  -> provFooBar
# ###########################################################################
/\(test\|prov\)__\?/s/_\+\([A-Za-z]\)/\u\1/g

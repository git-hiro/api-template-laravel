<?php

// https://github.com/junstyle/vscode-php-cs-fixer
// https://github.com/FriendsOfPHP/PHP-CS-Fixer

return PhpCsFixer\Config::create()
  ->setRules([
    '@PSR2'       => true,
    '@PhpCsFixer' => true,

    'array_indentation'      => true,
    'array_syntax'           => ['syntax' => 'short'],

    'binary_operator_spaces' => [
      'align_double_arrow' => true,
      'align_equals'       => false,
    ],

    'blank_line_after_opening_tag' => true,
    'cast_spaces'                  => ['space' => 'none'],
    'class_keyword_remove'         => false,
    'combine_consecutive_issets'   => true,
    'combine_consecutive_unsets'   => true,
    'compact_nullable_typehint'    => true,
    'concat_space'                 => ['spacing' => 'one'],

    'switch_case_space' => false,

    'multiline_whitespace_before_semicolons' => [
      'strategy' => 'no_multi_line',
    ],

    'single_quote' => true,

    // 'declare_equal_normalize' => true,
    // 'function_typehint_space' => true,
    // 'hash_to_slash_comment'   => true,
    // 'include'                 => true,
    // 'lowercase_cast'          => true,
    // 'native_function_casing' => true,
    // 'new_with_braces' => true,
    // 'no_blank_lines_after_class_opening' => true,
    // 'no_blank_lines_after_phpdoc' => true,
    // 'no_empty_comment' => true,
    // 'no_empty_phpdoc' => true,
    // 'no_empty_statement' => true,
    // 'no_extra_consecutive_blank_lines' => [
    //   'curly_brace_block',
    //   'extra',
    //   'parenthesis_brace_block',
    //   'square_brace_block',
    //   'throw',
    //   'use',
    // ],
    // 'no_leading_import_slash' => true,
    // 'no_leading_namespace_whitespace' => true,
    // 'no_mixed_echo_print' => array('use' => 'echo'),
    // 'no_multiline_whitespace_around_double_arrow' => true,
    // 'no_short_bool_cast' => true,
    // 'no_singleline_whitespace_before_semicolons' => true,
    // 'no_spaces_around_offset' => true,
    // 'no_trailing_comma_in_list_call' => true,
    // 'no_trailing_comma_in_singleline_array' => true,
    // 'no_unneeded_control_parentheses' => true,
    // 'no_unused_imports' => true,
    // 'no_whitespace_before_comma_in_array' => true,
    // 'no_whitespace_in_blank_line'         => true,
    // 'normalize_index_brace' => true,
    // 'object_operator_without_whitespace' => true,
    // 'php_unit_fqcn_annotation' => true,
    // 'phpdoc_align' => true,
    // 'phpdoc_annotation_without_dot' => true,
    // 'phpdoc_indent' => true,
    // 'phpdoc_inline_tag' => true,
    // 'phpdoc_no_access' => true,
    // 'phpdoc_no_alias_tag' => true,
    // 'phpdoc_no_empty_return' => true,
    // 'phpdoc_no_package' => true,
    // 'phpdoc_no_useless_inheritdoc' => true,
    // 'phpdoc_return_self_reference' => true,
    // 'phpdoc_scalar' => true,
    // 'phpdoc_separation' => true,
    // 'phpdoc_single_line_var_spacing' => true,
    // 'phpdoc_summary' => true,
    // 'phpdoc_to_comment' => true,
    // 'phpdoc_trim' => true,
    // 'phpdoc_types' => true,
    // 'phpdoc_var_without_name' => true,
    // 'pre_increment' => true,
    // 'return_type_declaration' => true,
    // 'self_accessor' => true,
    // 'short_scalar_cast' => true,
    // 'single_blank_line_before_namespace' => true,
    // 'single_class_element_per_statement' => true,
    // 'space_after_semicolon' => true,
    // 'standardize_not_equals' => true,
    // 'ternary_operator_spaces' => true,
    // 'trailing_comma_in_multiline_array' => true,
    // 'trim_array_spaces'               => true,
    // 'unary_operator_spaces'           => true,
    // 'whitespace_after_comma_in_array' => true,
  ])
  ->setIndent('  ')
  ->setLineEnding("\n")
  ->setFinder(
  PhpCsFixer\Finder::create()
    ->exclude('vendor')
    ->in(__DIR__)
  );

<?php

return [
    'title'                       => 'Margin calculating',
    'error_title'                 => 'Oops!',
    'not_found'                   => 'Entity not found.',
    'select_manufacturer_title'   => 'Manufacturer',
    'select_carat_range_title'    => 'Carat range',
    'select_manufacturer_default' => 'Master',
    'select_carat_range_default'  => 'All',

    'default_empty_values'      => 'You don\'t have any default margin values',
    'add_margin'                => 'Add margin table',
    'margin_save_success'       => 'Margin was successfully saved.',
    'sync_with_default'         => 'Sync margins with master',
    'margins_are_syncing'       => 'Syncing...',
    'carat_range_already_exist' => 'This carat range already exist',
    'margin_carat_ranges'       => 'Carat ranges',
    'manufacturer_not_exist'    => 'Manufacturer does not found',
    'margins_sync_success'      => 'Margins were successfully synced',
    'carat_min_placeholder'     => 'Min',
    'carat_max_placeholder'     => 'Max',
    'margin_shapes'             => [
        'round' => 'Round',
        'other' => 'Fancy',
    ],

    'edit'         => 'Edit',
    'cancel'       => 'Cancel',
    'accept'       => 'Accept',
    'save_changes' => 'Save changes',

    'validation' => [
        'carat_min'   => [
            'invalid' => 'Carat min value is invalid'
        ],
        'carat_max'   => [
            'invalid' => 'Carat max value is invalid'
        ],
        'carat_range' => [
            'invalid' => 'Carat min value cannot be greater or equal than carat max value.'
        ],
        'margin'      => [
            'missing_margin_key'  => 'Cannot find key in margin saving object. Try one more time or call to developer.',
            'invalid_margin_type' => 'Key in margin saving object does not equal to allow type. Try one more time or call to developer.',
        ],
    ],
];
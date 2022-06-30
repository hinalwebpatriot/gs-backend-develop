<?php

return [
    'error'       => [
        'not_found' => 'Not found',
    ],
    'locales'     => [
        'en'  => 'English',
        'ru'  => 'Russian',
        'zh'  => 'Cantonese Chinese',
        'cmn' => 'Mandarin Chinese',
    ],
    'catalog'     => [
        'carat'       => 'Ct.',
        'shape'       => 'Shape',
        'diamond'     => 'Diamond',
        'certificate' => 'GIA',
        'delete'      => 'Diamond delete',
    ],
    'locations'   => [
        'selected'    => 'Data locations selected',
    ],
    'favorites'   => [
        'error'      => [
            'product_not_found'      => 'This item was not found in favorites list',
            'max_items'              => 'You can add maximum 6 items per category',
            'invalid_request_params' => 'Invalid request params',
            'share_list_empty'       => "Can't share empty favorites list",
            'max_items_not_added'    => "Can't add some of items because of limit - maximum 6 items per category",
        ],
        'added_ok'   => 'Added to favorites',
        'removed_ok' => 'Removed from favorites',
        'share_email_successfully_sent' => 'Email with share link was successfully sent',
    ],
    'compares'    => [
        'error'      => [
            'product_not_found'      => 'This item was not found in compare list',
            'max_items'              => 'You can add maximum 6 items per category',
            'invalid_request_params' => 'Invalid request params',
            'max_items_not_added'    => "Can't add some of items because of limit - maximum 6 items per category",
            'share_list_empty'       => "Can't share empty compares list",
        ],
        'email'   => 'Email not send',
        'added_ok'   => 'Added to compare',
        'removed_ok' => 'Removed from compare',
    ],
    'cart'        => [
        'error'        => [
            'item_not_found'     => 'Item not found',
            'item_already_added' => 'Item is already in cart',
        ],
        'item_added'   => 'Item added to cart',
        'item_removed' => 'Item removed from cart',
    ],
    'order'        => [
        'error'        => [
            'cart_is_empty'     => 'Cart is empty',
        ],
        'added'   => 'Order added cart update',
        'added_cart'   => 'Order added cart add item',
    ],
    'reviews'     => [
        'error' => [
            'max_photos_upload' => 'You can upload :count photos max',
        ],
    ],
    'constructor' => [
        'products_match'               => 'ok',
        'products_not_match'           => 'Diamond does not fit to the ring',
        'complete_ring_created'        => 'Complete ring was created',
        'complete_ring_non_exist'      => 'Diamond or ring you have chosen already does not exist',
        'complete_ring_already_exists' => 'You\'ve already created this ring',
        'complete_ring_deleted'        => 'Complete ring removed from your list',
        'complete_ring_updated'        => 'Complete ring updated',
        'complete_ring_bad_request'    => 'This ring cannot be updated',
        'added_to_cart'                => 'Successfully added to cart',
    ],
    'mail' => [
        'confirm-code-subject' => 'Promo-code confirmation',
        'common-confirm-code-subject' => 'Confirmation code',
        'default' => [
            'success_send' => 'Mail was successfully sent'
        ],
        'share' => [
            'favorites' => 'favorites',
            'compares' => 'compares',
            'subject'=> 'Share :share_list_type list',
            'text' => 'I want to share with you my :share_list_type list'
        ],
        'product_hint' => [
            'subject' => 'Someone shared a product link to you',
            'recipient' => 'Dear :recipient_name,',
            'sender' => 'From :sender_name'
        ],
        'share_complete_rings' => [
            'subject' => 'Shared complete rings',
            'text' => 'Someone shared a rings list to you. Check the link below'
        ],
        'invoice' => [
            'subject' => 'Thank you for placing your order with GS Diamonds!',
            'attach_hint' => '
                <p>Bank Wire Transfer</p>
                <p>Your bank will need the following information to complete the bank wire:</p>
                <p>Bank: National Australia Bank</p>
                <p>Bank Branch address: 85-95 Marrickville Rd, Marrickville Sydney, NSW 2204, Australia</p>
                <p>Beneficiary name: GS DIamonds PTY LTD</p>
                <p>Beneficiary address: Level 6, Suite 602-603, 276 Pitt St. Sydney, NSW 2000, Australia</p>
                <p>BANK DETAILS<br>
                    GS DIAMONDS PTY LTD<br>
                    NAB<br>
                    BSB 082-356<br>
                    ACC 36-852-6502
                </p>
                <br>
                <p>Your payment invoice is in attachment.</p>',
            'attach_hint_file' => '
                <p>Bank Wire Transfer</p>
                <p>Your bank will need the following information to complete the bank wire:</p>
                <p>Bank: National Australia Bank</p>
                <p>Bank Branch address: 85-95 Marrickville Rd, Marrickville Sydney, NSW 2204, Australia</p>
                <p>Beneficiary name: GS DIamonds PTY LTD</p>
                <p>Beneficiary address: Level 6, Suite 602-603, 276 Pitt St. Sydney, NSW 2000, Australia</p>
                <p>BANK DETAILS<br>
                    GS DIAMONDS PTY LTD<br>
                    NAB<br>
                    BSB 082-356<br>
                    ACC 36-852-6502
                </p>',
            'transfer_description' => '
                <p>Bank Transfer Payment</p>
                <p>Bank Wire Transfer</p>
                <p>Your bank will need the following information to complete the bank wire:</p>',
            'credentials_bank' => 'Bank',
            'credentials_bank_branch_address' => 'Bank Branch address',
            'credentials_beneficiary_name' => 'Beneficiary name',
            'credentials_beneficiary_address' => 'Beneficiary address',
            'credentials_account_number' => 'Account number',
            'credentials_bsb' => 'BSB',
            'credentials_swift_code' => 'SWIFT code',
        ],
        'order_base' => [
            'subject' => 'Thank you for placing your order with GS Diamonds!',
            'top_text' => '
                <p>Thank you for placing your order with GS Diamonds!</p>
                <p>No single diamond is the same as another which is whyone of our specialists is currently checking availability
                    and performing an inspection on your diamond to ensure that you receive diamond that you are after.</p>
                <p>Once our diamond specialists have completed the inspection of the diamond, you will receive an email notification
                updating you in the status of your order.</p>',
            'bottom_text' => ' <p>Thank you,<br>
                    GS Diamond Support Team</p>
                <p style="font-weight: bold">CONTACT US</p>
                <p>
                    Shop 34-36, Level 2, <br>
                    Queen Victoria Building, <br>
                    Queen Victoria Building, Sydney, <br>
                    2000, NSW
                </p>
                <p>
                    Email: <a href="orders@gsdiamonds.com.au">orders@gsdiamonds.com.au</a><br>
                    Phone: 1300 181 294
                </p>'
        ],
        'order_details' => [
            'order_details' => 'Order Details',
            'your_order' => 'Your order #:id',
            'products' => 'Product(s)',
            'items' => 'Items',
            'quantity' => 'Qty',
            'price' => 'Price',
            'sku' => 'SKU: :sku',
            'subtotal' => 'Subtotal',
            'shipping_total' => 'Shipping and Hadling',
            'grand1' => 'Grand Total (Excl. Tax)',
            'tax' => 'GST (10%)',
            'grand2' => 'Grand Total (Incl. Tax)',
            'shipping' => 'Shipping details',
            'shipping_instructions' => 'Shipping Instructions',
            'shipping_free' => 'Free shipping - free',
            'billing' => 'Billing Details',
            'payment' => 'Payment details',
            'payment_name' => 'Payment via :paysytem_name',
            'shipping_showroom' => 'Ship to showroom',
            'invoice-alias' => 'Your invoice #:alias',
            'promocode' => 'Promo-code',
            'discount_percent' => 'Discount (:value%)',
        ],
        'payment_link' => 'Click here to proceed payment',
        'order_payment' => [
            'subject' => 'Order payment details',
            'top_text' => 'Here are payment details to complete your order'
        ],
        'order_payment_complete' => [
            'subject' => 'Thank You! Order has been paid successfully',
            'text' => 'Once your order has shipped, we\'ll send you an email with shipping information.',

        ]
    ],
    'complete_share' => [
        'items_not_found' => 'You have no complete rings to share'
    ],
    'currency_format' => [
        'AUD' => 'A$ :sum',
        'USD' => '$ :sum',
        'NZD' => 'NZ$ :sum',
        'HKD' => 'HK$ :sum',
        'CNY' => '¥ :sum',
        'EUR' => '€ :sum',
    ],
    'paypal' => [
        'execute_failed' => 'Payment failed',
        'order_not_found' => 'Payment went successfully, but order can\'t be found. Please, contact administrators for details',
        'execute_success' => 'Payment complete',
        'order_token_not_found' => 'Order with this payment token was not found'
    ],
    'alipay' => [
        'execute_failed' => 'Payment failed',
        'order_not_found' => 'Payment went successfully, but order can\'t be found. Please, contact administrators for details',
        'execute_success' => 'Payment complete',
        'order_token_not_found' => 'Order with this payment token was not found',
        'token_failed' => 'Invalidate payment process data'
    ],
    'adyen' => [
        'execute_failed' => 'Payment failed',
        'execute_success' => 'Payment complete',
        'order_not_found' => 'Order can\'t be found. Please, contact administrators for details',
        'error_message' => 'Transaction is under processing, please contact sales team for further information',
        'error_message2' => 'Payment failed, probably you have mentioned wrong data',
    ],
    'order-already-payed' => 'Order is already payed!',
    'order-not-found' => 'Order not found!',
    'sold-out' => 'SOLD',
    'submit-pay' => 'Pay',
    'prepare-form-fields-error' => 'Sorry! Unspecified failure. Please try again',
    'invoice-not-available' => 'The invoice is not available!',
    'order-invoice-created' => 'The order successfully created!',
    'promocode-is-not-validate' => 'Promocode is not correct!',
    'promocode-confirmation-wrong' => 'Wrong confirmation code',
    'confirm-promocode' => 'We have sent confirmation code on your e-mail',
    'promocode-applied' => 'Promocode successfully applied',
    'promocode-already-applied' => 'Promocode is already applied! Please, complete the checkout!',
    'estimate-delivery-time' => 'Estimated delivery time: :period :unit',
    'cart-delivery-period' => 'in :period business :unit by',
    'weeks' => 'weeks',
    'code-invalid' => 'Confirmation code is invalid',
    'promo-code-registration-send' => 'We have sent confirmation code on your e-mail',
    'email-successfully-added' => 'E-mail successfully registered!',
    'email' => 'E-mail',
    'first-name' => 'First Name',
    'last-name' => 'Last Name',
    'send-code-expire-comment' => 'You can send new confirmation code every :value minutes',
];

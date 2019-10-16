<?php

return [
    'property_path' => 'last_stage',
    'transitions' => [
        'enquiry_reopen' => [
            'accept' => [
                'enquiry_sales_approval' => [
                    'rolename' => '*',
                    'extra' => [],
                    'callbacks' => [
                        'pre' => [],
                        'post' => ['Notification\SalesApproval'],
                    ],
                    'validators' => [],
                ]
            ],
            'reject' => [
                'enquiry_close' => [
                    'rolename' => '*',
                    'extra' => [],
                    'callbacks' => [
                        'pre' => [],
                        'post' => ['Notification\SalesReject'],
                    ],
                    'validators' => [],
                ],
            ],
        ],
        'enquiry_open' => [
            'accept' => [
                'enquiry_sales_approval' => [
                    'rolename' => '*',
                    'extra' => [],
                    'callbacks' => [
                        'pre' => [],
                        'post' => ['Notification\SalesApproval'],
                    ],
                    'validators' => [],
                ]
            ],
            'reject' => [
                'enquiry_close' => [
                    'rolename' => '*',
                    'extra' => [],
                    'callbacks' => [
                        'pre' => [],
                        'post' => ['Notification\SalesReject'],
                    ],
                    'validators' => [],
                ],
            ],
        ],
        'enquiry_sales_approval' => [
            'accept' => [
                'enquiry_engg_approval' => [
                    'rolename' => '*',
                    'validations' => [
                        'need_products' => true
                    ],
                    'extra' => [],
                    'callbacks' => [
                        'pre' => [],
                        'post' => ['Notification\EnggApproval'],
                    ],
                    'validators' => [],
                ],
                'enquiry_quotation_ready' => [
                    'rolename' => '*',
                    'validations' => [
                        'need_products' => false
                    ],
                    'extra' => [],
                    'callbacks' => [
                        'pre' => [],
                        'post' => ['Notification\QuotationReady'],
                    ],
                    'validators' => [],
                ],
            ],
            'reject' => [
                'enquiry_sales_approval' => [
                    'rolename' => 'SM',
                    'extra' => [],
                    'callbacks' => [
                        'pre' => [],
                        'post' => ['Notification\SalesApproval'],
                    ],
                    'validators' => [],
                ],
                'enquiry_close' => [
                    'rolename' => 'MD',
                    'extra' => [],
                    'callbacks' => [
                        'pre' => [],
                        'post' => ['Notification\EnquiryClosed'],
                    ],
                    'validators' => [],
                ],
            ]
        ],
        'enquiry_engg_approval' => [
            'accept' => [
                'enquiry_engg_waiting' => [
                    'rolename' => '*',
                    'extra' => [],
                    'callbacks' => [
                        'pre' => [],
                        'post' => [],
                    ],
                    'validators' => ['Notification\EnggApproval'],
                ],
            ],
            'reject' => [
                'enquiry_engg_approval' => [
                    'rolename' => 'EM',
                    'extra' => [],
                    'callbacks' => [
                        'pre' => [],
                        'post' => ['Notification\EnggApproval'],
                    ],
                    'validators' => [],
                ],
                'enquiry_close' => [
                    'rolename' => 'MD',
                    'extra' => [],
                    'callbacks' => [
                        'pre' => [],
                        'post' => ['Notification\EnquiryClosed'],
                    ],
                    'validators' => [],
                ],
            ]
        ],
        'enquiry_engg_waiting' => [
            'acknowledge' => [
                'enquiry_engg_inprocess' => [
                    'rolename' => '*',
                    'extra' => [],
                    'callbacks' => [
                        'pre' => [],
                        'post' => ['Notification\EnggAcknowledge'],
                    ],
                    'validators' => [],
                ],
            ],
            'accept' => [
                'enquiry_quotation_ready' => [
                    'rolename' => '*',
                    'extra' => [],
                    'callbacks' => [
                        'pre' => [],
                        'post' => ['Notification\QuotationReady'],
                    ],
                    'validators' => [],
                ],
            ]
        ],
    ],
    'stages' => [
        'enquiry_reopen' => [
            'text' => 'Re-Open',
            'extra' => []
        ],
        'enquiry_open' => [
            'text' => 'Open',
            'extra' => []
        ],
        'enquiry_sales_approval' => [
            'text' => 'Sales: Approval Waiting',
            'extra' => []
        ],
        'enquiry_engg_approval' => [
            'text' => 'Engg: Approval Waiting',
            'extra' => []
        ],
        'enquiry_engg_waiting' => [
            'text' => 'Engg: Acknowledge Waiting',
            'extra' => []
        ],
        'enquiry_engg_inprocess' => [
            'text' => 'Engg: Inprocess',
            'extra' => []
        ],
        'enquiry_quotation_ready' => [
            'text' => 'Quotation Ready',
            'extra' => []
        ],
        'enquiry_close' => [
            'text' => 'Close',
            'extra' => []
        ],
    ]
];
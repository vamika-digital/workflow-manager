<?php

return [
	'property_path' => 'last_stage',
	'transitions' => [
		'product_revision' => [
			'accept' => [
				'product_approval' => [
					'rolename' => '*',
					'extra' => [],
					'callbacks' => [
						'pre' => [],
						'post' => ['Notification\ProductApproval'],
					],
					'validators' => [],
				],
			],
		],
		'product_open' => [
			'accept' => [
				'product_approval' => [
					'rolename' => '*',
					'extra' => [],
					'callbacks' => [
						'pre' => [],
						'post' => ['Notification\ProductApproval'],
					],
					'validators' => [],
				],
			],
		],
		'product_approval' => [
			'accept' => [
				'product_approval' => [
					'rolename' => 'EE',
					'extra' => [],
					'callbacks' => [
						'pre' => [],
						'post' => ['Notification\ProductApproval'],
					],
					'validators' => [],
				],
				'product_approval' => [
					'rolename' => 'EM',
					'extra' => [],
					'callbacks' => [
						'pre' => [],
						'post' => ['Notification\ProductApproval'],
					],
					'validators' => [],
				],
				'product_ready' => [
					'rolename' => 'MD',
					'extra' => [],
					'callbacks' => [
						'pre' => [],
						'post' => ['Notification\ProductReady'],
					],
					'validators' => [],
				],
			],
			'reject' => [
				'product_revision' => [
					'rolename' => 'MD',
					'extra' => [],
					'callbacks' => [
						'pre' => [],
						'post' => ['Notification\ProductRevision'],
					],
					'validators' => [],
				],
				'product_revision' => [
					'rolename' => 'EM',
					'extra' => [],
					'callbacks' => [
						'pre' => [],
						'post' => ['Notification\ProductRevision'],
					],
					'validators' => [],
				],
			],
		],
	],
	'stages' => [
		'product_revision' => [
			'text' => 'Product Revision',
			'extra' => [],
		],
		'product_open' => [
			'text' => 'Product Open',
			'extra' => [],
		],
		'product_approval' => [
			'text' => 'Product Approval',
			'extra' => [],
		],
		'product_ready' => [
			'text' => 'Product Ready',
			'extra' => [],
		],
	],
];
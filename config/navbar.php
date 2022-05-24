<?php

return [
	'main' => [
		[
			'title' => 'dashboard.title',
			'icon_class' => 'fa fa-chart-line',
			'route' => 'dashboard',
			'extra_routes' => 'home'
		],
		[
			'title' => 'blogs.title-m',
			'icon_class' => 'fa fa-edit',
			'extra_routes' => 'blogs.search',
			'items' => [
				[
					'title' => 'blogs.add',
					'route' => 'blogs.add',
				],
				[
					'title' => 'blogs.list',
					'route' => 'blogs.list',
				],
			],
		],
		[
			'title' => 'products.title-m',
			'icon_class' => 'fas fa-boxes',
			'items' => [
				[
					'title' => 'products.list',
					'route' => 'products.list',
				],
				[
					'title' => 'products.import',
					'route' => 'products.import',
				],
			],
		],
		[
			'title' => 'categories.title-m',
			'icon_class' => 'fas fa-boxes',
			'items' => [
				[
					'title' => 'categories.add',
					'route' => 'categories.add',
				],
				[
					'title' => 'categories.list',
					'route' => 'categories.list',
				],
			],
		],
		[
			'title' => 'filters.title-m',
			'icon_class' => 'fas fa-filter',
			'items' => [
				[
					'title' => 'filters.add',
					'route' => 'filters.add',
				],
				[
					'title' => 'filters.list',
					'route' => 'filters.list',
				],
			],
		],
		[
			'title' => 'banners.title-m',
			'icon_class' => 'fa fa-images',
			'items' => [
				[
					'title' => 'banners.add',
					'route' => 'banners.add',
				],
				[
					'title' => 'banners.list',
					'route' => 'banners.list',
				],
			],
		],
		[
			'title' => 'delivery-prices.title-m',
			'icon_class' => 'fa fa-images',
			'route' => 'delivery-prices.list',

		],
		[
			'title' => 'discounts.title-m',
			'icon_class' => 'fa fa-images',
			'items' => [
				[
					'title' => 'discounts.add',
					'route' => 'discounts.add',
				],
				[
					'title' => 'discounts.add-coupons',
					'route' => 'discounts.add-coupons',
				],
				[
					'title' => 'discounts.list',
					'route' => 'discounts.list',
				],
			],
		],
		[
			'title' => 'notifications.title-m',
			'icon_class' => 'fa fa-broadcast-tower',
			'items' => [
				[
					'title' => 'notifications.add',
					'route' => 'notifications.add',
				],
				[
					'title' => 'notifications.list',
					'route' => 'notifications.list',
				],
			],
		],
		[
			'title' => 'sms-messages.title-m',
			'icon_class' => 'fa fa-comments',
			'extra_routes' => ['sms-messages.search', 'sms-messages.incoming.search'],
			'items' => [
				[
					'title' => 'sms-messages.add',
					'route' => 'sms-messages.add',
				],
				[
					'title' => 'sms-messages.list',
					'route' => 'sms-messages.list',
				],
				[
					'title' => 'sms-messages.incoming.title-m',
					'route' => 'sms-messages.incoming.list',
				],
			],
		],
		[
			'title' => 'users.title-m',
			'icon_class' => 'fa fa-users',
			'items' => [
				[
					'title' => 'users.add',
					'route' => 'users.add',
				],
				[
					'title' => 'users.list',
					'route' => 'users.list',
				],
			],
		],
		[
			'title' => 'customers.title-m',
			'icon_class' => 'fa fa-users',
			'route' => 'customers.list',

		],
		[
			'title' => 'cheese-club.title-m',
			'icon_class' => 'fas fa-cheese',
			'items' => [
				[
					'title' => 'cheese-club.add',
					'route' => 'cheese-club.add',
				],
				[
					'title' => 'cheese-club.list',
					'route' => 'cheese-club.list',
				],
				[
					'title' => 'cheese-club.import',
					'route' => 'cheese-club.import',
				],
			],
		],
		[
			'title' => 'orders.title-m',
			'icon_class' => 'fa fa-users',
			'route' => 'orders.list',

		],
		[
			'title' => 'reviews.title-m',
			'icon_class' => 'fa fa-users',
			'route' => 'reviews.list',

		],
		[
			'title' => 'roles.title-m',
			'icon_class' => 'fa fa-ban',
			'items' => [
				[
					'title' => 'roles.add',
					'route' => 'roles.add',
				],
				[
					'title' => 'roles.list',
					'route' => 'roles.list',
				],
			],
		],
		[
			'title' => 'monitors.title-m',
			'icon_class' => 'fa fa-clock',
			'items' => [
				[
					'title' => 'monitors.add',
					'route' => 'monitors.add'
				],
				[
					'title' => 'monitors.list',
					'route' => 'monitors.list'
				]
			]
		],
		[
			'title' => 'navbar.administration',
			'icon_class' => 'fa fa-cogs',
			'items' => [
				[
					'title' => 'settings.general.title',
					'route' => 'settings.general.edit',
				],
				[
					'title' => 'storage.title',
					'route' => 'storage.list',
				],
				[
					'title' => 'db.title',
					'route' => 'db.list',
				],
				[
					'title' => 'navbar.telescope',
					'route' => 'telescope-auth',
					'target' => '_blank',
				],
				[
					'title' => 'navbar.horizon',
					'route' => 'horizon-auth',
					'target' => '_blank',
				],
				[
					'title' => 'tech-info.title',
					'route' => 'tech-info',
				],
			],
		],
	],
	'header' => [
		/*[
			'title' => 'navbar.pages',
			'icon_class' => 'fa fa-cogs',
			'pages' => [
				[
					'title' => 'users.title-m',
					'icon_class' => 'fa fa-users',
					'items' => [
						[
							'title' => 'users.add',
							'route' => 'users.add',
						],
						[
							'title' => 'users.list',
							'route' => 'users.list',
						],
					],
				],
			]
		]*/
	]
];

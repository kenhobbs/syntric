<?php
	
	function syntric_block_categories( $categories, $post ) {
		return array_merge(
			$categories,
			[
				[
					'slug'  => 'syntric-blocks',
					'title' => __( 'Syntric', 'syntric' ),
					'icon'  => 'lightbulb',
				],
			]
		);
	}
	
	add_filter( 'block_categories', 'syntric_block_categories', 10, 2 );
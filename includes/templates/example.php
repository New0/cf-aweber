<?php
/**
 * Example form for Aweber for Caldera Forms
 *
 * @package   cf_aweber
 * @author    Josh Pollock for CalderaWP LLC (email : Josh@CalderaWP.com)
 * @license   GPL-2.0+
 * @link
 * @copyright 2015 Josh Pollock for CalderaWP LLC
 */
return array(
	'_last_updated' => 'Mon, 06 Jun 2016 21:28:12 +0000',
	'ID' => 'aweber',
	'cf_version' => '1.3.5.3',
	'name' => 'Aweber',
	'description' => '',
	'db_support' => 1,
	'pinned' => 1,
	'hide_form' => 1,
	'check_honey' => 1,
	'success' => 'Form has been successfully submitted. Thank you.',
	'avatar_field' => '',
	'form_ajax' => 1,
	'custom_callback' => '',
	'layout_grid' =>
		array(
			'fields' =>
				array(
					'fld_1859436' => '1:1',
					'fld_4215532' => '1:2',
					'fld_3053848' => '2:1',
					'fld_9240534' => '3:1',
					'fld_8585108' => '3:2',
				),
			'structure' => '6:6|12|6:6',
		),
	'fields' =>
		array(
			'fld_4215532' =>
				array(
					'ID' => 'fld_4215532',
					'type' => 'email',
					'label' => 'Email',
					'slug' => 'email',
					'conditions' =>
						array(
							'type' => '',
						),
					'required' => 1,
					'caption' => '',
					'config' =>
						array(
							'custom_class' => '',
							'placeholder' => '',
							'default' => '',
						),
				),
			'fld_8585108' =>
				array(
					'ID' => 'fld_8585108',
					'type' => 'button',
					'label' => 'Contact Us',
					'slug' => 'contact_us',
					'conditions' =>
						array(
							'type' => '',
						),
					'caption' => '',
					'config' =>
						array(
							'custom_class' => '',
							'type' => 'submit',
							'class' => 'btn btn-default',
							'target' => '',
						),
				),
			'fld_1859436' =>
				array(
					'ID' => 'fld_1859436',
					'type' => 'text',
					'label' => 'Name',
					'slug' => 'name',
					'conditions' =>
						array(
							'type' => '',
						),
					'required' => 1,
					'caption' => '',
					'config' =>
						array(
							'custom_class' => '',
							'placeholder' => '',
							'default' => '',
							'mask' => '',
							'type_override' => 'text',
						),
				),
			'fld_9240534' =>
				array(
					'ID' => 'fld_9240534',
					'type' => 'checkbox',
					'label' => 'Join Mailing List?',
					'slug' => 'join_mailing_list',
					'conditions' =>
						array(
							'type' => '',
						),
					'caption' => '',
					'config' =>
						array(
							'custom_class' => '',
							'auto_type' => '',
							'taxonomy' => 'category',
							'post_type' => 'post',
							'value_field' => 'name',
							'orderby_tax' => 'count',
							'orderby_post' => 'ID',
							'order' => 'ASC',
							'default' => 'opt1479969',
							'option' =>
								array(
									'opt1479969' =>
										array(
											'value' => '',
											'label' => 'Yes',
										),
								),
						),
				),
			'fld_3053848' =>
				array(
					'ID' => 'fld_3053848',
					'type' => 'paragraph',
					'label' => 'Message',
					'slug' => 'message',
					'conditions' =>
						array(
							'type' => '',
						),
					'caption' => '',
					'config' =>
						array(
							'custom_class' => '',
							'placeholder' => '',
							'rows' => 4,
							'default' => '',
						),
				),
		),
	'page_names' =>
		array(
			0 => 'Page 1',
		),
	'conditional_groups' =>
		array(
			'_open_condition' => '',
		),
	'processors' =>
		array(
			'fp_62821611' =>
				array(
					'ID' => 'fp_62821611',
					'runtimes' =>
						array(
							'insert' => 1,
						),
					'type' => 'cf-aweber',
					'config' =>
						array(
							'cf-aweber-list' => 4290049,
							'cf-aweber-email' => 'fld_4215532',
							'_required_bounds' =>
								array(
									0 => 'cf-aweber-email',
								),
							'cf-aweber-name' => '%name%',
							'cf-aweber-tags' => '',
							'cf-aweber-misc_notes' => '',
							'cf-aweber-add_tracking' => '',
						),
					'conditions' =>
						array(
							'type' => 'use',
							'group' =>
								array(
									'rw70193021234' =>
										array(
											'cl3177823116' =>
												array(
													'field' => 'fld_9240534',
													'compare' => 'is',
													'value' => 'opt1479969',
												),
										),
								),
						),
				),
		),
	'settings' =>
		array(
			'responsive' =>
				array(
					'break_point' => 'sm',
				),
		),
	'mailer' =>
		array(
			'on_insert' => 1,
			'sender_name' => 'Caldera Forms Notification',
			'sender_email' => 'admin@local.dev',
			'reply_to' => '',
			'email_type' => 'html',
			'recipients' => '',
			'bcc_to' => '',
			'email_subject' => 'aweber',
			'email_message' => '{summary}',
		),
	'version' => '1.3.5.3',
);

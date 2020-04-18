<?php
return apply_filters( 'ninja_forms_uploads_styles_field_type_section', array(
	'progress-bar' => array(
		'name'     => 'progress-bar',
		'label'    => __( 'Progress Bar', 'ninja-forms-uploads' ),
		'selector' => '.file_upload-wrap .nf-field-element .nf-fu-progress .nf-fu-progress-bar',
		'only'     => array(
			NF_FU_File_Uploads::TYPE,
		),
	),

	'progress-background' => array(
		'name'     => 'progress-background',
		'label'    => __( 'Progress Bar Background', 'ninja-forms-uploads' ),
		'selector' => '.file_upload-wrap .nf-field-element .nf-fu-progress',
		'only'     => array(
			NF_FU_File_Uploads::TYPE,
		),
	),

	'files-list' => array(
		'name'     => 'files-list',
		'label'    => __( 'File List', 'ninja-forms-uploads' ),
		'selector' => '.file_upload-wrap .nf-field-element .files_uploaded',
		'only'     => array(
			NF_FU_File_Uploads::TYPE,
		),
	),

	'file-delete' => array(
		'name'     => 'file-delete',
		'label'    => __( 'File Delete Link', 'ninja-forms-uploads' ),
		'selector' => '.file_upload-wrap .nf-field-element .files_uploaded p a.delete',
		'only'     => array(
			NF_FU_File_Uploads::TYPE,
		),
	),
) );

<?php return [

/**
 * @package linkonoid\mrevisions
 * @idea and sponsored by Mightyhaggis (https://mightyhaggis.com)
 * @coding Linkonoid (https://github.com/linkonoid, https://linkonoid.com)
 * @uses modal Dialog from Toolbox plugin by Prismify (https://github.com/prismify/oc-toolbox-plugin)
**/

    'plugin' => [
        'name' => 'Multi Revisions',
        'description' => 'Adding Revisions in all models and base plugins',
    ],

    'permissions' => [
        'tab' => 'Multi Revisions',
        'settings' => 'Settings',
    ],

    'settings' => [
    	'menu_label' => 'Settings',
    	'menu_description' => 'Base parameters settings',
        'fields' => [
	        'enabled' => 'Enable Revisionable plugin',
	        'access' => 'Use plugin Permissions',
	        'limit' => 'Default revisionable records Limit ',
	        'models' => 'Model settings',
	        'models_tab' => 'Select Tab to history view',
	        'models_enabled' => 'Enabled this set',
	        'models_quickButtonEnabled' => 'Enable quick restore button on form panel',
	        'models_model_select' => 'Select revisionable Model',
	        'models_model' => "Custom Model name in \Vendor\Plugin\Models\YourModelName format",
	        'models_access' => 'Backend users permissions for this set',
	        'models_revisionables' => 'Select DB fields for revisionable',
	        'models_revisionable_array' => "Custom fields array in ['one','two'] format for merged with DB fields selected",
	        'models_limit' => 'Revisionable records Limit for this Model',
	    ],

        'tabs' => [
	        'workmode' => 'Work mode',
	        'import_export' => 'Import & Export presets',
	    ],
    ],

    'common' => [
        'active' => 'Active',
        'created_at' => 'Created at',
        'updated_at' => 'Updated at',
        'options' => [
	 'empty' => '-- Default Controller Model --',
		],
        'export' => 'Export',
        'import' => 'Import',
        'import_settings' => [
	        'truncate_table' => 'Truncate table',
	        'delete_relations' => 'Delete connections',
        ],

	 'form' => [
			'save_and_copy' => 'Save and copy',
			'save_and_preview' => 'Save and preview',
		]
    ],

];
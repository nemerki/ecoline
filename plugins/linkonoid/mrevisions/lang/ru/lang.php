<?php return [

/**
 * @package linkonoid\mrevisions
 * @idea and sponsored by Mightyhaggis (https://mightyhaggis.com)
 * @coding Linkonoid (https://github.com/linkonoid, https://linkonoid.com)
 * @uses modal Dialog from Toolbox plugin by Prismify (https://github.com/prismify/oc-toolbox-plugin)
**/

    'plugin' => [
        'name' => 'Multi Revisions',
        'description' => 'Добавление функционала ревизий в произвольные модели и базовые плагины',
    ],

    'permissions' => [
        'tab' => 'Multi Revisions',
        'settings' => 'Установки',
    ],

    'settings' => [
    	'menu_label' => 'Установки',
    	'menu_description' => 'Базовые установки',
        'fields' => [
	        'enabled' => 'Разрешить использование на уровне плагина',
	        'access' => 'Использовать настроки доступа на уровне плагина',
	        'limit' => 'Лимит записей в истории изменений по умолчанию',
	        'models' => 'Настройки отслеживания истории моделей',
	        'models_tab' => 'Выберите Tab для просмотра истории',
	        'models_enabled' => 'Разрешить использование данной установки модели',
	        'models_quickButtonEnabled' => 'Разрешить кнорку быстрого восстановления на панели формы',
	        'models_model_select' => 'Выбор отслеживаемой модели',
	        'models_model' => "Пользовательское имя модели в формате \Vendor\Plugin\Models\YourModelName",
	        'models_access' => 'Разрешения бакенд-пользователей для данной установки модели',
	        'models_revisionables' => 'Выбор полей базы данных для отслеживания изменений',
	        'models_revisionable_array' => "Массив пользовательских полей в формате ['one', 'two'] для объединения с выбранными полями БД",
	        'models_limit' => 'Лимит записей в истории изменений для устанавливаемой модели',
	    ],

        'tabs' => [
	        'workmode' => 'Основные настройки',
	        'import_export' => 'Импорт и Экспорт настроек',
	    ],
    ],

    'common' => [
        'active' => 'Active',
        'created_at' => 'Created at',
        'updated_at' => 'Updated at',
        'options' => [
	 		'empty' => '-- Default Controller Model --',
		],
        'export' => 'Экспорт',
        'import' => 'Импорт',
        'import_settings' => [
	        'truncate_table' => 'Truncate table',
	        'delete_relations' => 'Delete connections',
        ],

    ],

];
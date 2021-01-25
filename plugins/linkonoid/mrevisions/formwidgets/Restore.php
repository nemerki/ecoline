<?php namespace Linkonoid\MRevisions\FormWidgets;

use Cms;
use Event;
use Flash;
use Redirect;
use System\Models\Revision;
//use October\Rain\Database\Models\Revision as Revision;
use Backend\Classes\FormField;
use Backend\Classes\FormWidgetBase;
use Linkonoid\MRevisions\Behaviors\RestoreBehavior;

/**
 * @package linkonoid\mrevisions
 * @idea and sponsored by Mightyhaggis (https://mightyhaggis.com)
 * @coding Linkonoid (https://github.com/linkonoid, https://linkonoid.com)
 * @uses modal Dialog from Toolbox plugin by Prismify (https://github.com/prismify/oc-toolbox-plugin)
**/

class Restore extends FormWidgetBase
{
    protected $defaultAlias = 'restore';

    public function render()
    {
        $this->prepareVars();
        return $this->makePartial($this->config->attributes['partial']);
    }

    public function prepareVars()
    {
 		$revisions = $this->vars['revisions'] = [];
        $nameClass = $this->model ? get_class($this->model) : null;
 		$cmsPage = $this->vars['cmsPage'] = isset($this->config->attributes['instanceArr'][$nameClass]);

        if($cmsPage)
        {
            $filename = $this->config->attributes['instanceArr'][$nameClass] . $this->model->fileName;
            $revisionHistory = \Cms\Models\ThemeLog::where('template', $filename)->where('type', '<>', \Cms\Models\ThemeLog::TYPE_CREATE)->with('user')->orderBy('updated_at', 'desc')->get();

        } else {
        	if (!$this->model->revision_history) return;
	        $revisionHistory = $this->model->revision_history->reverse();
        }

	    foreach ($revisionHistory as $history) {
	        $revisions[$history->created_at . $history->user_id][] = $history;
	    }

        $this->vars['revisions'] = $revisions;

        $this->vars['getFieldName'] = function ($fieldName) {
            $fields = $this->parentForm->getFields();
            if (array_key_exists($fieldName, $fields)) {
                $field = $fields[$fieldName];
                return $field->label ?? $field->tab ?? $fieldName;
            }
            return $fieldName;
        };
    }

    public function getSaveValue($value)
    {
        return FormField::NO_SAVE_DATA;
    }

    protected function loadAssets()
    {
        $this->addCss('css/toolbox.css', 'core');
        $this->addCss('/modules/cms/assets/css/themelogs/template-diff.css', 'core');
        $this->addJs('js/toolbox-min.js', ['build' => 'core','cache'  => 'false']);
        $this->addJs('/modules/cms/assets/vendor/jsdiff/diff.js', 'core');
        $this->addJs('/modules/cms/assets/js/themelogs/template-diff.js', 'core');

    }

    public function onContentLoad()
    {
        $this->prepareVars();
		return $this->makePartial('diff',['revision_id' => input('revision_id')]);
    }

    public function onRestore()
    {
        $nameClass = $this->model ? get_class($this->model) : null;

        if(!isset($this->config->attributes['instanceArr'][$nameClass]))
        {
            $revision_id = input('revision_id');

	        $form = get_class($this->model)::findOrFail($this->model->id);

	        if (input('restore_all')) {
	        	$revision = Revision::findOrFail($revision_id);
	            $revisions = Revision::where('user_id', $revision->user_id)->where('created_at', $revision->created_at)->get();
	        } else {
	            $revisionIds = !empty($revision_id) ? input('checkbox_' . $revision_id) : $revision_id;
	            $revisions = Revision::findOrFail($revisionIds);
	        }

		    foreach ($revisions as $revision) {
		        if (!empty($form->{$revision->field}) && !empty($revision->old_value))
		        $form->{$revision->field} = $revision->old_value;
		        Revision::destroy($revision->id);
		    }

	        if ($alert = $form->save())
	        {
	            $revision = Revision::latest()->firstOrFail(); //orderBy('created_at', 'DESC')->firstOrFail();
	            $toDelete = Revision::where('user_id', $revision->user_id)->where('created_at', $revision->created_at)->get()->each->delete();
	        }

	        if ($alert) return RestoreBehavior::alert();

        } else return RestoreBehavior::cmsRestore();
    }
}

<?php namespace Linkonoid\MRevisions\Behaviors;

use System\Classes\ModelBehavior;
use BackendAuth;
use Db;
use Exception;
use DateTime;
use Illuminate\Database\Eloquent\Model as EloquentModel;

/**
 * @package linkonoid\mrevisions
 * @idea and sponsored by Mightyhaggis (https://mightyhaggis.com) 
 * @coding Linkonoid (https://github.com/linkonoid, https://linkonoid.com)
 * @uses modal Dialog from Toolbox plugin by Prismify (https://github.com/prismify/oc-toolbox-plugin)
**/

class RevisionsBehavior extends ModelBehavior//ModelBehaviorBase //ModelBehavior //ControllerBehavior //\October\Rain\Extension\ExtensionBase //\October\Rain\Extension\Extendable
{
    public $revisionable = [];
    public $revisionableLimit = 999;
	public $revisionsEnabled = true;
	const REVISION_HISTORY = 'revision_history';

    public function __construct($model)
    {

        parent::__construct($model);

        $this->model = $model;

        //print_r($model->revisionableVars);

        $this->revisionsEnabled = $model->revisionableVars['revisionsEnabled'];
        $this->revisionableLimit = $model->revisionableVars['revisionableLimit'];
        $this->revisionable = $model->revisionableVars['revisionable'];

		if(is_array($this->model->belongsTo))
        $this->model->belongsTo['user'] = [$model->revisionableVars['modelUser']];

		if(is_array($this->model->morphMany))
        $this->model->morphMany['revision_history'] = ['System\Models\Revision', 'name' => 'revisionable'];

        unset($this->model->revisionableVars);
        unset($model->revisionableVars);

		$model->bindEvent('model.afterUpdate', [$this, 'revisionableModelAfterUpdate']);
        $model->bindEvent('model.afterDelete', [$this, 'revisionableModelAfterDelete']);
    }


    public function revisionableModelAfterUpdate()
    {

        if (!$this->revisionsEnabled) {
            return;
        }

        $relation = $this->model->getRevisionHistoryName();
        $relationObject = $this->model->{$relation}();

        $revisionModel = $relationObject->getRelated();

        $toSave = [];
        $dirty = $this->model->getDirty();

        foreach ($dirty as $attribute => $value) {

            if (!in_array($attribute, $this->revisionable)) {
                continue;
            }

            $toSave[] = [
                'field' => $attribute,
                //'old_value' => array_get($this->model->original, $attribute),
                'old_value' => $this->model->getOriginal($attribute),
                'new_value' => $value,
                'revisionable_type' => $relationObject->getMorphClass(),
                'revisionable_id' => $this->model->getKey(),
                'user_id' => $this->revisionableGetUser(),
                'cast' => $this->revisionableGetCastType($attribute),
                'created_at' => new DateTime,
                'updated_at' => new DateTime
            ];



        }

        // Nothing to do
        if (!count($toSave)) {
            return;
        }

        Db::table($revisionModel->getTable())->insert($toSave);
        $this->revisionableCleanUp();
    }


    public function revisionableModelAfterDelete()
    {
    	if (!$this->model->revisionsEnabled) {
            return;
        }

        $softDeletes = in_array(
            'October\Rain\Database\Traits\SoftDelete',
            class_uses_recursive(get_class($this))
        );

        if (!$softDeletes) {
            return;
        }

        if (!in_array('deleted_at', $this->revisionable)) {
            return;
        }

        $relation = $this->model->getRevisionHistoryName();
        $relationObject = $this->model->{$relation}();
        $revisionModel = $relationObject->getRelated();

        $toSave = [
            'field' => 'deleted_at',
            'old_value' => null,
            'new_value' => $this->deleted_at,
            'revisionable_type' => $relationObject->getMorphClass(),
            'revisionable_id' => $this->model->getKey(),
            'user_id' => $this->revisionableGetUser(),
            'created_at' => new DateTime,
            'updated_at' => new DateTime
        ];

        Db::table($revisionModel->getTable())->insert($toSave);
        $this->revisionableCleanUp();
    }


    /*
     * Deletes revision records exceeding the limit.
     */
    protected function revisionableCleanUp()
    {
        $relation = $this->model->getRevisionHistoryName();
        $relationObject = $this->model->{$relation}();

        $revisionLimit = property_exists($this, 'revisionableLimit')
            ? (int) $this->revisionableLimit
            : 500;

        $toDelete = $relationObject
            ->orderBy('id', 'desc')
            ->skip($revisionLimit)
            ->limit(64)
            ->get();

        foreach ($toDelete as $record) {
            $record->delete();
        }
    }

    protected function revisionableGetCastType($attribute)
    {
        if (in_array($attribute, $this->model->getDates())) {
            return 'date';
        }

        return null;
    }

    public function getRevisionableUser()
	{
		 return BackendAuth::getUser() ? BackendAuth::getUser()->id : null;
	}

    protected function revisionableGetUser()
    {

        if (method_exists($this, 'getRevisionableUser')) {
            $user = $this->getRevisionableUser();

            return $user instanceof EloquentModel
                ? $user->getKey()
                : $user;
        }

        $user = BackendAuth::getUser() ? BackendAuth::getUser()->id : null;

        return $user instanceof EloquentModel
        	? $user->getKey()
            : $user;

        return null;
    }

    /**
     * Get revision history relation name name.
     * @return string
     */
    public function getRevisionHistoryName()
    {
        return defined('static::REVISION_HISTORY') ? static::REVISION_HISTORY : 'revision_history';
    }



}

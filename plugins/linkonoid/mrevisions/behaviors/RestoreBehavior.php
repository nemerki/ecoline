<?php namespace Linkonoid\MRevisions\Behaviors;

use Redirect;
use Flash;
use Event;
use Cms\Models\ThemeLog;
use October\Rain\Filesystem\Filesystem;
use Backend\Classes\ControllerBehavior;

/**
 * @package linkonoid\mrevisions
 * @idea and sponsored by Mightyhaggis (https://mightyhaggis.com) 
 * @coding Linkonoid (https://github.com/linkonoid, https://linkonoid.com)
 * @uses modal Dialog from Toolbox plugin by Prismify (https://github.com/prismify/oc-toolbox-plugin)
**/

class RestoreBehavior extends ControllerBehavior
{
    public static function alert($revisions = null)
    {
		Event::fire('linkonoid.mrevisions.restore', [$revisions]);
	    Flash::success('Changes restored!');
	    return Redirect::refresh();
    }

    public static function cmsRestore()
    {
        $revision_id = input('revision_id');

		$alert = false;

        $revision = ThemeLog::findOrFail($revision_id);

        if($revision->type !== $revision::TYPE_CREATE){

	        $filename = themes_path($revision->theme . '/' . $revision->template);

	        $filesystem = new Filesystem;


	        if ($alert = $filesystem->put($filename, $revision->old_content))
	        {
	            ThemeLog::destroy($revision_id);
	        }

        } else {

	        Flash::success('Changes not restored (create context)!');
	        return Redirect::refresh();
        }

        if ($alert) return self::alert($revision);
    }

    public function onRestore()
    {
        return self::cmsRestore();
    }

}

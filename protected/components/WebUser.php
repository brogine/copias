<?php 
class WebUser extends CWebUser
{
    /**
     * Overrides a Yii method that is used for roles in controllers (accessRules).
     *
     * @param integer $module Id of the module to check access
     * @return bool Permission granted?
     */
    public function hasAccessTo($module)
    {
        if(Yii::app()->user->isGuest)
            return false;
        return in_array($module, Yii::app()->user->permisos);
    }
}
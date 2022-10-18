<?php


namespace App\Services\History;


trait Historyable
{

    use Created,
        Deleted,
        HasRelationshipHistory;


    public static function bootHistoryable()
    {
        if(self::actionHistoryCreate()) {
            self::actionCreated();
        }
        if(self::actionHistoryDelete()) {
            self::actionDeleted();
        }
    }

    protected function saveChange($change, $action) {

        $this->history()->create([
            'user_id' => $this->toOwnerHistory(),
            'action'  => $action,
            'body'    => $change->body
        ]);
    }

    protected function action($key) {

        return collect($this->actionKey())->get($key);
    }

    protected function actionKey() {

        return [
            'created' => 'created'
        ];
    }

    protected static function actionHistoryCreate() {

        return true;
    }

    protected static function actionHistoryDelete() {

       return true;
    }

}

<?php

namespace App\Traits;
use App\Activity;
use ReflectionClass;

trait RecordsActivity {

    // Will work just like the model "boot"
    // The formula is bootTraitName()
    protected static function bootRecordsActivity()
    {

        // Guests dont need anything to be recorded.

        if(auth()->guest()) return;

        foreach ( self::getActivitiesToRecord() as $event => $method) {

            self::$event(function($model) use ($event, $method) {
                $model->$method($event);
             });
        }
    }

    // Choose which boot events to handle.
    protected static function getActivitiesToRecord()
    {
        return ['created' => 'record_activity', 'deleting' => 'delete_activity'];
    }

    protected function record_activity($event){

        $this->activity()->create([
            'user_id' => auth()->id(),
            'type' => $this->getActivityType($event)
        ]);
    }
    protected function delete_activity($event){

        $this->activity()->delete();
    }

    protected function activity()
    {
        return $this->morphMany('App\Activity', 'subject');
    }

    protected function getActivityType($event){

        $type = strtolower((new ReflectionClass($this))->getShortName());
        
        return "{$event}_{$type}";
    }
}
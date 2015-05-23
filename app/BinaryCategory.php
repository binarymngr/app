<?php namespace App;

use LaravelBook\Ardent\Ardent;

final class BinaryCategory extends Ardent
{
    public $autoHydrateEntityFromInput    = true;
    public $forceEntityHydrationFromInput = true;

    protected $dates    = ['created_at', 'updated_at'];
    protected $fillable = ['name', 'description'];
    protected $visible  = ['id', 'name', 'description'];

    public static $relationsData = [
        'binaries' => [self::BELONGS_TO_MANY, 'App\Binary', 'table' => 'binaries_categories']
    ];
    public static $rules = [
        'name' => 'required|between:1,75'  # TODO: unique
    ];


    /**
     * @Override (to detach relations)
     */
    public function delete()
    {
        $this->binaries()->detach();
        return parent::delete();
    }

    public function hasBinaries()
    {
        return !$this->binaries->isEmpty();
    }
}

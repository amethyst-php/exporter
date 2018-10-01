<?php

namespace Railken\Amethyst\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;
use Railken\Amethyst\Schemas\ExporterSchema;
use Railken\Lem\Contracts\EntityContract;

/**
 * @property string      $name
 * @property DataBuilder $data_builder
 * @property string      $input
 * @property string      $body
 * @property string      $filename
 */
class Exporter extends Model implements EntityContract
{
    use SoftDeletes;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'body'      => 'object',
    ];

    /**
     * Creates a new instance of the model.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = Config::get('amethyst.exporter.managers.exporter.table');
        $this->fillable = (new ExporterSchema())->getNameFillableAttributes();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function data_builder()
    {
        return $this->belongsTo(DataBuilder::class);
    }
}

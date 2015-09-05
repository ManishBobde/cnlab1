<?php namespace App\CN\CNBuckets;

use Illuminate\Database\Eloquent\Model;

class Bucket extends Model {

	//

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'buckets';

    /**
     * primary key override
     * @var string
     */
    protected $primaryKey ='bucketId';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    //protected $fillable = ['bucket_type','bucket_name'];

    public $timestamps = false;

    public function messages(){

        return $this->hasMany('App\CN\CNMessages\Messages', 'foreign_key', 'local_key');

    }
}

<?php namespace App\CN\CNMessages;

use App\CN\CNBuckets\Bucket;
use Illuminate\Database\Eloquent\Model;

class Message extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'messages';

    /**
     * primary key override
     * @var string
     */
    protected $primaryKey ='msgId';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['msgTo','msgTitle','msgDesc'];
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['msgRead'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    /*public function buckets(){

        return $this->belongsTo('App\CN\CNBuckets\Bucket','','');
    }*/

}

<?php namespace App\CN\CNColleges;

use Illuminate\Database\Eloquent\Model;

class College extends Model {

	//

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'colleges';

    /**
     * primary key override
     * @var string
     */
    protected $primaryKey ='collegeId';

    public function users(){

        return $this->hasMany('App\CN\CNUsers\User','collegeId','collegeId');

    }

}

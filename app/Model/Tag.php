<?php

namespace App\Model;

class Tag extends Model
{
    //
    protected $table = 'tag';
    protected $primaryKey = 'aid';

    protected $dateFormat = 'U';

    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';
}

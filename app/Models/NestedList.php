<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class NestedList extends Model
{
    use HasFactory;

    protected $table = 'nested_list';
    protected $fillable = ['id', 'parent_id', 'label', 'is_edited'];
    protected $primaryKey = 'id';

    /**
     * Get list data
     * 
     * @param array $whereCondition
     *
     * @return Illuminate\Support\Collection
    */
    public function getList($whereCondition = [])
    {
        return $this->where($whereCondition)->orderBy('label')->orderBy('parent_id')->get();
    }

    /**
     * Insert item in the list and return ID
     * 
     * @param array $insertData
     *
     * @return int
    */
    public function insertListItem($insertData)
    {
        return $this->insertGetId($insertData);
    }

    /**
     * Insert or Update items
     * 
     * @param array $upsertData
     *
     * @return int
    */
    public function upsertItems($upsertData)
    {
        return DB::table('nested_list')->upsert($upsertData, ['id']);
    }

    /**
     * Delete branches
     * 
     * @param array $whereCondition
     *
     * @return int
    */
    public function deleteBranches($whereCondition)
    {
        return $this->where($whereCondition)->delete();
    }
}

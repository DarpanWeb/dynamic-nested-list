<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\NestedList;

class UpdateNestedList extends Command
{
    public function __construct()
    {
        parent::__construct();
        $this->nestedList = new NestedList();
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hour:update-nested-list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hourly updates the nested list in local database depending on the changes of the remote file';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $fetchRemoteList = $this->fetchList();

        // Check if remote file data is not empty
        if (empty($fetchRemoteList)) {
            Log::info('Error: Remote file empty');
            $this->error('Command executed with errors');
            return false;
        }
        
        // Delete non edited branches to remain consistent with remote file
        // $this->nestedList->deleteBranches(['is_edited' => config('constants.STATUSES.DISABLED')]); /* Can be uncommented if need to incorporate delete via remote file as well */

        // Get locally saved data of the list
        $localList = $this->nestedList->getList();
        
        $this->storeItems($localList, $fetchRemoteList['menu_items']);

        $this->info('Command ran successfully');
        return true;
    }

    /**
     * Fetch list from remote server
     * 
     * @return arr
    */
    public function fetchList()
    {
        $listResponse = Http::get('https://dev.shepherds-mountain.appoly.io/fruit.json');

        $responseData = [];
        if ($listResponse->successful()) {
            $responseData = $listResponse->json();
        }

        return $responseData;
    }

    /**
     * Store additional items from the remote file to the local database
     * 
     * @return boolean
    */
    function storeItems($localList, $data, $parentId = null) {
        $labels = [];
        
        DB::beginTransaction();
        foreach ($data as $item) {
            // Check if label is already stored
            $currentItem = $localList->where('label', $item['label'])->first();

            // Add a new entry if the item is not found locally
            if ($currentItem) {
                $currentItemId = $currentItem->id;
            } else {
                $currentItemId = $this->nestedList->insertListItem([
                    'label' => $item['label'],
                    'parent_id' => $parentId,
                    'created_at' => now(),
                ]);
            }
            
            // Recursively call the function if the node is branching out
            if (!empty($item['children'])) {
                $this->storeItems($localList, $item['children'], $currentItemId);
            }
        }
        DB::commit();
        
        return true;
    }
}

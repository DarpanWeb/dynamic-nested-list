<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\NestedList;

class NestedListController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->nestedList = new NestedList();
    }

    public function getList()
    {
        // Fetch all records sorted alphabetically
        $allItems = $this->nestedList->getList();
        $firstLevelItems = $allItems->whereNull('parent_id');

        return view('form', ['firstLevelItems' => $firstLevelItems, 'items' => $allItems]);
    }

    public function editList()
    {
        $this->validate($this->request, [
            'items' => 'required|array',
        ]);

        // Fetch all records sorted alphabetically
        $allItems = $this->nestedList->getList();
        
        $editItems = $this->request->items['edit'] ?? [];
        $addChildren = $this->request->items['children'] ?? [];
        
        // Get keys of all locally changed items to mark them edited in local database
        $editKeys = array_unique(
            array_merge(
                array_keys($editItems), 
                array_keys($addChildren)
            )
        );

        // Create array to insert newly added children in the list
        foreach($addChildren as $parentId => $label) {
            $upsertArray[] = [
                'id' => null,
                'parent_id' => $parentId,
                'label' => $label,
                'is_edited' => config('constants.STATUSES.ENABLED'),
                'created_at' => now(),
                'updated_at' => null,
            ];
        }

        // As locally changed items cannot be deleted, all nodes till the root parent node will be marked as edited
        foreach ($editKeys as $id) {
            $item = $allItems->where('id', $id)->first();
            
            // Mark the current item and all its ancestors as edited
            while ($item) {
                $updateItem = [
                    'id' => $item->id,
                    'parent_id' => $item->parent_id,
                    'label' => $item->label,
                    'is_edited' => config('constants.STATUSES.ENABLED'),
                    'created_at' => $item->created_at,
                    'updated_at' => now(),
                ];
                
                // check if there's a label change for current ID
                if (isset($editItems[$id])) {
                    $updateItem['label'] = $editItems[$id];
                }

                $upsertArray[] = $updateItem;
                $item = $allItems->where('id', $item->parent_id)->first();
            }
        }

        // Update and/or insert items into the list
        $this->nestedList->upsertItems($upsertArray);

        // Return the nested list
        return redirect()->route('home');
    }
}

<tr>
    <td>
        <div class="form-group">
            <label id="item_{{ $item['id'] }}" data-value="{{ $item['label'] }}">{{ $item['label'] }}</label>
        </div>
    </td>
    <td>
        <div class="btn-group" role="group" aria-label="Edit or Add Child">
        <button type="button" class="btn btn-primary edit-btn" data-item-id="{{ $item['id'] }}"><i class="fas fa-pencil-alt"></i></button>
            <button type="button" class="btn btn-success add-child-btn" data-item-id="{{ $item['id'] }}"><i class="fas fa-plus"></i></button>
        </div>
    </td>
    @if($items->where('parent_id', $item['id'])->isNotEmpty())
        <td>
            <table class="table table-bordered">
                <tbody>
                    @foreach($items->where('parent_id', $item['id'])->sortBy('label') as $child)
                        @include('nested-list', ['item' => $child])
                    @endforeach
                </tbody>
            </table>
        </td>
    @endif
</tr>

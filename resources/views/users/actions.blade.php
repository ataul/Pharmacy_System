<div class="d-flex flex-row justify-content-center btn-group btn-group-toggle" data-toggle="buttons">
    <div class="d-flex flex-row gap-1">
        <button type="button" class="btn btn-success rounded me-2" onclick="usereditmodalShow(event)" id="{{$id}}" data-bs-toggle="modal" data-bs-target="#user-edit">Edit</button>
        <button type="button" class="btn btn-primary rounded me-2" onclick="usershowmodalShow(event)" id="{{$id}}" data-bs-toggle="modal" data-bs-target="#show-user">Show</button>
        <form method="post" class="delete_item me-2"  action="{{route('users.destroy', $id)}}">
            @csrf
            @method("DELETE")
            <button type="button" class="btn btn-danger rounded delete-user" onclick="userdeletemodalShow(event)" id="delete_{{$id}}" data-bs-toggle="modal" data-bs-target="#user-del-model">Delete</button>
        </form>
    </div>
</div>

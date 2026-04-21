<div class="modal fade" id="user-edit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" id="edit-user-form">
                @csrf
                @method('PUT')
                <div class="modal-body row g-3">
                    <div class="col-md-12">
                        <label for="edit-user-name" class="form-label">Name</label>
                        <input name="name" class="form-control" id="edit-user-name" placeholder="Name" required>
                    </div>
                    <div class="col-md-12">
                        <label for="edit-user-email" class="form-label">Email</label>
                        <input name="email" type="email" class="form-control" id="edit-user-email" placeholder="Email" required>
                    </div>
                    <div class="col-md-12">
                        <label for="edit-user-password" class="form-label">Password (Leave blank to keep current)</label>
                        <input name="password" type="password" class="form-control" id="edit-user-password" placeholder="Password">
                    </div>
                    <div class="col-md-12">
                        <label for="edit-user-roles" class="form-label">Roles</label>
                        <select name="roles[]" id="edit-user-roles" class="form-select" multiple>
                            @foreach($roles as $role)
                                <option value="{{$role->name}}">{{$role->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success text-white">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function usereditmodalShow(event) {
        let userId = event.currentTarget.id;
        $('#edit-user-form').attr('action', "{{route('users.index')}}/" + userId);
        $.ajax({
            url: "{{route('users.index')}}/" + userId,
            type: "GET",
            success: function(data) {
                $('#edit-user-name').val(data.user.name);
                $('#edit-user-email').val(data.user.email);
                $('#edit-user-password').val("");
                $('#edit-user-roles').val(data.roles);
            }
        });
    }
</script>
@endpush

<div class="modal fade" id="create-user" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Create New User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{route('users.store')}}" id="create-user-form">
                @csrf
                <div class="modal-body row g-3">
                    <div class="col-md-12">
                        <label for="user-name" class="form-label">Name</label>
                        <input name="name" class="form-control" id="user-name" placeholder="Name" required>
                    </div>
                    <div class="col-md-12">
                        <label for="user-email" class="form-label">Email</label>
                        <input name="email" type="email" class="form-control" id="user-email" placeholder="Email" required>
                    </div>
                    <div class="col-md-12">
                        <label for="user-password" class="form-label">Password</label>
                        <input name="password" type="password" class="form-control" id="user-password" placeholder="Password" required>
                    </div>
                    <div class="col-md-12">
                        <label for="user-roles" class="form-label">Roles</label>
                        <select name="roles[]" id="user-roles" class="form-select" multiple>
                            @foreach($roles as $role)
                                <option value="{{$role->name}}">{{$role->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success text-white">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function createmodalShow(event) {
        $('#create-user-form').trigger("reset");
    }
</script>
@endpush

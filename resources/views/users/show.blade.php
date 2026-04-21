<div class="modal fade" id="show-user" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">User Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>ID:</strong> <span id="show-user-id"></span></p>
                <p><strong>Name:</strong> <span id="show-user-name"></span></p>
                <p><strong>Email:</strong> <span id="show-user-email"></span></p>
                <p><strong>Roles:</strong> <span id="show-user-roles"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function usershowmodalShow(event) {
        let userId = event.currentTarget.id;
        $.ajax({
            url: "{{route('users.index')}}/" + userId,
            type: "GET",
            success: function(data) {
                $('#show-user-id').text(data.user.id);
                $('#show-user-name').text(data.user.name);
                $('#show-user-email').text(data.user.email);
                $('#show-user-roles').text(data.roles.join(', '));
            }
        });
    }
</script>
@endpush

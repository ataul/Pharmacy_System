<div class="modal fade" id="user-del-model" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Delete User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this user?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirm-user-delete">Delete</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let deleteForm;
    function userdeletemodalShow(event) {
        deleteForm = $(event.currentTarget).closest('form');
    }

    $('#confirm-user-delete').on('click', function() {
        if (deleteForm) {
            deleteForm.submit();
        }
    });
</script>
@endpush

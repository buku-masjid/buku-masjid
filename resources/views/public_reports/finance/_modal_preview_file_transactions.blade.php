<div class="modal" id="transaction-file-preview" role="dialog" aria-labelledby="modalTransactionFilePreview">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTransactionFilePreview"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="img-container text-center">
                    <img alt="file-preview" src="#" />
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('app.close') }}</button>
                <a download href="#" class="btn btn-success" id="download">{{ __('file.download') }}</a>
            </div>
        </div>
    </div>
</div>
<span style="font-size: 90%;">
@if ($files->count())
    @if ($files->count() == 1)
        <a onclick="showPreviewFile('{{ asset('storage/'.$files->first()->file_path) }}', this)" href="#" class="badge bg-light text-bg-light">
            1 <i class="ti ti-photo fs-3"></i>
            @if ($files->first()->title)
                <div class="d-none">{{ $files->first()->title }}</div>
            @else
                <div class="d-none">{{ __('transaction.files') }} 1</div>
            @endif
        </a>
    @else
        <div class="btn-group dropstart">
            <button type="button" class="badge bg-light text-bg-light" data-bs-toggle="dropdown" aria-expanded="false">
                {{ $files->count() }} <i class="ti ti-photo fs-3"></i>
            </button>
            <ul class="dropdown-menu">
                @foreach ($files as $key => $file)
                    <div class="dropdown-item">
                        <a class="w-full" onclick="showPreviewFile('{{ asset('storage/'.$file->file_path) }}', this)" href="#">
                            @if ($file->title)
                                <div>{{ $file->title }}</div>
                            @else
                                <div>{{ __('transaction.files') }} {{ 1 + $key }}</div>
                            @endif
                        </a>
                    </div>
                @endforeach
            </ul>
        </div>
    @endif
@endif
</span>
<script>
    function setResponsiveImage($modalPreview, $img, $downloadButton) {
        const imgEl = $img.get(0)
        const modalWidthVar = "--tblr-modal-width"

        const windowWidth = window.document.body.clientWidth;
        const windowHeight = window.document.body.clientHeight - 230;

        let actualWidth = imgEl.naturalWidth;
        let actualHeight = imgEl.naturalHeight;

        const isLandscape = actualWidth >= actualHeight;

        if(actualWidth > windowWidth) {
            actualWidth = windowWidth
            actualHeight = (actualWidth * imgEl.naturalHeight) / imgEl.naturalWidth;
        }
        if(actualHeight > windowHeight) {
            actualHeight = windowHeight
            actualWidth = (actualHeight * imgEl.naturalWidth) / imgEl.naturalHeight;
        }

        $modalPreview.find(".modal-dialog.modal-lg").css(
            modalWidthVar, `${actualWidth <= 300 ? actualWidth + 300 : actualWidth}px`);

        $img.css('height', actualHeight)
            .css('width', actualWidth)

        $modalPreview.modal('show')
        $modalPreview.on('hide.bs.modal', function () {
            $img.removeAttr('src height width')
            $downloadButton.removeAttr('href download')
        });
    }

    function showPreviewFile(url, linkEl) {
        const $modalPreview = $("#transaction-file-preview")
        const $img = $modalPreview.find('.img-container img')
        const $downloadButton = $modalPreview.find("#download")
        const headerTitle = $(linkEl).find("div").text()
        $modalPreview.find("#modalTransactionFilePreview").text(headerTitle)
        $modalPreview.find(".modal-body").css('padding', 0)
        $modalPreview.find(".modal-footer").css('padding-top', '0.75rem')

        $downloadButton.attr("href", url)
            .attr("download", url.split("/").pop()
        )
        $img.attr('src', url).on("load", function() {
            setResponsiveImage($modalPreview, $img, $downloadButton)
        })

    }
</script>

<script>
    $(function () {
        //$.HSCore.components.HSModalWindow.init('[data-modal-target]');
        $('.load-media').click(function (e) {
            e.preventDefault();
            
            var newModal = new Custombox.modal({
                overlay: {
                    close: false
                },
                content: {
                    target: '#mediaModal',
                    effect: 'slit',
                    animateFrom: 'left',
                    animateTo: 'left',
                    positionX: 'center',
                    positionY: 'center',
                    speedIn: 300,
                    speedOut: 300,
                    fullscreen: false,
                    onClose: function () {
                        $('.imgareaselect-outer, .imgareaselect-selection, .imgareaselect-border1, .imgareaselect-border2').hide();
                    }
                }
            });
            newModal.open();
            
            $('.loaded-image-id').removeClass('current-image-id');
            $(this).children('input').addClass('current-image-id');
            $('.loaded-image').removeClass('current-image');
            $(this).next('img').addClass('current-image');
            localStorage.setItem('uploadedImageId', $(this).attr('data-image_id'));
            var url = 'admin/images/media/' + $(this).attr('data-model') + "/" + $(this).attr('data-category') + "/" + $(this).attr('data-user_id');
            $.get(SITE_URL + url, function (data) {
                $("#imageMedia").html(data);
            });
            
        });
    })
</script>

<!-- Demo modal window -->
<div id="mediaModal" class="text-left g-bg-white g-overflow-y-auto  g-pa-20"
     style="max-height: 900px; max-width: 1600px; display: none; width: 80%;  min-width: 1200px;    min-height: 700px; ">
    <button type="button" class="close" onclick="Custombox.modal.close();">
        <i class="hs-icon hs-icon-close"></i>
    </button>
    <h4 class="g-mb-20">Media</h4>
    <div calss="modal-body" id="imageMedia" style="position: relative;">
    
    </div>
    <div class="clear-both"></div>
</div>
<!-- End Demo modal window -->

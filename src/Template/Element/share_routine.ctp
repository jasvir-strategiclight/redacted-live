<div id="shareRoutine" class="text-left g-bg-white g-overflow-y-auto  g-pa-20"
     style="max-height: 300px; max-width: 600px; display: none; width: 80%;  min-width: 600px;    min-height: 200px; ">
    <button type="button" class="close" onclick="Custombox.modal.close();">
        <i class="hs-icon hs-icon-close"></i>
    </button>
    <h4 class="g-mb-20">Share Program</h4>
    <div calss="modal-body" id="shareRoutineContent" style="position: relative;">
        <?= $this->Form->create(null, ['url' => 'javascript:void(0);', 'id' => 'shareRoutineForm']); ?>
        <div class="row">
            <div class="col-md-12">
                <label class="label font-bold">Share with:</label>
                <input type="email" name="email" class="form-control" placeholder="Email" id="sharableEmail">
                <input type="hidden" name="routine_id" value="" id="shareRoutineId">
            </div>
            <div class="col-md-8"></div>
            <div class="col-md-4 mt-3 pl-2">
                
                <button type="submit" class="btn btn-success">
                    <i class="fa fa-share-alt"></i> Share
                </button>
                <button type="button" class="btn btn-primary" onclick="Custombox.modal.close();">
                    <i class="fa fa-close"></i> Cancel
                </button>
            </div>
        </div>
        <?= $this->Form->end(); ?>&nbsp;
    </div>
    <div calss="modal-body" id="shareAjaxMessage" style="position: relative; display: none">
        <h3></h3>
        <div class="row">
            <div class="col-md-10"></div>
            <div class="col-md-2" id="okayBtn" style="display: none;">
                <button type="button" class="btn btn-primary" onclick="Custombox.modal.close();">
                    <i class="fa fa-close"></i> OK
                </button>
            </div>
        </div>
        <div class="clear-both"></div>
    </div>
</div>
<script>
    $(function () {
        $('.share-routine').click(function (e) {
            e.preventDefault();
            
            $('#shareAjaxMessage').hide();
            $('#shareRoutineContent').fadeIn();
            $('#shareRoutineId').val($(this).attr('data-id'));
            
            var newModal = new Custombox.modal({
                overlay: {
                    close: false
                },
                content: {
                    target: '#shareRoutine',
                    effect: 'blur',
                    animateFrom: 'left',
                    animateTo: 'left',
                    positionX: 'center',
                    positionY: 'center',
                    speedIn: 300,
                    speedOut: 300,
                    fullscreen: false,
                    onClose: function () {
                        //Do something Here;
                    }
                }
            });
            newModal.open();
        });
        
        
        $('#shareRoutineForm').validate({
            rules: {
                email: {
                    required: true,
                    email: true
                }
            },
            messages: {
                email: {
                    required: "Please enter email.",
                    email: "Please enter valid email."
                }
            },
            submitHandler: function (form) {
                
                $.ajax({
                    url: SITE_URL + 'routines/share',
                    type: "POST",
                    data: $('#shareRoutineForm').serialize(),
                    dataType: "JSON",
                    beforeSend: function () {
                        $('#shareRoutineContent').hide();
                        $('#shareAjaxMessage h3').html('<i class="fa fa-spin fa-spinner"></i>  Sharing...');
                        $('#shareAjaxMessage').fadeIn();
                    },
                    success: function (resp) {
                        $('#sharableEmail').val('');
                        $('#shareAjaxMessage h3').html(resp.message);
                        $('#okayBtn').fadeIn();
                    }
                });
            }
        });
    });
</script>

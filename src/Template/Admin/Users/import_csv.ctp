<form action="<?= $this->Url->build(['Controller' => 'Users', 'action' => 'importCsv']); ?>" method="post"
      enctype="multipart/form-data" id="importCsvFrom">
    <div class="row">
        <div class="col-md-2"><label>Select list type:</label>

            <select name="list_type" class="form-control">
                <option value="">Select List Type</option>
                <option value="subscribed">Subscribed</option>
                <option value="unsubscribed">Unsubscribed</option>
            </select>
        </div>
        <div class="col-md-4">
            <label>Select image to upload:</label>
            <input type="file" name="file" id="fileToUpload" class="form-control">
        </div>
        <div class="col-md-4" style="padding-top: 30px">
            <input type="submit" value="Upload File" name="submit" class="btn btn-primary btn-md">
        </div>
    </div>
</form>

<script>
    $(function () {
        $('#importCsvFrom').validate({
            rules:
                {
                    list_type: {
                        required: true,
                    },
                    file: {
                        required: true,
                    }
                },
            messages:
                {
                    list_type: {
                        required: "Please select list type.",
                    },
                    file: {
                        required: "Please select CSV file.",
                    }
                }
        })
        ;
    });
</script>
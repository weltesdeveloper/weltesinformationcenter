<link href="../css/jquery.dataTables.css" rel="stylesheet" type="text/css"/>
<script src="../jQuery/jquery-2.1.1.min.js"></script>
<script src="../AdminLTE/js/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../jQuery/jquery.csv-0.71.js" type="text/javascript"></script>
<script src="../jQuery/jquery.csv-0.71.min.js" type="text/javascript"></script>

<div id="dvImportSegments" class="fileupload ">
    <fieldset>
        <legend>Upload your CSV File</legend>
            <input type="file" name="File Upload" id="txtFileUpload" accept=".csv" />
   </fieldset>
</div>

<script type="text/javascript">  
    $(document).ready(function() {

    // The event listener for the file upload
    document.getElementById('txtFileUpload').addEventListener('change', upload, false);

    // Method that checks that the browser supports the HTML5 File API
    function browserSupportFileUpload() {
        var isCompatible = false;
        if (window.File && window.FileReader && window.FileList && window.Blob) {
        isCompatible = true;
        }
        return isCompatible;
    }

    // Method that reads and processes the selected file
    function upload(evt) {
    if (!browserSupportFileUpload()) {
        alert('The File APIs are not fully supported in this browser!');
        } else {
            var data = null;
            var file = evt.target.files[0];
            var reader = new FileReader();
            reader.readAsText(file);
            reader.onload = function(event) {
                var csvData = event.target.result;
                data = $.csv.toArrays(csvData);
                if (data && data.length > 0) {
                  alert('Imported -' + data.length + '- rows successfully!');
                } else {
                    alert('No data to import!');
                }
            };
            reader.onerror = function() {
                alert('Unable to read ' + file.fileName);
            };
        }
    }
};
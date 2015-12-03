<section id="section-WasteDetails">
    <div class="row">
        <div class="col-md-12">

            <div class="box box-warning">
                <div class="box-header with-border">
                    <i class="fa fa-pie-chart"></i>
                    <h3 class="box-title"> Waste <b><span id="txt_job_input_stock"></span> ~ Details Input</b>
                        <small>Collect Waste Details</small>
                    </h3>
                </div> 

                <div class="box-body">
                    <div class="row">

                        <div class="col-md-6">
                            <label>West Name </label>
                            <input type="text" class="form-control" id="nestingFile" placeholder="">
                        </div>
                        <div class="col-md-6">
                            <label>Qty</label>
                            <input type="text" class="form-control" id="qty" placeholder="">
                        </div>
                    </div>

                    <br/>
                    <div class="row">
                        <!--<div class="col-md-6">-->
                        <div class="col-md-2">
                            <label>Tebal</label>
                            <input type="text" class="form-control" id="tebal" disabled>
                        </div>
                        <div class="col-md-2">
                            <label>Lenght</label>
                            <input type="text" class="form-control" id="length" placeholder="">
                        </div>
                        <div class="col-md-2">
                            <label>Width</label>
                            <input type="text" class="form-control" id="width" placeholder="">
                        </div>
                        <!--</div>-->

                        <div class="col-md-6">
                            <label>Grade</label>
                            <!--<input type="text" class="form-control" id="grade" placeholder="">-->
                            <select class="form-control selectpicker" id="grade" data-live-search="true">                                
                            </select>
                        </div>
                    </div>
                    <br/>
                    <div class="row">
                        <div class="col-md-6">
                            <label>Berat Jenis</label>
                            <input type="text" class="form-control" id="beratJenis" value="7.85" disabled>
                            <br/>
                            <label>Weight</label>
                            <input type="text" class="form-control" id="weight" placeholder="" disabled>
                        </div>
                        <div class="col-md-6">
                            <label>Remark</label>
                            <textarea cols="10" rows="4" class="form-control" id="remark"></textarea>
                        </div>
                    </div>
                </div>

            </div> 

        </div><!-- /.col -->
    </div><!-- /.row -->

    <div class="row">
        <div class="col-md-12">
            <button class="btn btn-block btn-outline btn-success" id="btnProsesWaste">SUBMIT WASTE</button>
        </div>
    </div>
</section>

<script type="text/javascript">
    $(document).ready(function () {
        selectGrade();
        
        var profile = $('#specificElement option:selected').html();
        var hasil = profile.split(/[\smm]+/);
        var x = hasil[1];
        var y = x.replace('mm')
        $('#tebal').val(hasil[1]);

        $("#length").keyup(function () {
            var t = $('#tebal').val();
            var p = $('#length').val();
            var l = $('#width').val();
            var bj = $('#beratJenis').val();
            RumusWeight(t, p, l, bj);
        });

        $("#width").keyup(function () {
            var t = $('#tebal').val();
            var p = $('#length').val();
            var l = $('#width').val();
            var bj = $('#beratJenis').val();
            RumusWeight(t, p, l, bj);
        });

    })

    function selectGrade() {
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: 'divpages/list/listWasteDetails.php',
            data: {action: 'selectGrade'},
            beforeSend: function () {
            },
            success: function (json) {
//                alert(json);
                $("select#grade").empty();
                var isiOption = '<option value="">-[Select Grade]-</option>';
                $.each(json, function (index, row) {
                    isiOption += '<option value="' + row.INV_GRD + '">' + row.INV_GRD + '</option>';
                });
                $("select#grade").append(isiOption);

            },
            complete: function () {
            }
        });
    }

    function RumusWeight(t, p, l, bj) {
        //RUMUS WEIGHT TxPxLxBeratJEnis/1jt
        if (l == "") {
            l = '1';
        }
        if (p == "") {
            p = '1';
        }
        var result = (t * p * l * bj) / 1000000;
        $('#weight').val(result);
        return false
    }

    $('#btnProsesWaste').click(function () {
        if (confirm('Are You Sure to Input Waste ?')) {
            var nestingFile = $('#nestingFile').val();
            var width = $('#width').val();
            var length = $('#length').val();
            var weight = $('#weight').val();
            var qty = $('#qty').val();
            var grade = $('#grade').val();
            var remark = $('#remark').val();
            var userINP = $('span#user').text();
            if (nestingFile == "") {
                sweetAlert("Oops...", "Data Tidak Lengkap !", "error");
            } else {
                var sentReq = {
                    action: 'inputWest',
                    nestingFile__: nestingFile,
                    width__: width,
                    length__: length,
                    weight__: weight,
                    qty__: qty,
                    grade__: grade,
                    remark__: remark,
                    userINP__: userINP
                };

                $.ajax({
                    type: "POST",
                    dataType: 'json',
                    url: 'divpages/list/listWasteDetails.php',
                    data: sentReq,
                    beforeSend: function () {
                    },
                    success: function (json) {
                        alert(json);
                        console.log(json);

                    },
                    complete: function () {
                        swal("Good job!", "You clicked the button!", "success")
                        var nestingFile = $('#nestingFile').val('');
                        var width = $('#width').val('');
                        var length = $('#length').val('');
                        var qty = $('#qty').val('');
                        var grade = $('select#grade').val('');
                        var remark = $('#remark').val('');
                        var weight = $('#weight').val('');
                        $('#nestingFile').focus();
                    }
                });
            }
        }
        else {
            return false;
        }


    });
</script>
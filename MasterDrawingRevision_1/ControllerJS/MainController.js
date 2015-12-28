function getCHeckBox() {
    var table = $('#table-revision').DataTable();
    var project_name = $('#projName').val();
    var head_mark = [];
    var comp_type = [];
    var profile = [];
    var surface = [];
    var length = [];
    var qty = [];
    var status = [];
    var weight = [];
    var gr_weight = [];
    var type_bld = [];
    var remark = [];
    var dwg_type = [];
    table.rows().every(function () {

        var element = $(this.node());
        var index = element.prop('id').replace('row', '');
        if (element.find('#check' + index).prop('checked') === true) {
            head_mark.push($("#head_mark" + index).text().trim());
            comp_type.push($("#comp_type" + index).text().trim());
            profile.push($("#profile" + index).text().trim());
            surface.push($("#surface" + index).text().trim());
            length.push($("#length" + index).text().trim());
            qty.push($("#total_qty" + index).text().trim());
            status.push($("#subcont_status" + index).text().trim());
            weight.push($("#weight" + index).text().trim());
            gr_weight.push($("#gr_weight" + index).text().trim());
            type_bld.push($("#type_bld" + index).text().trim());
            dwg_type.push($("#dwg_typ" + index).text().trim());
            remark.push($("#remark" + index).val());
        }
    });
    var sentReq = {
        project_name:project_name,
        head_mark: head_mark,
        comp_type: comp_type,
        profile: profile,
        surface: surface,
        length: length,
        qty: qty,
        status: status,
        weight: weight,
        gr_weight: gr_weight,
        type_bld: type_bld,
        remark: remark,
        dwg_type:dwg_type,
        action: "show_rev"
    };
    return sentReq;
}

function SubmitRevision() {
    var sentReq = getCHeckBox();
    $.ajax({
        type: 'POST',
        url: "ModelPHP/rev_model.php",
        data: sentReq,
        dataType: 'JSON',
        success: function (response, textStatus, jqXHR) {
            if(response.indexOf("GAGAL")==-1){
                alert("SUKSES UPDATE");
                $("#modal-revision").modal('hide');
                window.location.reload();
            }else{
                alert("GAGAL UPDATE");
            }
        }
    });
}

function ShowModal() {
    //UNTUK PROSES SHOW MODAL
    var sentReq = getCHeckBox();
    console.log(sentReq);
    $("#table-showrev tbody").empty();
    var content = "";
    for (var key = 0; key < sentReq.head_mark.length; key++) {
        console.log(key);
        content += "<tr>" +
                "<td class='text-center'>" + sentReq.head_mark[key] + "</td>" +
                "<td class='text-center'>" + sentReq.comp_type[key] + "</td>" +
                "<td class='text-center'>" + sentReq.profile[key] + "</td>" +
                "<td class='text-center'>" + sentReq.surface[key] + "</td>" +
                "<td class='text-center'>" + sentReq.length[key] + "</td>" +
                "<td class='text-center'>" + sentReq.qty[key] + "</td>" +
                "<td class='text-center'>" + sentReq.status[key] + "</td>" +
                "<td class='text-center'>" + sentReq.weight[key] + "</td>" +
                "<td class='text-center'>" + sentReq.gr_weight[key] + "</td>" +
                "<td class='text-center'>" + sentReq.type_bld[key] + "</td>" +
                "<td class='text-center'>" + sentReq.dwg_type[key] + "</td>" +
                "<td class='text-center'>" + sentReq.remark[key] + "</td>" +
                "</tr>";

    }
    $("#table-showrev tbody").append(content);
    $("#modal-revision").modal('show');
}

$(document).ready(function () {
    $('#projName').selectpicker();
    $('#projName').change(function () {
        var projectno = $(this).val();
        $.ajax({
            type: "POST",
            url: "ModelPHP/rev_model.php",
            data: {job: projectno, action: "show_hm"},
            dataType: 'JSON',
            beforeSend: function (xhr) {
                $("#table-revision").DataTable().destroy();
                $('#table-revisoion tbody').empty();
            },
            success: function (response, textStatus, jqXHR) {
                var content = "";
                $.each(response, function (key, value) {
                    content += "<tr id=row" + key + ">" +
                            "<td class='text-center' id='head_mark" + key + "'>" + value.HEAD_MARK + "</td>" +
                            "<td class='text-center'>" + "<a href='#' data-pk='" + value.HEAD_MARK + "' data-id='comp_type' id='comp_type" + key + "' data-type='select'>" + value.COMP_TYPE + "</a></td>" +
                            "<td class='text-center'>" + "<a href='#' data-pk='" + value.HEAD_MARK + "' id='profile" + key + "' data-type='text'>" + value.PROFILE + "</a></td>" +
                            "<td class='text-center'>" + "<a href='#' data-pk='" + value.HEAD_MARK + "' id='surface" + key + "' data-type='text'>" + value.SURFACE + "</a></td>" +
                            "<td class='text-center'>" + "<a href='#' data-pk='" + value.HEAD_MARK + "' id='length" + key + "' data-type='text'>" + value.LENGTH + "</a></td>" +
                            "<td class='text-center'>" + "<a href='#' data-pk='" + value.HEAD_MARK + "' id='total_qty" + key + "' data-type='text'>" + value.TOTAL_QTY + "</a></td>" +
                            "<td class='text-center'>" + "<a href='#' data-pk='" + value.HEAD_MARK + "' id='subcont_status" + key + "' data-type='text'>" + value.SUBCONT_STATUS + "</a></td>" +
                            "<td class='text-center'>" + "<a href='#' data-pk='" + value.HEAD_MARK + "' id='weight" + key + "' data-type='text'>" + value.WEIGHT + "</a></td>" +
                            "<td class='text-center'>" + "<a href='#' data-pk='" + value.HEAD_MARK + "' id='gr_weight" + key + "' data-type='text'>" + value.GR_WEIGHT + "</a></td>" +
                            "<td class='text-center'>" + "<a href='#' data-pk='" + value.HEAD_MARK + "' id='dwg_typ" + key + "' data-type='select'>" + value.DWG_TYP + "</a></td>" +
                            "<td class='text-center'>" + "<a href='#' data-pk='" + value.HEAD_MARK + "' id='type_bld" + key + "' data-type='select'>" + value.TYPE_BLD + "</a></td>" +
                            "<td class='text-center'>" + "<input type='text' class='form-control' id='remark" + key + "'>" + "</td>" +
                            "<td class='text-center'>" + "<input type='checkbox' id='check" + key + "'>" + "</td>" +
                            "</tr>";
                });
                $('#table-revision tbody').html(content);
            },
            complete: function (jqXHR, textStatus) {
                $("#table-revision").DataTable({
                    "drawCallback": function (settings) {

//                                    EDIT COMP TYPE
                        $('a[id^=comp_type]').editable({
                            mode: 'inline',
                            showbuttons: false,
                            source: source_comptype,
                            success: function (response, newValue) {
                                var project_name = $('#projName').val();
                                var element = $(this);
                                var headmark = element.data("pk");
                                var id = element.data("id");
                                var sentReq = {
                                    project_name: project_name,
                                    headmark: headmark,
                                    id: id
                                };
                                console.log(sentReq);
                            }
                        });

                        $('a[id^=profile]').editable({
                            mode: 'inline',
                            showbuttons: false,
//                                        source: source_comptype,
                            success: function (response, newValue) {
                                var project_name = $('#projName').val();
                                var element = $(this);
                                var headmark = element.data("pk");
                                var id = element.data("id");
                                var sentReq = {
                                    project_name: project_name,
                                    headmark: headmark,
                                    id: id
                                };
                                console.log(sentReq);
                            }
                        });

                        $('a[id^=surface]').editable({
                            mode: 'inline',
                            showbuttons: false,
//                                        source: source_comptype,
                            success: function (response, newValue) {
                                var project_name = $('#projName').val();
                                var element = $(this);
                                var headmark = element.data("pk");
                                var id = element.data("id");
                                var sentReq = {
                                    project_name: project_name,
                                    headmark: headmark,
                                    id: id
                                };
                                console.log(sentReq);
                            }
                        });
                        $('a[id^=length]').editable({
                            mode: 'inline',
                            showbuttons: false,
//                                        source: source_comptype,
                            success: function (response, newValue) {
                                var project_name = $('#projName').val();
                                var element = $(this);
                                var headmark = element.data("pk");
                                var id = element.data("id");
                                var sentReq = {
                                    project_name: project_name,
                                    headmark: headmark,
                                    id: id
                                };
                                console.log(sentReq);
                            }
                        });
                        $('a[id^=total_qty]').editable({
                            mode: 'inline',
                            showbuttons: false,
//                                        source: source_comptype,
                            success: function (response, newValue) {
                                var project_name = $('#projName').val();
                                var element = $(this);
                                var headmark = element.data("pk");
                                var id = element.data("id");
                                var sentReq = {
                                    project_name: project_name,
                                    headmark: headmark,
                                    id: id
                                };
                                console.log(sentReq);
                            }
                        });
                        $('a[id^=weight]').editable({
                            mode: 'inline',
                            showbuttons: false,
//                                        source: source_comptype,
                            success: function (response, newValue) {
                                var project_name = $('#projName').val();
                                var element = $(this);
                                var headmark = element.data("pk");
                                var id = element.data("id");
                                var sentReq = {
                                    project_name: project_name,
                                    headmark: headmark,
                                    id: id
                                };
                                console.log(sentReq);
                            }
                        });
                        $('a[id^=gr_weight]').editable({
                            mode: 'inline',
                            showbuttons: false,
//                                        source: source_comptype,
                            success: function (response, newValue) {
                                var project_name = $('#projName').val();
                                var element = $(this);
                                var headmark = element.data("pk");
                                var id = element.data("id");
                                var sentReq = {
                                    project_name: project_name,
                                    headmark: headmark,
                                    id: id
                                };
                                console.log(sentReq);
                            }
                        });

                        $('a[id^=dwg_typ]').editable({
                            mode: 'inline',
                            showbuttons: false,
                            source: [
                                {value: "H", text: 'H'},
                                {value: "W", text: 'W'},
                                {value: "M", text: 'M'},
                                {value: "L", text: 'L'},
                                {value: "CR", text: 'CR'}
                            ],
                            success: function (response, newValue) {
                                var project_name = $('#projName').val();
                                var element = $(this);
                                var headmark = element.data("pk");
                                var id = element.data("id");
                                var sentReq = {
                                    project_name: project_name,
                                    headmark: headmark,
                                    id: id
                                };
                                console.log(sentReq);
                            }
                        });
                        $('a[id^=type_bld]').editable({
                            mode: 'inline',
                            showbuttons: false,
                            source: [
                                {value: "MECHANICAL", text: 'MECHANICAL'},
                                {value: "STRUCTURE", text: 'STRUCTURE'}
                            ],
                            success: function (response, newValue) {
                                var project_name = $('#projName').val();
                                var element = $(this);
                                var headmark = element.data("pk");
                                var id = element.data("id");
                                var sentReq = {
                                    project_name: project_name,
                                    headmark: headmark,
                                    id: id
                                };
                                console.log(sentReq);
                            }
                        });
                    }
                });
            }
        });
    });
});
<style>
	.alert-container { background-color: #e89517; color: black; font-weight: bolder;}
    .label-title { color: black; font-weight: bold; }
    .label { color: black; font-weight: bold; }
    .detail { color: black; text-align: center; }
    .footerCss { color: black; font-weight: bold; }
	.text_input {font-size: small; color: black;}
</style>

<section>
    <div class="container-fluid">
        <br>
        <div class="alert alert-success" role="alert">
            <i class="fas fa-university"></i> Laporan Master Barang
        </div>
        <?php echo $this->session->flashdata('pesan') ?>
        <form id="masterbarang" method="post" action="<?php echo base_url('admin/laporan/index_MasterBarang') ?>">
            <!-- <div class="col-md-12">
                <div class="form-group row">
                    <div class="col-md-1">
                        <label class="label-title">Barang </label>
                    </div>
                    <div class="col-md-3">
                        <select class="js-example-responsive form-control KD_BHN_1" name="KD_BHN_1" id="KD_BHN_1" style="width: 100%;">
                            <?php
                            if (isset($_POST["tampilkan"]) &&  $_POST["KD_BHN_1"] == $KD_BHN_1) {
                                echo '<option value="' . $KD_BHN_1 . '" selected >' . $KD_BHN_1 . '</option>';
                            } ?>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <label class="label-title">s/d</label>
                    </div>
                    <div class="col-md-3">
                        <select class="js-example-responsive form-control KD_BHN_2" name="KD_BHN_2" id="KD_BHN_2" style="width: 100%;">
                            <?php
                            if (isset($_POST["tampilkan"]) &&  $_POST["KD_BHN_2"] == $KD_BHN_2) {
                                echo '<option value="' . $KD_BHN_2 . '" selected >' . $KD_BHN_2 . '</option>';
                            } ?>
                        </select>
                    </div>
                </div>
            </div> -->
            <div class="col-md-6">
                <div class="form-group row">
                    <div class="col-sm-2 nopadding">
                        <button class="btn btn-md btn-secondary" id="tampilkan" name="tampilkan"> Tampilkan </button>
                    </div>
                    <div class="dropdown col-sm-2 nopadding">
                        <button class="btn btn-outline secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-download"></i>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <button type="button" class="dropdown-item" id="btnExportCopy">
                                <i class="fa fa-clone"></i> Copy
                            </button>
                            <button type="button" class="dropdown-item" id="btnExportExcel">
                                <i class="fa fa-file-excel-o"></i> Excel
                            </button>
                            <button type="button" class="dropdown-item" id="btnExportCsv">
                                <i class="fas fa-file-csv"></i> Csv
                            </button>
                            <button type="button" class="dropdown-item" id="btnExportPdf">
                                <i class="fa fa-file-pdf-o"></i> Pdf
                            </button>
                            <button class="dropdown-item" id="print" name="print" value="print">
                                <i class="fa fa-print"></i> Print
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <hr class="m-t-10">
            <!-- PASTE DIBAWAH INI -->
            <!-- DISINI BATAS AWAL KOOLREPORT-->
            <?php
            use \koolreport\datagrid\DataTables;
            ?>
            <div class="report-content">
                <?php
                DataTables::create(array(
                    "dataStore" => $master_barang,
                    "name" => "example",
                    "columns" => array(
                        "KD_BHN" => array(
                            "label" => "Kode Barang"
                        ),
                        "NA_BHN" => array(
                            "label" => "Nama Barang"
                        ),
                        "SATUAN" => array(
                            "label" => "Satuan"
                        )
                    ),
                    "cssClass" => array(
                        "table" => "table table-hover table-striped table-bordered compact",
                        "th" => "label-title",
                        "td" => "detail",
                        function ($row, $colName) {
                            if ($colName == "DEBET") {
                                return "text-right";
                            }
                            if ($colName == "KREDIT") {
                                return "text-right";
                            }
                        }
                    ),
                    "options" => array(
                        // "columnDefs"=>array(
                        //     array(
                        //         "width"=> 5, "targets"=>2
                        //     ),
                        // ),
                        "paging" => true,
                        "searching" => true,
                        "colReorder" => true,
                        "fixedHeader" => true,
                        "select" => true,
                        "showFooter" => true,
                        "showFooter" => "bottom",
                        "dom" => 'lfrtip', // B e dilangi
                        // "dom" => '<"row"<col-md-6"B><"col-md-6"f>> <"row"<"col-md-12"t>><"row"<"col-md-12">>',
                        "buttons" => array(
                            array(
                                "extend" => 'collection',
                                "text" => 'Export',
                                "buttons" => [
                                    'copy',
                                    'excel',
                                    'csv',
                                    'pdf',
                                    'print'
                                ],
                            ),
                        ),
                    )
                ));
                ?>
            </div>
            <!-- DISINI BATAS AKHIR KOOLREPORT-->
</section>
<script type="text/javascript">
    $(document).ready(function() {
        $("#btnExportCopy").on("click", function() {
            var table = $('#example').DataTable();
            table.button('.buttons-copy').trigger();
        });
        $("#btnExportExcel").on("click", function() {
            var table = $('#example').DataTable();
            table.button('.buttons-excel').trigger();
        });
        $("#btnExportPdf").on("click", function() {
            var table = $('#example').DataTable();
            table.button('.buttons-pdf').trigger();
        });
        $("#btnExportCsv").on("click", function() {
            var table = $('#example').DataTable();
            table.button('.buttons-csv').trigger();
        });
        $("#btnExportPrint").on("click", function() {
            var table = $('#example').DataTable();
            table.button('.buttons-print').trigger();
        });
        $('.date').mask('00-00-0000');
    });
</script>

<script>
    $(document).ready(function() {
        select_barang_1();
        select_barang_2();
    });

    function select_barang_1() {
        $('#KD_BHN_1').select2({
            ajax: {
                url: "<?= base_url('admin/laporan/getData_master_barang_1') ?>",
                dataType: "json",
                type: "post",
                delay: 250,
                data: function(params) {
                    return {
                        search: params.term,
                        page: params.page
                    }
                },
                processResults: function(data, params) {
                    params.page = params.page || 1;
                    return {
                        results: data.items,
                        pagination: {
                            more: data.total_count
                        }
                    };
                },
                cache: true
            },
            placeholder: 'Masukan Barang ...',
            minimumInputLength: 0,
            templateResult: format,
            templateSelection: formatSelection
        });
    }

    function select_barang_2() {
        $('#KD_BHN_2').select2({
            ajax: {
                url: "<?= base_url('admin/laporan/getData_master_barang_2') ?>",
                dataType: "json",
                type: "post",
                delay: 250,
                data: function(params) {
                    return {
                        search: params.term,
                        page: params.page
                    }
                },
                processResults: function(data, params) {
                    params.page = params.page || 1;
                    return {
                        results: data.items,
                        pagination: {
                            more: data.total_count
                        }
                    };
                },
                cache: true
            },
            placeholder: 'Masukan Barang ...',
            minimumInputLength: 0,
            templateResult: format,
            templateSelection: formatSelection
        });
    }

    function format(repo) {
        if (repo.loading) {
            return repo.text;
        }

        var $container = $(
            "<div class='select2-result-repository clearfix'>" +
            "<div class='select2-result-repository__title'></div>" +
            "</div>"
        );

        $container.find(".select2-result-repository__title").text(repo.text);
        return $container;
    }

    function formatSelection(repo) {
        return repo.text;
    }
</script>
<!-- Bootstrap core JavaScript-->
<script src="<?php echo base_url() ?>assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="<?php echo base_url() ?>assets/vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="<?php echo base_url() ?>assets/js/sb-admin-2.min.js"></script>
<script src="<?php echo base_url() ?>assets/js/autoNumeric.js"></script>
<script src="<?php echo base_url() ?>assets/js/autoNumeric.min.js"></script>

<!-- Page level plugins -->
<!-- <script src="<?php echo base_url() ?>assets/vendor/chart.js/Chart.min.js"></script> -->

<!-- Page level custom scripts -->
<!-- <script src="<?php echo base_url() ?>assets/js/demo/chart-area-demo.js"></script>
<script src="<?php echo base_url() ?>assets/js/demo/chart-pie-demo.js"></script> -->

<!-- <script src="<?php echo base_url() ?>assets/vendor/Excel-like-Bootstrap-Table-Sorting-Filtering-Plugin/dist/excel-bootstrap-table-filter-bundle.js"></script> -->

<script src="<?php echo base_url() ?>assets/vendor/datatables/jquery.dataTables.js"></script>
<script src="<?php echo base_url() ?>assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>

<script src="<?php echo base_url() ?>assets/vendor/datatables/RowGroup-1.1.1/js/dataTables.rowGroup.min.js"></script>
<!-- <script  src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> -->
<script src="<?php echo base_url() ?>assets/DataTables/Buttons-1.6.1/js/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url() ?>assets/DataTables/Buttons-1.6.1/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="<?php echo base_url() ?>assets/DataTables/Buttons-1.6.1/js/buttons.html5.min.js"></script>
<script src="<?php echo base_url() ?>assets/DataTables/Buttons-1.6.1/js/buttons.print.min.js"></script>
<script src="<?php echo base_url() ?>assets/DataTables/Scroller-2.0.1/js/dataTables.scroller.min.js"></script>
<script src="<?php echo base_url() ?>assets/DataTables/Scroller-2.0.1/js/scroller.bootstrap4.min.js"></script>


<script type="text/javascript">
  jQuery(function($) {
    $('body').on('click', '#selectall', function() {
      $('.singlechkbox').prop('checked', this.checked);
    });

    $('body').on('click', '.singlechkbox', function() {
      if ($(".singlechkbox").length == $(".singlechkbox:checked").length) {
        $("#selectall").prop("checked", "checked");
      } else {
        $("#selectall").removeAttr("checked");
      }

    });
  });
</script>

<script type="text/javascript">
  $('.dropdown-menu a.dropdown-toggle').on('click', function(e) {
    if (!$(this).next().hasClass('show')) {
      $(this).parents('.dropdown-menu').first().find('.show').removeClass("show");
    }
    var $subMenu = $(this).next(".dropdown-menu");
    $subMenu.toggleClass('show');


    $(this).parents('li.nav-item.dropdown.show').on('hidden.bs.dropdown', function(e) {
      $('.dropdown-submenu .show').removeClass("show");
    });


    return false;
  });
</script>

<script>
  $(".ronly").on('keydown paste', function(e) {
    e.preventDefault();
    e.currentTarget.blur();
  });
</script>

</body>

</html>
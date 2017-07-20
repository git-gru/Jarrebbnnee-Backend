/**
 * Created by kalpesh on 5/12/2015.
 */
function ajaxresponsiveDataTable(id_table,url,mdatarow,orderBy)
{
	orderBy=typeof orderBy != 'undefined'?orderBy:[[ 0, "desc" ]];
    if ($.fn.dataTable) {
        // Set default options
        $.extend(true, $.fn.dataTable.defaults, {
            "oLanguage": {
                "sSearch": ""
            },
            "sDom": "<'row'<'dataTables_header clearfix'<'col-md-6'l><'col-md-6'f>r>>t<'row'<'dataTables_footer clearfix'<'col-md-6'i><'col-md-6'p>>>",
            // set the initial value
            "iDisplayLength": 10,
            "processing": true,
            "serverSide": true,
            "aoColumns": mdatarow,
			 "order": orderBy,
            "ajax": url,
            fnDrawCallback: function () {
                if ($.fn.uniform) {
                    $(':radio.uniform, :checkbox.uniform').uniform();
                }

                // SEARCH - Add the placeholder for Search and Turn this into in-line formcontrol
                var search_input = $(this).closest('.dataTables_wrapper').find('div[id$=_filter] input');

                // Only apply settings once
                if (search_input.parent().hasClass('input-group')) return;

                //search_input.attr('placeholder', 'Search')
                search_input.addClass('form-control')
                search_input.wrap('<div class="input-group"></div>');
                search_input.parent().prepend('<span class="input-group-addon"><i class="icon-search"></i></span>');
                //search_input.parent().prepend('<span class="input-group-addon"><i class="icon-search"></i></span>').css('width', '250px');

                // Responsive
                /*if (typeof responsiveHelper != 'undefined') {
                 responsiveHelper.respond();
                 }*/
            }
        });

        $.fn.dataTable.defaults.aLengthMenu = [[5, 10, 25, 50], [5, 10, 25, 50]];

        // Initialize default datatables
        $('#'+id_table).each(function () {
            var self = $(this);
            var options = {};
//alert(self.attr('data-data-pagination'))
            // Checkable Tables
            if (self.hasClass('table-checkable')) {
                $.extend(true, options, {
                    'aoColumnDefs': [
                        { 'bSortable': false, 'aTargets': [0] }
                    ]
                });
            }

            if (self.hasClass('iDisplayLength')) {
                $.extend(true, options, {
                    'iDisplayLength': self.attr('data-pagination')
                });
            }

            // TableTools
            if (self.hasClass('table-tabletools')) {
                $.extend(true, options, {
                    "sDom": "<'row'<'dataTables_header clearfix'<'col-md-4'l><'col-md-8'Tf>r>>t<'row'<'dataTables_footer clearfix'<'col-md-6'i><'col-md-6'p>>>", // T is new
                    "oTableTools": {
                        "aButtons": [
                            "copy",
                            "print",
                            "csv",
                            "xls",
                            "pdf"
                        ],
                        "sSwfPath": "../global/js/datatables/extras/tabletools/media/swf/copy_csv_xls_pdf.swf"
                    }
                });
            }

            // ColVis
            if (self.hasClass('table-colvis')) {
                $.extend(true, options, {
                    "sDom": "<'row'<'dataTables_header clearfix'<'col-md-6'l><'col-md-6'Cf>r>>t<'row'<'dataTables_footer clearfix'<'col-md-6'i><'col-md-6'p>>>", // C is new
                    "oColVis": {
                        "buttonText": "Columns <i class='icon-angle-down'></i>",
                        "iOverlayFade": 0
                    }
                });
            }

            // If ColVis is used with checkable Tables
            if (self.hasClass('table-checkable') && self.hasClass('table-colvis')) {
                $.extend(true, options, {
                    "oColVis": {
                        "aiExclude": [0]
                    }
                });
            }

            // Responsive Tables
            if (self.hasClass('table-responsive')) {
                var responsiveHelper;
                var breakpointDefinition = {
                    tablet: 1024,
                    phone: 480
                };

                // Preserve old function from $.extend above
                // to extend a function
                var old_fnDrawCallback = $.fn.dataTable.defaults.fnDrawCallback;

                $.extend(true, options, {
                    bAutoWidth: false,
                    fnPreDrawCallback: function () {
                        // Initialize the responsive datatables helper once.
                        if (!responsiveHelper) {
                            responsiveHelper = new ResponsiveDatatablesHelper(this, breakpointDefinition);
                        }
                    },
                    fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                        responsiveHelper.createExpandIcon(nRow);
                    },
                    fnDrawCallback: function (oSettings) {
                        // Extending function
                        old_fnDrawCallback.apply(this, oSettings);

                        responsiveHelper.respond();
                    }
                });
            }

            $(this).dataTable(options);
        });
    }
    $('#'+id_table).each(function(){
        var datatable = $(this);
        // SEARCH - Add the placeholder for Search and Turn this into in-line form control
        var search_input = datatable.closest('.dataTables_wrapper').find('div[id$=_filter] input');
        search_input.attr('placeholder', 'Search');
        search_input.addClass('form-control input-sm');
        // LENGTH - Inline-Form control
        var length_sel = datatable.closest('.dataTables_wrapper').find('div[id$=_length] select');
        length_sel.addClass('form-control input-sm');
    });
}


function responsiveDataTable(id_table)
{
	orderBy=typeof orderBy != 'undefined'?orderBy:[[ 0, "desc" ]];
    if ($.fn.dataTable) {
        // Set default options
        $.extend(true, $.fn.dataTable.defaults, {
            "oLanguage": {
                "sSearch": ""
            },
            "sDom": "<'row'<'dataTables_header clearfix'<'col-md-6'l><'col-md-6'f>r>>t<'row'<'dataTables_footer clearfix'<'col-md-6'i><'col-md-6'p>>>",
            // set the initial value
            "iDisplayLength": 10,
            fnDrawCallback: function () {
                if ($.fn.uniform) {
                    $(':radio.uniform, :checkbox.uniform').uniform();
                }

                // SEARCH - Add the placeholder for Search and Turn this into in-line formcontrol
                var search_input = $(this).closest('.dataTables_wrapper').find('div[id$=_filter] input');

                // Only apply settings once
                if (search_input.parent().hasClass('input-group')) return;

                //search_input.attr('placeholder', 'Search')
                search_input.addClass('form-control')
                search_input.wrap('<div class="input-group"></div>');
                search_input.parent().prepend('<span class="input-group-addon"><i class="icon-search"></i></span>');
                //search_input.parent().prepend('<span class="input-group-addon"><i class="icon-search"></i></span>').css('width', '250px');

                // Responsive
                /*if (typeof responsiveHelper != 'undefined') {
                 responsiveHelper.respond();
                 }*/
            }
        });

        $.fn.dataTable.defaults.aLengthMenu = [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]];

        // Initialize default datatables
        $('#'+id_table).each(function () {
            var self = $(this);
            var options = {};
//alert(self.attr('data-data-pagination'))
            // Checkable Tables
            if (self.hasClass('table-checkable')) {
                $.extend(true, options, {
                    'aoColumnDefs': [
                        { 'bSortable': false, 'aTargets': [0] }
                    ]
                });
            }

            if (self.hasClass('iDisplayLength')) {
                $.extend(true, options, {
                    'iDisplayLength': self.attr('data-pagination')
                });
            }

            // TableTools
            if (self.hasClass('table-tabletools')) {
                $.extend(true, options, {
                    "sDom": "<'row'<'dataTables_header clearfix'<'col-md-4'l><'col-md-8'Tf>r>>t<'row'<'dataTables_footer clearfix'<'col-md-6'i><'col-md-6'p>>>", // T is new
                    "oTableTools": {
                        "aButtons": [
                            "copy",
                            "print",
                            "csv",
                            "xls",
                            "pdf"
                        ],
                        "sSwfPath": "../global/js/datatables/extras/tabletools/media/swf/copy_csv_xls_pdf.swf"
                    }
                });
            }

            // ColVis
            if (self.hasClass('table-colvis')) {
                $.extend(true, options, {
                    "sDom": "<'row'<'dataTables_header clearfix'<'col-md-6'l><'col-md-6'Cf>r>>t<'row'<'dataTables_footer clearfix'<'col-md-6'i><'col-md-6'p>>>", // C is new
                    "oColVis": {
                        "buttonText": "Columns <i class='icon-angle-down'></i>",
                        "iOverlayFade": 0
                    }
                });
            }

            // If ColVis is used with checkable Tables
            if (self.hasClass('table-checkable') && self.hasClass('table-colvis')) {
                $.extend(true, options, {
                    "oColVis": {
                        "aiExclude": [0]
                    }
                });
            }

            // Responsive Tables
            if (self.hasClass('table-responsive')) {
                var responsiveHelper;
                var breakpointDefinition = {
                    tablet: 1024,
                    phone: 480
                };

                // Preserve old function from $.extend above
                // to extend a function
                var old_fnDrawCallback = $.fn.dataTable.defaults.fnDrawCallback;

                $.extend(true, options, {
                    bAutoWidth: false,
                    fnPreDrawCallback: function () {
                        // Initialize the responsive datatables helper once.
                        if (!responsiveHelper) {
                            responsiveHelper = new ResponsiveDatatablesHelper(this, breakpointDefinition);
                        }
                    },
                    fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                        responsiveHelper.createExpandIcon(nRow);
                    },
                    fnDrawCallback: function (oSettings) {
                        // Extending function
                        old_fnDrawCallback.apply(this, oSettings);

                        responsiveHelper.respond();
                    }
                });
            }

            $(this).dataTable(options);
        });
    }
    $('#'+id_table).each(function(){
        var datatable = $(this);
        // SEARCH - Add the placeholder for Search and Turn this into in-line form control
        var search_input = datatable.closest('.dataTables_wrapper').find('div[id$=_filter] input');
        search_input.attr('placeholder', 'Search');
        search_input.addClass('form-control input-sm');
        // LENGTH - Inline-Form control
        var length_sel = datatable.closest('.dataTables_wrapper').find('div[id$=_length] select');
        length_sel.addClass('form-control input-sm');
    });
	
	return $(this);
}

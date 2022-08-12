var coordenadas;

$(function () {

  'use strict';

  var console = window.console || { log: function () {} },
      $alert = $('.docs-alert'),
      $message = $alert.find('.message'),
      showMessage = function (message, type) {
        $message.text(message);

        if (type) {
          $message.addClass(type);
        }

        $alert.fadeIn();

        setTimeout(function () {
          $alert.fadeOut();
        }, 3000);
      };

  // Demo
  // -------------------------------------------------------------------------

  (function () {
    var $image = $('#img-container > img'),
        $dataX = $('#dataX'),
        $dataY = $('#dataY'),
        $dataHeight = $('#dataHeight'),
        $dataWidth = $('#dataWidth'),
        $dataRotate = $('#dataRotate'),
        options = {
           strict: false,
           responsive: false,
           checkImageOrigin: false,

           modal: true,
           guides: true,
           highlight: true,
           background: false,

           autoCrop: true,
           //autoCropArea: 0.5,
           dragCrop: true,
          movable: true,
           resizable: true,
           rotatable: true,
           zoomable: true,
            touchDragZoom: true,
          mouseWheelZoom: true,

          //minCanvasWidth: 320,
         // minCanvasHeight: 180,
          // minCropBoxWidth: 160,
          // minCropBoxHeight: 90,
          // minContainerWidth: 320,
          // minContainerHeight: 180,

          build: null,
          built: null,
          dragstart: null,
          dragmove: null,
          dragend: null,
          zoomin: null,
          zoomout: null, 

          aspectRatio: $("#aspectRatioW").val() / $("#aspectRatioH").val(),
          preview: '.img-preview',
          crop: function (data) {
            $dataX.val(Math.round(data.x));
            $dataY.val(Math.round(data.y));
            $dataHeight.val(Math.round(data.height)); 
            $dataWidth.val(Math.round(data.width));
            $dataRotate.val(Math.round(data.rotate));
          }
        };

    $image.on({
      'build.cropper': function (e) {
        // console.log(e.type);
      },
      'built.cropper': function (e) {
        // console.log(e.type);

        coordenadas = $("#img-container>img").cropper('getData');
        coordenadas = JSON.stringify(coordenadas, null, 4);
        $("#coordenadas").val(coordenadas);
      },
      'dragstart.cropper': function (e) {
        // console.log(e.type, e.dragType);
      },
      'dragmove.cropper': function (e) {
        // console.log(e.type, e.dragType);
      },
      'dragend.cropper': function (e) {
        // console.log(e.type, e.dragType);

        coordenadas = $("#img-container>img").cropper('getData');
        coordenadas = JSON.stringify(coordenadas, null, 4);
        $("#coordenadas").val(coordenadas);
      },
      'zoomin.cropper': function (e) {
        // console.log(e.type);

        coordenadas = $("#img-container>img").cropper('getData');
        coordenadas = JSON.stringify(coordenadas, null, 4);
        $("#coordenadas").val(coordenadas);
      },
      'zoomout.cropper': function (e) {
        // console.log(e.type);

        coordenadas = $("#img-container>img").cropper('getData');
        coordenadas = JSON.stringify(coordenadas, null, 4);
        $("#coordenadas").val(coordenadas);
      }
    }).cropper(options);


    // Methods
    $(document.body).on('click', '[data-method]', function () {
      var data = $(this).data(),
          $target,
          result;

      if (data.method) {
        data = $.extend({}, data); // Clone a new one

        if (typeof data.target !== 'undefined') {
          $target = $(data.target);

          if (typeof data.option === 'undefined') {
            try {
              data.option = JSON.parse($target.val());
            } catch (e) {
              console.log(e.message);
            }
          }
        }

        result = $image.cropper(data.method, data.option);

        if (data.method === 'getCroppedCanvas') {
          $('#getCroppedCanvasModal').modal().find('.modal-body').html(result);
        }

        if ($.isPlainObject(result) && $target) {
          try {
            $target.val(JSON.stringify(result));
          } catch (e) {
            console.log(e.message);
          }
        }

      }
    }).on('keydown', function (e) {

      // switch (e.which) {
      //   case 37:
      //     e.preventDefault();
      //     $image.cropper('move', -1, 0);
      //     break;

      //   case 38:
      //     e.preventDefault();
      //     $image.cropper('move', 0, -1);
      //     break;

      //   case 39:
      //     e.preventDefault();
      //     $image.cropper('move', 1, 0);
      //     break;

      //   case 40:
      //     e.preventDefault();
      //     $image.cropper('move', 0, 1);
      //     break;
      // }

    });


    // Import image
    var $inputImage = $(".cropped-image"),
        URL = window.URL || window.webkitURL,
        blobURL;

    if (URL) {
        $inputImage.change(function () {
            var files = this.files,
                file;

            if (files && files.length) {
                file = files[0];
                
                if (/^image\/\w+$/.test(file.type)) {
                  blobURL = URL.createObjectURL(file);
                  $image.one('built.cropper', function () {
                    URL.revokeObjectURL(blobURL); // Revoke when load complete
                  }).cropper('reset', true).cropper('replace', blobURL);

                  $("#img-container").show();
                  
                  //$inputImage.val('');
                } else {
                  showMessage('Please choose an image file.');
                }
            }
        });
    } else {
      $inputImage.parent().remove();
    }


    // Options
    $('.docs-options :checkbox').on('change', function () {
      var $this = $(this);

      options[$this.val()] = $this.prop('checked');
      $image.cropper('destroy').cropper(options);
    });


    // Tooltips
    $('[data-toggle="tooltip"]').tooltip();

  }());

});

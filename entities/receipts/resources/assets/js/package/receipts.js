let receipts = {};

receipts.init = function() {
  $(document).ready(function() {
    $('.wrapper-content').on('click', '.show-receipts', function() {
      let url = $(this).attr('data-url');

      $.ajax({
        url: url,
        method: 'GET',
        dataType: 'html',
        success: function(data) {
          $('#receipts_modal .modal-body').html(data);

          $('#receipts_modal').modal();
        },
      });
    });
  });
};

module.exports = receipts;

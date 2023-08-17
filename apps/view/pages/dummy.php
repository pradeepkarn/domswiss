<section>
      <div class="container-fluid px-4">
        <div class="row">
          <div class="col-lg-12 col-md-12">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">
                  <font style="vertical-align: inherit;">
                    <font style="vertical-align: inherit;">Referral Links</font>
                  </font>
                </h4>
                <hr>


                <div class="form-group row">
                  <div class="col-6">
                    <label class="control-label">
                      <font style="vertical-align: inherit;">
                        <font style="vertical-align: inherit;">To partner</font>
                      </font>
                    </label>
                    <div class="input-group copyarea">
                      <input type="text" class="form-control" value="http:/<?php echo home; ?>/signup/?sponserid=<?php echo $_SESSION['user_id']; ?>" readonly="">
                      <div class="input-group-append">
                        <span onclick="copyToClipboard('#p1')" class="input-group-text1"><i class="far fa-copy"></i></span>
                      </div>
                    </div>
                  </div>
                  <div class="col-6">
                    <label class="control-label">
                      <font style="vertical-align: inherit;">
                        <font style="vertical-align: inherit;">To customers</font>
                      </font>
                    </label>
                    <div class="input-group copyarea">
                      <input type="text" class="form-control" value="https://domswiss.at?sponsor=jyoti" readonly="">
                      <div class="input-group-append">
                        <span onclick="copyToClipboard('#p2')" class="input-group-text1"><i class="far fa-copy"></i></span>
                      </div>
                    </div>
                  </div>
                </div>

                <script>
                  $('.copyarea').click(function() {
                    var copyText = $(this).children('input');
                    copyText.select();
                    document.execCommand("copy");
                    $.toast({
                      heading: 'Erledigt!',
                      text: 'Sie haben den Link erfolgreich in die Zwischenablage kopiert!',
                      position: 'top-right',
                      loaderBg: '#00ab81',
                      icon: 'success',
                      hideAfter: 3500,
                      stack: 6
                    });
                  });

                  function copyToClipboard(element) {
                    var $temp = $("<input>");
                    $("body").append($temp);
                    $temp.val($(element).text()).select();
                    document.execCommand("copy");
                    $temp.remove();
                  }
                </script>

              </div>
            </div>
          </div>
        </div>
      </div>

    </section>
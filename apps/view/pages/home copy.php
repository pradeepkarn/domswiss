<?php
import("apps/view/inc/header.php");
import("apps/view/inc/navbar.php");
$tree_data = file_get_contents(RPATH . "/jsondata/trees/tree_" . USER['id'] . '.json');
// echo $json_data;
$td = json_decode($tree_data, true);
$myarr = [];

// myprint(active_member($td,1));
echo <<<SCRPT
  <script>
    let treeData = {}
    treeData = $tree_data
  </script>
SCRPT;
?>
<div id="layoutSidenav">
  <?php import("apps/view/inc/sidebar.php"); ?>
  <div id="layoutSidenav_content">
    <main>
      <div class="container-fluid px-4">
        <h1 class="mt-4">Dashboard</h1>
        <ol class="breadcrumb mb-4 mypop">
          <li class="breadcrumb-item active">Dashboard</li>
        </ol>

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

                        <?php
                        $my_username = null;

                        if (isset($_SESSION['user_id'])) {
                          $userid = $_SESSION['user_id'];
                          $invite = getData("pk_user", $userid);
                          $my_username = $invite != false ? $invite['username'] : null;
                        }
                        ?>

                        <?php
                        if (authenticate() == true) {
                          // $acnt = new Account;
                          // $user = $acnt->getLoggedInAccount();


                          // echo js_alert(days_left());

                          $userObj = new Model('pk_user');

                          $arr = null;
                          $arr['ref'] = $_SESSION['user_id'];
                          $partner = $userObj->filter_index($assoc_arr = $arr, $ord = 'DESC', $limit = 999,              $change_order_by_col = "");
                        }


                        ?>


                        <div class="input-group copyarea">
                          <input type="text" class="form-control" value="https:/<?php echo home; ?>/signup/?sponserid=<?php echo $my_username; ?>" readonly="">
                          <div class="input-group-append">
                            <span onclick="copyToClipboard('#p1')" class="input-group-text1 pointer"><i class="far fa-copy"></i></span>
                          </div>
                        </div>


                      </div>
                      <!-- <div class="col-6">
                        <label class="control-label">
                          <font style="vertical-align: inherit;">
                            <font style="vertical-align: inherit;">To customers</font>
                          </font>
                        </label>
                        <div class="input-group copyarea">
                          <input type="text" class="form-control" value="https://domswiss.at?sponsor=jyoti" readonly="">
                          <div class="input-group-append">
                            <span onclick="copyToClipboard('#p2')" class="input-group-text1"><i class="far fa-copy pointer"></i></span>
                          </div>
                        </div>
                      </div> -->
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
                      // $('.copyarea').click(function() {
                      //   var copyText = $(this).children('input').get(0);
                      //   navigator.clipboard.writeText(copyText.value)
                      //     .then(function() {
                      //       $.toast({
                      //         heading: 'Erledigt!',
                      //         text: 'Link copied!',
                      //         position: 'top-right',
                      //         loaderBg: '#00ab81',
                      //         icon: 'success',
                      //         hideAfter: 3500,
                      //         stack: 6
                      //       });
                      //     })
                      //     .catch(function(error) {
                      //       console.error('Failed to copy: ', error);
                      //     });
                      // });
                    </script>

                  </div>
                </div>
              </div>
            </div>
            <div class="row mt-3">
              <div class="col">
                <h5><b>Active Days Left: </b><?php 
                $date = last_active_date($user_id=$_SESSION['user_id']);
                $days_left = days_left($date);
                if ($days_left<=0) {
                  echo "Your account was expired before ". abs($days_left). "days";
                }else{
                  echo $days_left. " active days left";
                }
                 ?></h5>
              </div>
            </div>



          </div>

        </section>
        <section>
          <div class="container">
            <div class="row">
              <div class="col-md-12">
                <?php
                if (authenticate() == true) {
                  $date = last_active_date($user_id=$_SESSION['user_id']);
                  $tree  = my_tree($ref = $_SESSION['user_id'],1,$date);
                  $depth = 1;
                  $treeLength = count($tree);
                  $calc = calculatePercentageSum($data = $tree, $depth, $treeLength,$_SESSION['user_id']);
                  $sum = $calc['sum'];
                  $rv_sum = $calc['rv_sum']+my_rv_and_admin_rv($user_id=$_SESSION['user_id'],$dbobj=null);
                  $jsonData = json_encode($tree, JSON_PRETTY_PRINT);
                  $file = "jsondata/trees/tree_" . USER['id'] . '.json';
                  file_put_contents($file, $jsonData);
                  $db = new Model('credits');
                  $crarr['user_id'] = USER['id'];
                  $crarr['status'] = 'lifetime';
                  $already = $db->filter_index($crarr);
                  if (count($already) > 0) {
                    $crid = obj($already[0]);
                    $crarr['amt'] = $sum;
                    $db->update($id = $crid->id, $crarr);
                  } else {
                    $crarr['amt'] = $sum;
                    $db->store($crarr);
                  }
                }
                ?>
              </div>
            </div>
            <div class="row">
              <div class="col text-center">
                <h5>Total Ring Commission: <?php echo "Sum of ring % of PV: " . round($sum, 2); ?> euros</h5>
                <div class="card">
                  <div class="card-body bg-primary text-white">
                    <h5>Position: <?php echo getPosition($level = $rv_sum); ?>
                      <br>
                      Current RV: <?php echo round($rv_sum); ?>
                    </h5>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">

              <canvas id="canvas"></canvas>
              <script>
                // Set up canvas
                const canvas = document.getElementById('canvas');
                const ctx = canvas.getContext('2d');

                // Function to set canvas size
                function setCanvasSize() {
                  canvas.width = window.innerWidth;
                  canvas.height = window.innerHeight;
                  drawRings(); // Redraw the rings when canvas size changes
                }

                // Function to draw the rings with username
                function drawRings() {
                  // Clear canvas
                  ctx.clearRect(0, 0, canvas.width, canvas.height);

                  // Calculate center coordinates based on canvas size
                  const centerX = canvas.width / 2;
                  const centerY = canvas.height / 2;

                  // Define the rings
                  const numRings = 10;
                  const ringRadius = 10;
                  const ringSpacing = 30;
                  const username = 'You';

                  ctx.beginPath();
                  ctx.arc(centerX, centerY, 4 * ringRadius, 0, 2 * Math.PI);
                  ctx.fillStyle = 'blue';
                  ctx.fill();

                  // Set font style
                  ctx.font = '16px Arial';
                  ctx.fillStyle = '#FFFFFF'; // Set the fill style to white color
                  ctx.textAlign = 'center';

                  // Draw the rings
                  for (let i = 0; i <= numRings; i++) {
                    const currentRadius = ringRadius + (i + 1) * ringSpacing;
                    const mbobj = active_member(treeData, i)
                    const totalMember = mbobj.active + mbobj.inactive
                    // Draw the ring
                    ctx.beginPath();
                    ctx.arc(centerX, centerY, currentRadius, 0, 2 * Math.PI);
                    ctx.strokeStyle = 'blue';
                    ctx.lineWidth = 2;
                    ctx.stroke();

                    // Draw the username or data in the middle of the ring
                    if (i == 0) {
                      ctx.fillStyle = '#FFFFFF'; // Set the fill style to white color
                      ctx.fillText(username, centerX, centerY + 5);
                    } else {
                      ctx.fillStyle = '#000000'; // Set the fill style back to black color
                      ctx.fillText(`${totalMember}/${mbobj.active}`, centerX, centerY + 30 + 30 * i);
                    }
                  }
                }
                // Call the function to set initial canvas size
                setCanvasSize();

                // Call the function when the window is resized
                window.addEventListener('resize', setCanvasSize);

                // console.log(treeData);

                function active_member(data, ringNumber) {
                  let activeCount = 0;
                  let inactiveCount = 0;

                  for (let user of data) {
                    if (user.ring === ringNumber) {
                      if (user.is_active) {
                        activeCount++;
                      } else {
                        inactiveCount++;
                      }
                    }

                    if (user.tree && user.tree.length > 0) {
                      const nestedCounts = active_member(user.tree, ringNumber);
                      activeCount += nestedCounts.active;
                      inactiveCount += nestedCounts.inactive;
                    }
                  }

                  return {
                    active: activeCount,
                    inactive: inactiveCount
                  };
                }
              </script>

            </div>
          </div>
        </section>
      </div>



    </main>



    <?php import("apps/view/inc/footer-credit.php"); ?>
  </div>
</div>



<?php

import("apps/view/inc/footer.php");

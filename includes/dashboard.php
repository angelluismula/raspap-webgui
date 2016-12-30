<?php

/**
*
*
*/
function DisplayDashboard(){

  $status = new StatusMessages();

  exec( 'ifconfig wlan1', $return );
  exec( 'iwconfig wlan1', $return );

  $strwlan1 = implode( " ", $return );
  $strwlan1 = preg_replace( '/\s\s+/', ' ', $strwlan1 );

  // Parse results from ifconfig/iwconfig
  preg_match( '/HWaddr ([0-9a-f:]+)/i',$strwlan1,$result );
  $strHWAddress = $result[1];
  preg_match( '/inet addr:([0-9.]+)/i',$strwlan1,$result );
  $strIPAddress = $result[1];
  preg_match( '/Mask:([0-9.]+)/i',$strwlan1,$result );
  $strNetMask = $result[1];
  preg_match( '/RX packets:(\d+)/',$strwlan1,$result );
  $strRxPackets = $result[1];
  preg_match( '/TX packets:(\d+)/',$strwlan1,$result );
  $strTxPackets = $result[1];
  preg_match( '/RX bytes:(\d+ \(\d+.\d+ [K|M|G]iB\))/i',$strwlan1,$result );
  $strRxBytes = $result[1];
  preg_match( '/TX Bytes:(\d+ \(\d+.\d+ [K|M|G]iB\))/i',$strwlan1,$result );
  $strTxBytes = $result[1];
  preg_match( '/ESSID:\"([a-zA-Z0-9\s]+)\"/i',$strwlan1,$result );
  $strSSID = str_replace( '"','',$result[1] );
  preg_match( '/Access Point: ([0-9a-f:]+)/i',$strwlan1,$result );
  $strBSSID = $result[1];
  preg_match( '/Bit Rate=([0-9\.]+ Mb\/s)/i',$strwlan1,$result );
  $strBitrate = $result[1];
  preg_match( '/Tx-Power=([0-9]+ dBm)/i',$strwlan1,$result );
  $strTxPower = $result[1];
  preg_match( '/Link Quality=([0-9]+)/i',$strwlan1,$result );
  $strLinkQuality = $result[1];
  preg_match( '/Signal level=(-?[0-9]+ dBm)/i',$strwlan1,$result );
  $strSignalLevel = $result[1];
  preg_match('/Frequency:(\d+.\d+ GHz)/i',$strwlan1,$result);
  $strFrequency = $result[1];

  if(strpos( $strwlan1, "UP" ) !== false && strpos( $strwlan1, "RUNNING" ) !== false ) {
    $status->addMessage('Interface is up', 'success');
    $wlan1up = true;
  } else {
    $status->addMessage('Interface is down', 'warning');
  }

  if( isset($_POST['ifdown_wlan1']) ) {
    exec( 'ifconfig wlan1 | grep -i running | wc -l',$test );
    if($test[0] == 1) {
      exec( 'sudo ifdown wlan1',$return );
    } else {
      echo 'Interface already down';
    }
  } elseif( isset($_POST['ifup_wlan1']) ) {
    exec( 'ifconfig wlan1 | grep -i running | wc -l',$test );
    if($test[0] == 0) {
      exec( 'sudo ifup wlan1',$return );
    } else {
      echo 'Interface already up';
    }
  }
  ?>
  <div class="row">
      <div class="col-lg-12">
          <div class="panel panel-primary">
            <div class="panel-heading"><i class="fa fa-dashboard fa-fw"></i> Dashboard   </div>
              <div class="panel-body">
                <p><?php $status->showMessages(); ?></p>
                  <div class="row">
                        <div class="col-md-6">
                        <div class="panel panel-default">
                  <div class="panel-body">
                      <h4>Interface Information</h4>
          <div class="info-item">Interface Name</div> wlan1</br>
          <div class="info-item">IP Address</div>     <?php echo $strIPAddress ?></br>
          <div class="info-item">Subnet Mask</div>    <?php echo $strNetMask ?></br>
          <div class="info-item">Mac Address</div>    <?php echo $strHWAddress ?></br></br>

                      <h4>Interface Statistics</h4>
          <div class="info-item">Received Packets</div>    <?php echo $strRxPackets ?></br>
          <div class="info-item">Received Bytes</div>      <?php echo $strRxBytes ?></br></br>
          <div class="info-item">Transferred Packets</div> <?php echo $strTxPackets ?></br>
          <div class="info-item">Transferred Bytes</div>   <?php echo $strTxBytes ?></br>
        </div><!-- /.panel-body -->
        </div><!-- /.panel-default -->
                        </div><!-- /.col-md-6 -->

        <div class="col-md-6">
                    <div class="panel panel-default">
              <div class="panel-body wireless">
                            <h4>Wireless Information</h4>
          <div class="info-item">Connected To</div>   <?php echo $strSSID ?></br>
          <div class="info-item">AP Mac Address</div> <?php echo $strBSSID ?></br>
          <div class="info-item">Bitrate</div>        <?php echo $strBitrate ?></br>
          <div class="info-item">Signal Level</div>        <?php echo $strSignalLevel ?></br>
          <div class="info-item">Transmit Power</div> <?php echo $strTxPower ?></br>
          <div class="info-item">Frequency</div>      <?php echo $strFrequency ?></br></br>
          <div class="info-item">Link Quality</div>
            <div class="progress">
            <div class="progress-bar progress-bar-info progress-bar-striped active"
              role="progressbar"
              aria-valuenow="<?php echo $strLinkQuality ?>" aria-valuemin="0" aria-valuemax="100"
              style="width: <?php echo $strLinkQuality ?>%;"><?php echo $strLinkQuality ?>%
            </div>
          </div>
        </div><!-- /.panel-body -->
        </div><!-- /.panel-default -->
                        </div><!-- /.col-md-6 -->
      </div><!-- /.row -->

                  <div class="col-lg-12">
                 <div class="row">
                    <form action="?page=wlan1_info" method="POST">
                    <?php if ( !$wlan1up ) {
                      echo '<input type="submit" class="btn btn-success" value="Start wlan1" name="ifup_wlan1" />';
                    } else {
                echo '<input type="submit" class="btn btn-warning" value="Stop wlan1" name="ifdown_wlan1" />';
              }
              ?>
              <input type="button" class="btn btn-outline btn-primary" value="Refresh" onclick="document.location.reload(true)" />
              </form>
            </div>
              </div>

                </div><!-- /.panel-body -->
                <div class="panel-footer">Information provided by ifconfig and iwconfig</div>
            </div><!-- /.panel-default -->
        </div><!-- /.col-lg-12 -->
    </div><!-- /.row -->
  <?php 
}

?>

<?php
  require_once('./Scripts/db_login.php');

  $ussps_year_start_from = 2009;
  $images_year_start_from = 2012;

?>

<?php
  // ----------------------------------------------
  // | The Database Connection is performed using |
  // | $connection variable!!!                    |
  // ----------------------------------------------
  function get_last_image_date($connection, $img_type){

    $sql = "SELECT date_obs FROM $img_type
            WHERE quality < 2
            ORDER BY date_obs DESC LIMIT 1";
    //echo "$sql <br/>";

    if($result =	mysqli_query($connection, $sql)){

        while ($row = mysqli_fetch_row($result)){

            $img_date = date_create($row[0]);

        }

        return $img_date;

        mysqli_free_result($result);
    }

  }
?>

<?php
  function get_image_path($img_date, $img_type, $img_ext, $grid){

    if($img_ext == "jpeg"){
      $folder_root = "./data/jpeg";
      $filename_extension = ".jpg";
    }
    else { //$img_ext == "fits"
      $folder_root = "./data/fits";
      $filename_extension = ".fts";
    }

    $date_folder = date_format($img_date, "/Y/m/d");

    // check the right image type to compose
    // the filename header

    if ($img_type == "HAlpha") {
      $filename_header = "/oact_halph_fi_";
    }
    elseif ($img_type == "Continuum"){
      $filename_header = "/oact_hared_fi_";
    }
    else // any other: "halph_fd", "halph_fc", "halph_ff", etc
      $filename_header = "/oact_". $img_type ."_";

    $filename_date = date_format($img_date, "Ymd_His");

    if ($grid == "yes")
      $filename_grid = "_grid";
    else // $grid == "no"
      $filename_grid = "";

    $complete_path =  "\"".
                      $folder_root.
                      $date_folder.
                      $filename_header.
                      $filename_date.
                      $filename_grid. // SHOW GRID IMAGE !!!
                      $filename_extension.
                      "\"";

    return $complete_path;
  }
?>

<?php
  function publish_last_image($connection, $img_type){

    $img_date = get_last_image_date($connection, $img_type);
    $complete_path = get_image_path($img_date, $img_type, "jpeg", "yes");
    // the resulting string is similar to
    // "./data/jpeg/2016/04/05/oact_halph_fi_20160405_092100.fits.jpg" >
    ?>

    <table >
      <tr>
        <td valign="top" align="center">
          <?php
            if ($img_type=="HAlpha")
              echo "H&alpha; (656.28 nm)";
            else
              echo "Continuum (656.78 nm)";
          ?>
        </td>
      </tr>
      <tr>
        <td width="50%" valign="top" align="center" colspan=4>
          <a href = <?php echo $complete_path ?> target ="_blank" >
            <img src = <?php echo $complete_path ?> >
          </a>
        </td>
      </tr>
      <tr>
        <td valign="top" align="center">
          <?php echo date_format($img_date, "d F Y, H:i:s"). " UTC" ?>
        </td>
      </tr>
    </table>

<?php
  }
?>



<?php

   function list_images($connection, $query_date, $img_type, $quality){

     $date_from = "\"" .$query_date. " 00:00:00\"";
  //   echo $date_from;
     $date_to = "\"" .$query_date. " 23:59:59\"";
  //   echo $date_to;
  //-----------------
     $sql = "SELECT date_obs, quality, xposure FROM $img_type
             WHERE (date_obs BETWEEN $date_from AND $date_to)
             AND   (quality <= $quality)
             ORDER BY date_obs";
  //  row 0: date_obs
  //  row 1: quality
  //  row 2: xposure

     if($result =	mysqli_query($connection, $sql)){//, $resultmode = MYSQLI_STORE_RESULT);
       //$_SESSION['result'] = $result;
         echo "<b>" .mysqli_num_rows($result). " records found for " .date_format(date_create($query_date), "d F Y"). "</b>";
    ?>

    <table width=100%>
      <tr>
          <td width=20%> Date/Time </td>
          <td width=10%> Quality </td>
          <td> Exp. Time </td>
          <td> Link to FITS file </td>
          <td> Link to JPEG file </td>
      </tr>
      <tr>
          <td> [yyyy-mm-dd hh:mm:ss] </td>
          <td>1(good) - 3(bad) </td>
          <td> [s] </td>
          <td>  </td>
          <td>  </td>
      </tr>

      <?php
      $hour_list = "00";
    //  $index = 0;

      while ($row = mysqli_fetch_row($result)){

          $img_date = date_create($row[0]);

          if(date_format($img_date, "H") != $hour_list){
              $hour_list = date_format($img_date, "H");
      ?>

      <tr bgcolor="#E8E8E8">
          <td colspan="5"> <font color="#1565AA"><?php echo $hour_list ?>:00 UTC</font></td>
      </tr>

      <?php
          }
          //  row 0: date_obs
          $date_obs = $row[0];
          //  row 1: quality
          $quality = $row[1];
          //  row 2: xposure
          $xposure = $row[2];
      ?>

      <tr>
          <td> <?php echo $date_obs ?> </td>
          <td> <?php echo $quality ?> </td>
          <td> <?php echo $xposure ?> </td>
          <td>
            <?php // Publish FITS image
              $complete_path = get_image_path($img_date, $img_type, "fits", "no");
            ?>
            <a href=<?php echo $complete_path ?> download> Download FITS file </a>
          </td>
          <td>
            <?php // Publish JPEG image
              $complete_path = get_image_path($img_date, $img_type, "jpeg", "no");
            ?>
            <a href=<?php echo $complete_path ?> target ="_blank"> JPG image </a>
          </td>
        </tr>


       <?php
       //$index++;
       }


       mysqli_free_result($result);
       echo "</table>";
     }

 }
?>

<?php

   function list_all_images($connection, $query_date){

     $date_from = "\"" .$query_date. " 00:00:00\"";
  //   echo $date_from;
     $date_to = "\"" .$query_date. " 23:59:59\"";
  //   echo $date_to;
  //-----------------
     $sql = " SELECT * FROM
              (SELECT 'HAlpha' AS tb_name, date_obs, quality, xposure FROM HAlpha
               UNION
               SELECT 'Continuum' AS tb_name, date_obs, quality, xposure FROM Continuum) AS temp
              WHERE (date_obs BETWEEN $date_from AND $date_to)
              ORDER BY date_obs";

     //  row 0: tb_name
     //  row 1: date_obs
     //  row 2: quality
     //  row 3: xposure


     if($result =	mysqli_query($connection, $sql)){//, $resultmode = MYSQLI_STORE_RESULT);
       if(!mysqli_query($connection, "SET @a:='this will not work'")){
         printf("Error: %s\n", mysqli_error($connection));
       }
         echo "<b>" .mysqli_num_rows($result). " records found for " .date_format(date_create($query_date), "d F Y"). "</b>";
    ?>

    <table width=100%>
      <tr>
          <td> type </td>
          <td > Date/Time </td>
          <td > Quality </td>
          <td> Exp. Time </td>
          <td> Link to FITS file </td>
          <td> Link to JPEG file </td>
      </tr>
      <tr>
          <td> H&alpha; / Continuum </td>
          <td> [yyyy-mm-dd hh:mm:ss] </td>
          <td>1(good) - 3(bad) </td>
          <td> [s] </td>
          <td>  </td>
          <td>  </td>
      </tr>

      <?php
      $hour_list = "00";

      while ($row = mysqli_fetch_row($result)){

          $img_date = date_create($row[1]);

          if(date_format($img_date, "H") != $hour_list){
              $hour_list = date_format($img_date, "H");
      ?>

      <tr bgcolor="#E8E8E8">
          <td colspan="6"> <font color="#1565AA"><?php echo $hour_list ?>:00 UTC</font></td>
      </tr>

      <?php
          }

          //  row 0: tb_name
          $img_type = $row[0];
          //  row 1: date_obs
          $date_obs = $row[1];
          //  row 2: quality
          $img_quality = $row[2];
          //  row 3: xposure
          $xposure = $row[3];



      ?>

      <tr>
          <td> <?php  if ($img_type=="HAlpha")
                        echo "H&alpha;";
                      else
                        echo $img_type; ?> </td>
          <td> <?php echo $date_obs ?> </td>
          <td> <?php echo $img_quality ?> </td>
          <td> <?php echo $xposure ?> </td>
          <td>
            <?php // Publish FITS image
              $complete_path = get_image_path($img_date, $img_type, "fits", "no");
            ?>
            <a href=<?php echo $complete_path ?> download> Download FITS file </a>
          </td>
          <td>
            <?php // Publish JPEG image if quality == 1
              if($img_quality == "1"){
                $complete_path = get_image_path($img_date, $img_type, "jpeg", "yes");
                echo "<a href=". $complete_path ."> JPG image </a>";
              }
            ?>
          </td>
      </tr>


       <?php
       }

       mysqli_free_result($result);
       echo "</table>";
     }
 }
?>

<?php

   function list_calibration_images($connection, $query_date, $img_type){

     $date_from = "\"" .$query_date. " 00:00:00\"";
  //   echo $date_from;
     $date_to = "\"" .$query_date. " 23:59:59\"";
  //   echo $date_to;
  //-----------------
     $sql = "SELECT date_obs, img_type FROM $img_type
             WHERE (date_obs BETWEEN $date_from AND $date_to)
             AND ((img_type != 'halph_fr') AND (img_type !='halph_fp'))
             ORDER BY date_obs";
  //  row 0: date_obs
  //  row 1: img_type

     if($result =	mysqli_query($connection, $sql)){//, $resultmode = MYSQLI_STORE_RESULT);
       //$_SESSION['result'] = $result;
         echo "<b>" .mysqli_num_rows($result). " Calibration files found for " .date_format(date_create($query_date), "d F Y"). "</b>";
    ?>

    <table width=100%>
      <tr>
          <td width=25%> type </td>
          <td width=20%> Date/Time </td>
          <td> Link to FITS file </td>
      </tr>
      <tr>
          <td>  </td>
          <td> [yyyy-mm-dd hh:mm:ss] </td>
          <td>  </td>
      </tr>

      <?php

        while ($row = mysqli_fetch_row($result)){

          $img_date = date_create($row[0]);

          //  row 0: date_obs
          //  row 1: img_type

          $calib_type = $row[1]; // default value

          switch ($row[1]){

              case "halph_fd":
                  $calib_type = "Dark";
                  break;

              case "halph_fc":
                  $calib_type = "Shifted Image for Flat Field";
                  break;

              case "halph_ff":
                  $calib_type = "Flat Field";
                  break;

              case "halph_fl":
                  $calib_type = "Limb Darkening";
                  break;

          }
      ?>

      <tr>
          <td> <?php echo $calib_type ?> </td>
          <td> <?php echo $row[0] ?> </td>
          <td>
            <?php // Publish FITS image
              // pass $row[1] as argument for img_type to force the actual name of file
              // eg: "halph_fd", "halph_fc", "halph_ff", etc
              $complete_path = get_image_path($img_date, $row[1], "fits", "no");
            ?>
            <a href=<?php echo $complete_path ?> download> Download FITS file </a>
          </td>
        </tr>


       <?php
       //$index++;
       }


       mysqli_free_result($result);
       echo "</table>";
     }

 }
?>

<?php

   function list_images_after_calibration($connection, $query_date, $img_type){

     $date_from = "\"" .$query_date. " 00:00:00\"";
  //   echo $date_from;
     $date_to = "\"" .$query_date. " 23:59:59\"";
  //   echo $date_to;
  //-----------------
     $sql = "SELECT date_obs, img_type FROM $img_type
             WHERE (date_obs BETWEEN $date_from AND $date_to)
             AND ((img_type = 'halph_fr') OR (img_type ='halph_fp'))
             ORDER BY date_obs";
  //  row 0: date_obs
  //  row 1: img_type

     if($result =	mysqli_query($connection, $sql)){

       if(mysqli_num_rows($result) > 0){
         echo "<b>" .mysqli_num_rows($result). " After Calibration files found for " .date_format(date_create($query_date), "d F Y"). "</b>";
      ?>

    <table width=100%>
      <tr>
          <td width=25%> type </td>
          <td width=20%> Date/Time </td>
          <td> Link to FITS file </td>
      </tr>
      <tr>
          <td>  </td>
          <td> [yyyy-mm-dd hh:mm:ss] </td>
          <td>  </td>
      </tr>

      <?php


        while ($row = mysqli_fetch_row($result)){

          $img_date = date_create($row[0]);


          //  row 0: date_obs
          //  row 1: img_type

          $calib_type = $row[1]; // default value

          switch ($row[1]){

              case "halph_fr":
                  $calib_type = "H&alpha; (fr)";
                  break;

              case "halph_fp":
                  $calib_type = "H&alpha; (fp)";
                  break;
          }
      ?>

      <tr>
          <td> <?php echo $calib_type ?> </td>
          <td> <?php echo $row[0] ?> </td>
          <td>
            <?php // Publish FITS image
              // pass $row[1] as argument for img_type to force the actual name of file
              // eg: "halph_fd", "halph_fc", "halph_ff", etc
              $complete_path = get_image_path($img_date, $row[1], "fits", "no");
            ?>
            <a href=<?php echo $complete_path ?> download> Download FITS file </a>
          </td>
        </tr>


       <?php
       //$index++;
       }


       mysqli_free_result($result);
       echo "</table>";
      }
    }

 }
?>




<?php
  // ----------------------------------------------
  // | The Database Connection is performed using |
  // | $connection variable!!!                    |
  // ----------------------------------------------
  function get_last_ussps_date($connection){

    $sql = "SELECT date_obs FROM USSPS
            ORDER BY date_obs DESC LIMIT 1";
    //echo "$sql <br/>";

    if($result =	mysqli_query($connection, $sql)){

        while ($row = mysqli_fetch_row($result)){

            $ussps_date = date_create($row[0]);

        }

        return $ussps_date;

        mysqli_free_result($result);
    }

  }
?>



<?php
  function get_ussps_path($ussps_date, $ussps_type){

    $folder_root = "./data/ussps";

    $date_folder = date_format($ussps_date, "/Y/m/"); // aaa/mm

    $filename_date = date_format($ussps_date, "ymd"); // aammdd

    if($ussps_type == "txt"){



      $filename_extension = ".txt";

      $complete_path =  "\"".
                        $folder_root.
                        $date_folder.
                        $filename_date.
                        $filename_extension.
                        "\"";
    }
    else {  //$ussps_type == "jpg")

      $filename_extension = ".jpg";

      $complete_path =  "\"".
                        $folder_root.
                        $date_folder.
                        $filename_date.
                        $filename_extension.
                        "\"";

    }

    return $complete_path;
  }
?>

<?php
  function publish_ussps($connection, $ussps_date){

    $complete_path_txt = get_ussps_path($ussps_date, "txt");
    $complete_path_jpg = get_ussps_path($ussps_date, "jpg");

    ?>

    <table align="left">
      <tr>
        <td bgcolor="#E8E8E8">
          <object data=<?php echo $complete_path_txt?> width="400"></object>
        </td>

        <td width="100">
          <a href = <?php echo $complete_path_jpg ?> target ="_blank">
            <img src = <?php echo $complete_path_jpg ?> width="420%">
          </a>
        </td>
      </tr>
      <tr>
        <td>
          <a href=<?php echo $complete_path_txt ?> download>download</a>
        </td>
        <td>
        </td>
    </tr>
    </table>

<?php
  }
?>

<?php

   function list_ussps($connection, $query_date){

     // $query_date contains only yyyy-mm
     $date_from = "\"" .$query_date. "-01\"";
     // echo $date_from;
     $date_to = "\"" .$query_date. "-31\"";
     // echo $date_to;
     //-----------------
     $sql = "SELECT date_obs FROM USSPS
             WHERE date_obs BETWEEN $date_from AND $date_to
             ORDER BY date_obs";
     // echo "$sql <br/>";

     if($result =	mysqli_query($connection, $sql)){

         echo "<b>" .mysqli_num_rows($result). " records found for " .date_format(date_create($query_date."-01"), "F Y"). "</b>";
    ?>

    <table width=100%>

      <?php

      $week_number = "00";

      while ($row = mysqli_fetch_row($result)){

          $ussps_date = date_create($row[0]);
          $complete_path_txt = get_ussps_path($ussps_date, "txt");
          $complete_path_jpg = get_ussps_path($ussps_date, "jpg");

          if(date_format($ussps_date, "W") != $week_number){
              $week_number = date_format($ussps_date, "W");
      ?>

      <tr bgcolor="#E8E8E8">
          <td colspan="4"> <font color="#1565AA">Week <?php echo $week_number ?></font></td>
      </tr>

      <?php
          }
      ?>

      <tr>
          <td> <?php echo $row[0] ?> </td>
          <td> <a href=<?php echo $complete_path_txt ?>> View USSPS file </a> </td>
          <td> <a href=<?php echo $complete_path_txt ?> download> Download USSPS file </a> </td>
          <td> <a href=<?php echo $complete_path_jpg ?> target ="_blank"> View Sunspot drawing </a> </td>
      </tr>


       <?php
       }

       mysqli_free_result($result);
       echo "</table>";
     }
 }
?>


<?php

  function check_cookie($page_name){
    //echo "page name: " .$page_name;
    $cookie_info = session_get_cookie_params();
    //print_r ($cookie_info);
    if((empty($cookie_info[$page_name]))){

        setcookie($page_name, date('Y-m-d'), time()+120);
    }

  }


function set_my_date_query_cookie($page_name, $query_date){
  setcookie($page_name, $query_date, time()+120);
  $_COOKIE[$page_name] = $query_date;



  echo "New cookie: " .$_COOKIE[$page_name]. " !!!    ";
}


?>








<?php
  function publish_last_image_new($connection, $img_type){

    $img_date = get_last_image_date($connection, $img_type);
    $complete_path = get_image_path($img_date, $img_type, "jpeg", "yes");
    // the resulting string is similar to
    // "./data/jpeg/2016/04/05/oact_halph_fi_20160405_092100.fits.jpg" >
    ?>

   
   
      
          <a href = <?php echo $complete_path ?> target ="_blank" >
            <img src = <?php echo $complete_path ?>  width="100%">
          </a>
      
      
      <br>
        
<center>  <?php echo date_format($img_date, "d F Y, H:i:s"). " UTC" ?> </center>
     

<?php
  }
?>






















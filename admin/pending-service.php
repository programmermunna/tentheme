<?php include("common/header-sidebar.php");?>
<div class="x_container space-y-10 py-10">
    <div class="flex flex-col rounded-lg shadow-md border border-[] shadow-gray-200 bg-white">
        <div class="overflow-x-auto rounded-lg">
            <div class="inline-block min-w-full align-middle">
                <div class="overflow-auto bg-white">
                  <div style="display:flex;justify-content:space-between">
                    <div style="display:flex">
                      <select style="margin: 15px;width:300px;" name="" class="input" id="category">
                          <?php if(isset($_GET['src'])){?>
                          <option style="display:none" selected value="<?php echo $_GET['src']?>"><?php echo $_GET['src']?></option>
                          <?php }else{?>
                          <option style="display:none" selected>Category</option>
                          <?php }?>
                          <option value="Javascript">Javascript</option>
                          <option value="PHP">PHP</option>
                      </select>

                      <select style="margin: 15px;" name="" class="input" id="sort">
                          <?php if(isset($_GET['sort'])){?>
                          <option style="display:none" selected value="<?php echo $_GET['sort']?>"><?php echo $_GET['sort']?></option>
                          <?php }else{?>
                          <option style="display:none" selected>Select</option>
                          <?php }?>
                          <option value="ASC">Low To High</option>
                          <option value="DESC">High To Low</option>
                      </select>

                      <a style="margin:15px;display:block;text-align:center;padding-top:12px;" class="input" href="pending-service.php"><i class="fa-solid fa-rotate-right"></i> Refresh</a>

                      <script type="text/javascript">
                          $(function () {
                              $('#sort').on('change', function () {
                                  var val = $(this).find("option:selected").val();
                                  var url = self.location.href.split('?')[0] + '?sort=' +val;
                                  if (url != "") {
                                      window.location.href = url;
                                  }
                              });
                          });
                          $(function () {
                              $('#category').on('change', function () {
                                  var val = $(this).find("option:selected").val();
                                  var url = self.location.href.split('?')[0] + '?src=' +val;
                                  if (url != "") {
                                      window.location.href = url;
                                  }
                              });
                          });
                      </script>
                    </div>

                    <div>
                      <form action="" method="GET">
                        <div style="text-align: right;margin: 5px;padding-top: 10px;">
                            <input name="src" type="search" id="srcvalue" placeholder="Search Here..." style="padding: 8px;border: 2px solid #ddd;border-radius:5px;">
                            <button type="submit" name="search" style="padding: 9px 15px;margin-right: 12px;background: #0e33f78a;color:#fff;box-sizing: border-box;border-radius: 2px;">Search</button>
                        </div>
                    </form>
                    </div>
                  </div>


                
                  <?php 
                  if(isset($_POST['submit'])){
                    if(isset($_POST['check_list'])){
                      $check_list = $_POST['check_list'];
                      for($i=0;$i<count($check_list);$i++){
                        $delete = _delete("service","id=$check_list[$i]");
                      }
                      $msg = "Delete Successfully";
                      header("location:pending-service.php?msg=$msg");
                    }
                  }
                  ?>
                  <form action="" method="POST">
                    <!-- Table -->
                    <table class="min-w-full divide-y divide-gray-200 table-fixed">
                  <thead class="bg-white">
                    <tr>
                      <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase lg:p-5">
                        <input name="submit" id="submit" style="background:red;padding:5px 10px;color:#fff;border-radius:2px;" type="submit" value="Delete">
                      </th>
                      <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase lg:p-5">Tumbnail</th>
                      <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase lg:p-5">Title</th>
                      <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase lg:p-5">Regular Price</th>
                      <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase lg:p-5">Sell Price</th>
                      <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase lg:p-5">Category</th>
                      <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase lg:p-5">Date</th>
                      <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase lg:p-5">Autor</th>
                      <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase lg:p-5">Status</th>
                      <th scope="col" class="text-center p-4 text-xs font-medium text-left text-gray-500 uppercase lg:p-5"> Actions</th>

                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <?php 
                    if(isset($_GET['src'])){
                      $src = trim($_GET['src']);
                      $service = _get("service","status='Pending' AND (title='$src' OR regular_price='$src' OR sell_price='$src' OR category='$src')");                       

                    }elseif(isset($_GET['sort'])){
                      if($_GET['sort']== 'ASC'){
                        $service =_get("service","status='Pending' ORDER BY sell_price ASC");
                      }else{
                        $service =_get("service","status='Pending' ORDER BY sell_price DESC");
                      }
                    }else{
                    
                    $pagination = "ON";
                    if (isset($_GET['page_no']) && $_GET['page_no']!="") {
                    $page_no = $_GET['page_no'];} else {$page_no = 1;}
                    $total_records_per_page = 8;
                    $offset = ($page_no-1) * $total_records_per_page;
                    $previous_page = $page_no - 1;
                    $next_page = $page_no + 1;
                    $adjacents = "2"; 

                    $service =_get("service","status='Pending' ORDER BY id DESC LIMIT $offset, $total_records_per_page");
                    $total_records = mysqli_num_rows(_get("service","status='Pending'")); 

                    $total_no_of_pages = ceil($total_records / $total_records_per_page);
                    $second_last = $total_no_of_pages - 1;
                    }

                    while($data = mysqli_fetch_assoc($service)){
                    $person_id = $data['pid'];
                    $person_info = _fetch("person","id=$person_id");
                    ?>
                      <tr class="hover:bg-gray-100">
                        <td class="p-4 text-sm font-normal text-gray-500 whitespace-nowrap lg:p-5">
                          <input name="check_list[]" type="checkbox" value="<?php echo $data['id']?>">
                        </td>
                        <td><img style="margin:0 auto;width:100;height:50px;object-fit:cover" src="upload/<?php echo $data['file_name1']?>"></td>
                        <td class="p-4 text-sm font-normal text-gray-500 whitespace-nowrap lg:p-5"><?php echo $data['title']?></td>
                        <td class="p-4 text-sm font-normal text-gray-500 whitespace-nowrap lg:p-5"><?php echo $data['regular_price']?></td>
                        <td class="p-4 text-sm font-normal text-gray-500 whitespace-nowrap lg:p-5"><?php echo $data['sell_price']?></td>
                        <td class="p-4 text-sm font-normal text-gray-500 whitespace-nowrap lg:p-5"><?php echo $data['category']?></td>
                        <td class="p-4 text-sm font-normal text-gray-500 whitespace-nowrap lg:p-5"><?php echo date("d-M-y",$data['time']);?></td>
                        <td class="p-4 text-sm font-normal text-gray-500 whitespace-nowrap lg:p-5"><?php echo $person_info['name']?></td>
                        <td class="p-4 text-sm font-normal text-gray-500 whitespace-nowrap lg:p-5"><?php echo $data['status']?></td>
                        <td class="text-center p-4 space-x-2 whitespace-nowrap lg:p-5">
                          <a href="edit.php?src=pending-service&&table=service&&id=<?php echo $data['id']?>" class="popup_show btn bg-red-500 w-fit text-white" style="background:#4ade80;">Edit</a>
                          <a href="delete.php?src=pending-service&&table=service&&id=<?php echo $data['id']?>" class="popup_show btn bg-red-500 w-fit text-white">Delete</a>
                          <a href="delete.php?src=pending-service&&table=service&&id=<?php echo $data['id']?>" class="popup_show btn bg-red-500 w-fit text-white" style="background:#4ade80;">View</a> 
                        </td>
                      </tr>
                      <?php }?>
                      
                    </tbody>
                  </table>
                </form>

                <?php if(isset($pagination)){?>
                <div style="padding:20px 10px;">
                <!-- /* ----------paginations----------- */ -->
                <style>
                .paginations>ul{box-shadow: 0 0 1px gray;margin: 0;padding: 10px;}
                .paginations>ul>li{list-style: none;display: inline-block;line-height: 2.5;}
                .paginations>ul>li>a{padding: 5px 10px;margin:5px;background: #fff;font-weight: bolder;box-shadow: 0px 0px 2px gray;}
                .paginations>ul>li>a:hover{background: #4ade80;color: #fff;}
                .active>a{background: #4ade80 !important;color: #fff !important;}
                .page_of{padding-top: 10px;}
                @media only screen and (max-width: 850px){.page_of{display: none;}}
              </style>

              <div style="display:flex;justify-content:space-between;padding:10px 20px;">
                  <div class="paginations">
                    <ul>
                      <?php // if($page_no > 1){ echo "<li><a href='?page_no=1'>First Page</a></li>"; } ?>
                        
                      <li <?php if($page_no <= 1){ echo "class=''"; } ?>>
                      <a <?php if($page_no > 1){ echo "href='?page_no=$previous_page'"; } ?>>Previous</a>
                      </li>
                          
                        <?php 
                      if ($total_no_of_pages <= 10){  	 
                        for ($counter = 1; $counter <= $total_no_of_pages; $counter++){
                          if ($counter == $page_no) {
                          echo "<li class=''><a>$counter</a></li>";	
                            }else{
                              echo "<li><a href='?page_no=$counter'>$counter</a></li>";
                            }
                            }
                      }
                      elseif($total_no_of_pages > 10){
                        
                      if($page_no <= 4) {			
                      for ($counter = 1; $counter < 8; $counter++){		 
                          if ($counter == $page_no) {
                          echo "<li class='active'><a>$counter</a></li>";	
                            }else{
                              echo "<li><a href='?page_no=$counter'>$counter</a></li>";
                            }
                            }
                        echo "<li><a>...</a></li>";
                        echo "<li><a href='?page_no=$second_last'>$second_last</a></li>";
                        echo "<li><a href='?page_no=$total_no_of_pages'>$total_no_of_pages</a></li>";
                        }

                      elseif($page_no > 4 && $page_no < $total_no_of_pages - 4) {		 
                        echo "<li><a href='?page_no=1'>1</a></li>";
                        echo "<li><a href='?page_no=2'>2</a></li>";
                            echo "<li><a>...</a></li>";
                            for ($counter = $page_no - $adjacents; $counter <= $page_no + $adjacents; $counter++) {			
                              if ($counter == $page_no) {
                          echo "<li class='active'><a>$counter</a></li>";	
                            }else{
                              echo "<li><a href='?page_no=$counter'>$counter</a></li>";
                            }                  
                          }
                          echo "<li><a>...</a></li>";
                        echo "<li><a href='?page_no=$second_last'>$second_last</a></li>";
                        echo "<li><a href='?page_no=$total_no_of_pages'>$total_no_of_pages</a></li>";      
                                }
                        
                        else {
                            echo "<li><a href='?page_no=1'>1</a></li>";
                        echo "<li><a href='?page_no=2'>2</a></li>";
                            echo "<li><a>...</a></li>";

                            for ($counter = $total_no_of_pages - 6; $counter <= $total_no_of_pages; $counter++) {
                              if ($counter == $page_no) {
                          echo "<li class='active'><a>$counter</a></li>";	
                            }else{
                              echo "<li><a href='?page_no=$counter'>$counter</a></li>";
                            }                   
                                    }
                                }
                      }
                    ?>
                        
                      <li <?php if($page_no >= $total_no_of_pages){ echo "class='disabled'"; } ?>>
                      <a <?php if($page_no < $total_no_of_pages) { echo "href='?page_no=$next_page'"; } ?>>Next</a>
                      </li>
                        <?php if($page_no < $total_no_of_pages){
                        echo "<li><a href='?page_no=$total_no_of_pages'>Last</a></li>";
                        } ?>
                    </ul>
                  </div>
                  <div class="page_of">
                    <div><strong>Page <?php echo $page_no." of ".$total_no_of_pages; ?></strong></div>
                  </div>
                </div>
                <!-- /* ----------paginations----------- */ -->
                <?php }?>
                
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
</div>
</main>

<!-- All Popup -->


<script src="js/app.js"></script>



</body>

</html>
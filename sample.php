<body>
       
    <div class="myform_div"></div>
    <h2 style="text-align:center"><i>Facebooks Search</i></h2>
    <hr>

        <form name="facebook_search" method="get">    
            Keyword<input type=text id="key" name="mykey" required="required" oninvalid="this.setCustomValidity('This cant be left empty')" oninput="setCustomValidity('')"  value="<?php echo isset($_GET['mykey'])?$_GET['mykey']:'' ?>">    
        
             <br/> 


   <label for="my_option">Type </label> 
    
    <select name= "select_opt" id="select_id" onchange='location_hiding();' style="width:10%; margin-left:5%">


        <option value="user" <?php echo isset($_GET[ "select_opt"])&&($_GET[ "select_opt"]=="user" )? "selected" : "user" ;?>>Users</option>
        <option value="page" <?php echo isset($_GET[ "select_opt"])&&($_GET[ "select_opt"]=="page" )? "selected" : "page" ;?>>Pages</option>
        <option value="place" <?php echo isset($_GET[ "select_opt"])&&($_GET[ "select_opt"]=="place" )? "selected" : "place" ;?>>Places</option>
        <option value="group" <?php echo isset($_GET[ "select_opt"])&&($_GET[ "select_opt"]=="group" )? "selected" : "group" ;?>>Groups</option>
        <option value="event" <?php echo isset($_GET[ "select_opt"])&&($_GET[ "select_opt"]=="event" )? "selected" : "event" ;?>>Events</option>
               
    </select>
   
      <div class ="add_Location" id = "loc_handle1">
          
         
            Location.<input type="text" id="location"  name="myloc" style="width:25%" value="<?php echo isset($_GET["myloc"])? $_GET["myloc"] : " " ?>"/>
            Distance. <input type="text" id="distance"  name="distance" style ="width:10%" value="<?php echo isset($_GET["myDis"])? $_GET["myDis"] : " " ?>"/> 
            
               
       </div>
            
       <br/>

            <input type=submit name="Search" value="Search" >
            <input type="button" name="Clear" value="Clear" onclick="'http://cs-server.usc.edu:8012/search.php'">
         
      </form>
         
         

<?php
  
    
      if (isset($_GET['get_details']))
        {
        $id=$_GET['get_details'];
        $txt=get_details($id);
         
        
        }
  
$fb = new Facebook\Facebook(['app_id' => '223100614830290','app_secret' => 'f9b99ab02d8a0421fa053b856b5a14df','default_graph_version' => 'v2.8']); 
    
$fb->setDefaultAccessToken('');  

 function get_details($id){    
    $d= $_GET['mykey'];
     
    $c= $_GET['select_opt'];
     
       
    
    $my_details_url="https://graph.facebook.com/v2.8/$id?fields=id,name,picture.width(700).height(700),albums.limit(5){name,photos.limit(2){name,picture}},posts.limit(5)&access_token=EAADK6KjlaNIBAGk337OPiyrtbCHIb68UT19Dfza0NbqexJAJvbMkiJrv7WVAr3M7ht5w7LWbIC8EfPvu5A1wwlLnQh0gNVZCuZCBuOC8plGkTMYi4ZAFZCYOegQ21oA17WeZBGocZBFvUIbRTEIN742mayLD2BhIIZD";
     


            
         $my_json = file_get_contents($my_details_url);
         $decoded_contents = json_decode($my_json, true);
  
        $n1=100;
     
        if (isset($decoded_contents['results']))
         {   
        $n1=count($decoded_contents['results']);
         }
     
        if($n1==0)
        {
            echo'<html><body>No Records have been found</body></html>';}
   
            $txt="<div id='allAlbums'>";
     
            $album_count='';
            if (isset($decoded_contents['albums']))
            $album_count = sizeof($decoded_contents['albums']);
        
     
            if (($album_count)==0){
               
                $txt.="Albums are not present<br/></div>";
                
            }
    
            if (($album_count)>0){
                           
            
            $txt.="<div><a href=\"javascript:album_hiding()\">Albums</a><br/></div>";
                
           $j=0;
            if(isset($decoded_contents['albums']['data']))    
            while($j < count($decoded_contents['albums']['data'])){
             
                $class="myclass_".$j;
                $txt.="<tr><div class='album_handle'> <a href=\"javascript:myhide($j)\">";
                $txt.=$decoded_contents['albums']['data'][$j]['name'];
                $txt.="</a><br/>";
               //-----------------check 
                $pitchers=$decoded_contents['albums']['data'][$j]['photos']['data'];
                
                $picture_length = sizeof($decoded_contents['albums']['data'][$j]['photos']['data']);
                if($picture_length ==0){$txt.="picture Not Present!!!";}
                else{
                
                   $class="myclass_".$j;
                    $i=0;
                    while($i<$picture_length) 
                        {   
                        $id=$pitchers[$i]['id'];
                        $src="https://graph.facebook.com/v2.8/".$id."/picture?access_token=EAADK6KjlaNIBAGk337OPiyrtbCHIb68UT19Dfza0NbqexJAJvbMkiJrv7WVAr3M7ht5w7LWbIC8EfPvu5A1wwlLnQh0gNVZCuZCBuOC8plGkTMYi4ZAFZCYOegQ21oA17WeZBGocZBFvUIbRTEIN742mayLD2BhIIZD";
                        $txt.="<div class=".$class."><a target='_blank' href='";
                        $txt.=$src;
                        $txt.="'><img src='";
                        $txt.=$pitchers[$i]['picture'];
                        $txt.="'style='height:50px;width:60px;align:left;'></img></a>
                        </div>&nbsp;&nbsp;";
                        $i++;    
                       }
                    }
                $txt.="</a><br/></div>";
                $j++;
            }       
           }
           
            $txt.="</div><div id='blank'><br/><br/></div>";
    
    

            $txt.="<div id='posting'>";
    
         $p1='';
     
         if (isset($decoded_contents['posts']))
         $p1=sizeof($decoded_contents['posts']);
     
 
            if($p1==0){
             $txt.="No Posts Found";
                
             }
             
            else{ 
           $txt.="<a href=\"javascript:post_hiding()\">Posts</a><br/>";
           $i=0;
            if (isset($decoded_contents['posts']))        
            while( $i < count($decoded_contents['posts'])){
               
                $txt.="<div class='post_handle'>";
                $txt.=$decoded_contents['posts']['data'][$i]['message'];
                $txt.="</a><br/></div>";
                $i++;
            }
           
            $txt.="</div>";
        
            echo $txt;
    
    }
     
 
}                           
                            
                            

     

if (isset($_GET["Search"])){
//if(isset($_POST["select_opt"])&&(($_POST["select_opt"]=="user")||){
  
        
$text="";
$text="<br/><br/>";  
$text=$text."<table class=\"a1\" id=\"myTab\">";
$text.="<tr>"."<th>Picture</th>"."<th>Name</th>"."<th>Details</th>"."</tr>"; 
    
$e_text="";
$e_text="<br/><br/>";  
$e_text=$e_text."<table class=\"a1\">";
$e_text.="<tr>"."<th>Picture</th>"."<th>Name</th>"."<th>Places</th>"."</tr>"; 
    
    
            

$d= $_GET['mykey'];
$c= $_GET['select_opt'];

    $search_string=$d;    
    $search_string=trim($d); 

    
    
    if (($_GET["select_opt"]=="user")||($_GET["select_opt"]=="page")||($_GET["select_opt"]=="group")){
        
     $request = $fb->request('GET','/search');
    $request->setParams([
                            'q' => $_GET['mykey'],
                            'type' => $_GET["select_opt"],
                            'fields' => 'id,name,picture.width(700).height(700)'
                        ]);
        $response = $fb->getClient()->sendRequest($request);
        $json = $response->getBody();
        $decoded_contents= json_decode($json, true);
        
        $n=(sizeof($decoded_contents['data']));
        
        if (sizeof($decoded_contents['data'])== 0) {
            echo '<html><body><h3 id="head">No Records</h3></body></html>';
        }
    
    $j=0;
    while($j<$n){
      
       
          $id= $decoded_contents['data'][$j]['id']?$decoded_contents['data'][$j]['id']:"";
        
       $picture[$j]= $decoded_contents['data'][$j]['picture']['data']['url']?$decoded_contents['data'][$j]['picture']['data']['url']:"";
               
        
$text.="<tr>"."<td><a target=\"_blank\" href=\"$picture[$j]\"><img src=\"$picture[$j]\" style=\"width:40px;height:30px\"></a></td>";  
        
            
       $name[$j]= $decoded_contents['data'][$j]['name']?$decoded_contents['data'][$j]['name']:"N/A";
       $text.="<td>$name[$j]</td>";      
       $text.="<td><a href='search.php?get_details=$id&mykey=$d&select_opt=$c'>Details</a></td> </tr>"; 
      
        $j++;
        
    }
          $text.="</table>"; 
          echo $text;
    }
    
    
    
        else if($_GET["select_opt"]=="event") {
           
        $request = $fb->request('GET','/search');
    $request->setParams([
                            'q' => $_GET['mykey'],
                            'type' => $_GET["select_opt"],
                            'fields' => 'id,name,place,picture.width(700).height(700)'
                        ]);
        $response = $fb->getClient()->sendRequest($request);
        $json = $response->getBody();
        $decoded_contents= json_decode($json, true);
        $n='';
        $n=(sizeof($decoded_contents['data']));
        
        if ($n == 0) {
           // echo '<html><body><h3 id="head">No Records have been found</h3></body></html>';
        }
            
            
            for($j=0 ;$j<$n;$j++){
           
      /* $picture[$j]= $decoded_contents['data'][$j]['picture']['data']['url']?$decoded_contents['data'][$j]['picture']['data']['url']:"";
                
        $e_text.="<tr>"."<td><a target=\"_blank\" href=\"$picture[$j]\"><img src=\"$picture[$j]\" style=\"width:40px;height:30px\"></a></td>";         

                
     //  if(isset($decoded_contents(['data'][$j]['name'])))    {    
       $name[$j]= $decoded_contents['data'][$j]['name'];
       $e_text.="<td>$name[$j]</td>"; 
                
      // if (isset($decoded_contents(['data'][$j]['place']['name'])) )           
       $place[$j]=$decoded_contents['data'][$j]['place']['name'];
       $e_text.="<td>$place[$j]</td></tr>";  */
        $picture= $decoded_contents['data'][$j]['picture']['data']['url']?$decoded_contents['data'][$j]['picture']['data']['url']:"";
                
        $e_text.="<tr>"."<td><a target=\"_blank\" href=\"$picture\"><img src=\"$picture\" style=\"width:40px;height:30px\"></a></td>";         

                
     //  if(isset($decoded_contents(['data'][$j]['name'])))    {    
       $name= $decoded_contents['data'][$j]['name'];
       $e_text.="<td>$name</td>"; 
                
      if (isset($decoded_contents['data'][$j]['place']['name']) )  {         
       $place=$decoded_contents['data'][$j]['place']['name'];}else{$place="";}
       $e_text.="<td>".$place."</td></tr>";         
         
    }
        
        $e_text.="</table>"; 
        echo $e_text;    
       }
         
   //}
    
    else{
        $address=$_GET['myloc'];    
        $loc_search_string=trim($address);
       // echo "fshfsh";
        echo $address;
        echo $loc_search_string;
        if ($loc_search_string==""){
            $loc_search_string="null";
        }
        
        $distance=$_GET['distance'];    
        $loc_dist_string=trim($distance);
        
       // echo $loc_dist_string;
    
            
        $place_content =  file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=".$loc_search_string."&key=AIzaSyCyBRK8r90eeomDOQbZXczyXK0kwh9oM44");
        
        $str="https://maps.googleapis.com/maps/api/geocode/json?address=".$loc_search_string."&key=AIzaSyCyBRK8r90eeomDOQbZXczyXK0kwh9oM44";
        
        
         $place_decoded_contents=json_decode($place_content,true);
        
        $n1=sizeof($place_decoded_contents['results']);
        if($n1==0){echo '<html><body><h3>No Records have been found</h3></body></html>';}
        
        else{
              
        $latitude=$place_decoded_contents['results'][0]['geometry']['location']['lat'];
        $longitude=$place_decoded_contents['results'][0]['geometry']['location']['lng'];
       
        //echo $lat;
        //echo $lng;
        $request = $fb->request('GET','/search');
    $request->setParams([
                            'q' => $_GET['mykey'],
                            'type' => $_GET["select_opt"],
                            'fields' => 'id,name,picture.width(700).height(700)',
                            'center'=>$latitude.','.$longitude,
                            'distance'=>$loc_dist_string
                        ]);
        $response = $fb->getClient()->sendRequest($request);
        $json = $response->getBody();
        $decoded_contents= json_decode($json, true);
        
        $n=(sizeof($decoded_contents['data']));
        
        if (sizeof($decoded_contents['data'])== 0) {
            echo '<html><body><h3 id="head">No Records have been found</h3></body></html>';
        }
            
            for($j=0 ;$j<$n;$j++){
           
       $picture[$j]= $decoded_contents['data'][$j]['picture']['data']['url']?$decoded_contents['data'][$j]['picture']['data']['url']:"";
                
       $text.="<tr>"."<td><a target=\"_blank\" href=\"$picture[$j]\"><img src=\"$picture[$j]\" style=\"width:40px;height:30px\"></a></td>"; 
                
      // $text.="<tr>"."<td><img src=\"$picture[$j]\" style=\"height:30px;width:40px;\"></td>";   
                
       $name[$j]= $decoded_contents['data'][$j]['name']?$decoded_contents['data'][$j]['name']:"N/A";
       $text.="<td>$name[$j]</td>"; 
       $text.="<td><a href='search.php?get_details=$id'>Details</td> </tr>"; }
                
                   
            
         
    }
        
        $text.="</table>"; 
        echo $text;    
       }
    
    
}
    
 
?>

</body>



</html>
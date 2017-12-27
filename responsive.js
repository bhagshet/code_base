var selectedtype = "user";
var prog = false;
var lat;
var long;
$('document').ready(function(){
    $(".nav li").on("click",function(){
    $(".nav li").removeClass("active");
    $(this).addClass("active");
    selectedtype = $(this).attr("id");
});
    
    $("#main a").on("click",function(e){
     e.preventDefault();
     $(this).tab('show');
    }); 
    $("#pageTwo").hide();
    $("#mainprogressbar").hide();
    $("#albprogressbar").hide();
    $("#postprogressbar").hide();
   getgeo();
});
    function getgeo(){
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition);
    }
        else{
            lat=0;long=0;
        }
    };

    function showPosition(position) {
   lat= position.coords.latitude;
   long =position.coords.longitude;
    }
   
var myapp = angular.module('myapplication', []);  

    function initLocalStorage() {
            if(localStorage.getItem("favRecords") === null) {
            var favRecords = new Array();
            localStorage.setItem("favRecords", JSON.stringify(favRecords));
            }
        else{
         //  alert("ls created");
        }
        };

myapp.service('localStorageAndSharedVarService', function(){
       return{
                getFavRecords: function() {
                        return JSON.parse(localStorage.getItem("favRecords"));
                                },
                addRecordToFav: function(record,typ){
                        //record.isFav = true;
                    //alert(record);
                        record.favClass = 'fa fa-star yellow-star';
                        record.type = typ;
                        var favRecords = JSON.parse(localStorage.getItem("favRecords"));
                        favRecords.push(record);
                        localStorage.setItem("favRecords", JSON.stringify(favRecords));
                },
                deleteRecordFromFav: function(record){
                        var i = -1;
                        //record.isFav = false;
                        record.favClass = 'fa fa-star-o';
                        var favRecords = JSON.parse(localStorage.getItem("favRecords"));
                        for(var i = 0; i < favRecords.length; i++) {
                                    if(record.id === favRecords[i].id) {
                                    favRecords.splice(i, 1);
                                    break;
                                    }
                                }
                        localStorage.setItem("favRecords", JSON.stringify(favRecords));
                        return i;    
                }                
            } 
                    });

function isFavRecord(rec) {
    var array = JSON.parse(localStorage.getItem("favRecords"));
    for(var i = 0; i < array.length; i++) {
        if(array[i]["id"] == rec["id"]) {
            return true;
        }
    }
    return false;
};

myapp.controller('myControl',function($scope, $http, $document,localStorageAndSharedVarService){
    initLocalStorage();
    //use this to show in fav view
    $scope.getFavorites = function(){
     $scope.favoritedata = localStorageAndSharedVarService.getFavRecords();   
    };
    
    
    //call this on star click
    $scope.addRecordToLocalStorage = function(record,typ){
        //alert(record);
        var record = record;
        var type = typ;
        if(!isFavRecord(record)) {
           localStorageAndSharedVarService.addRecordToFav(record,typ); 
            $scope.getFavorites();
            //alert($scope.favoritedata);
        } else {
            var users;
            switch(type){
                case "user":{users = $scope.userdata.data;};
                    break;
                case "page":{users = $scope.pagedata.data;};
                    break;
                case "event": {users = $scope.eventdata.data;};
                    break;
                case "place": {users = $scope.placedata.data;};
                    break;
                case "group":{users = $scope.groupdata.data;};
                    break;
            }
             for(i=0;i<users.length;i++)
               { if(users[i].id==record.id)   
                   {
                     users[i].favClass = 'fa fa-star-o';
                     break;
                   }
                } 
            var index = localStorageAndSharedVarService.deleteRecordFromFav(record);
            if(index != -1)
            {$scope.favoritedata.splice(index, 1);}
        }
    };
    
    $scope.removeRecordFromLocalStorage  = function(record){
            var users;
            switch(record){
                case "user":{users = $scope.userdata.data;};
                    break;
                case "page":{users = $scope.pagedata.data;};
                    break;
                case "event": {users = $scope.eventdata.data;};
                    break;
                case "place": {users = $scope.placedata.data;};
                    break;
                case "group":{users = $scope.groupdata.data;};
                    break;
             for(i=0;i<users.length;i++)
               { if(users[i].id==record.id)   
                   {
                     users[i].favClass = 'fa fa-star-o';
                     break;
                   }
                } 
        
            var index = localStorageAndSharedVarService.deleteRecordFromFav(record);
            if(index != -1)
            {$scope.favoritedata.splice(index, 1);}
         
    };
    
    $scope.searchFunc = function(){
        var keyword = $scope.kw;
        var type = selectedtype;
        $("#pageOne").show();
        $("#mainprogressbar").show();
        $http({
            url: "http://lowcost-env.nmmjfgnypp.us-west-2.elasticbeanstalk.com/index.php",
            method: "POST",
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            data: $.param({'content': "submit",'kw': keyword,'type': type,'lat': lat,'long': long})
        }).success(function(data, status, headers, config) {
             $("#mainprogressbar").hide();
            var got = data;
             $scope.headings = ["#","Profile-photo","Name","Favorite","Details"];
             $scope.favheadings = ["Profile-photo","Name","Type","Favorite","Details"];
             $scope.userdata =  JSON.parse(got[0]);
             $scope.pagedata = JSON.parse(got[1]);
             $scope.eventdata = JSON.parse(got[2]);
             $scope.placedata = JSON.parse(got[3]);
             $scope.groupdata = JSON.parse(got[4]);
             
             }).error(function(data, status, headers, config) {
               $scope.status = status;
              });
        };
     

    $scope.clearit = function(){
     $scope.kw="";
     $("#pageOne").hide();
     $("#pageTwo").hide();
    };
    
    $scope.makehttpcall= function(purl,type){
     var purl = purl;
      $http({
            url: purl,
            method: "GET",
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        }).success(function(data, status, headers, config) {
            var got = data;
             $scope.headings = ["#","Profile-photo","Name","Favorite","Details"];
          if(type=='user'){$scope.userdata =  got; }
          if(type=='page'){ $scope.pagedata = got;}
          if(type=='event'){$scope.eventdata = got;}
          if(type=='place'){$scope.placedata = got;}
          if(type=='group'){$scope.groupdata = got;}
             
             
             }).error(function(data, status, headers, config) {
               $scope.status = status;
              });  
    };
    
    $scope.getnext = function(pdata,type){
        var url =pdata["paging"]["next"];
       $scope.makehttpcall(url,type)
    };
    
    $scope.getprevious = function(pdata,type){
       var url =pdata["paging"]["previous"];
      $scope.makehttpcall(url,type)  
    };
    
        $scope.facebook_pic ="";
        $scope.facebook_name ="";
      
        $scope.clickDetails = function(rec) {
            var id = rec.id;
            $scope.curr = rec;

            $("#pageOne").hide();
            $("#pageTwo").show();
            $("#albprogressbar").show();
            $("#postprogressbar").show();
           $http({
            url: "http://lowcost-env.nmmjfgnypp.us-west-2.elasticbeanstalk.com/index.php",
            method: "POST",
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            data: $.param({'content': "album",'id': id})
            }).success(function(data, status, headers, config) {
               $("#albprogressbar").hide();
                $("#postprogressbar").hide();
               $scope.post_name = data["name"];
               $scope.post_img = data["picture"]["data"]["url"];
               $scope.albumdata = data["albums"]["data"];
               $scope.postsdata = data["posts"]["data"];
               $scope.facebook_pic = $scope.post_img;
               $scope.facebook_name = $scope.post_name;
           }).error(function(data, status, headers, config) {
               $scope.status = status;
              });    
        };
    
        $scope.backButtonClick = function(){
          $("#pageTwo").hide();
            $("#pageOne").show();
        };
    
    
    $scope.convert=function(tim){
        var tim1 = tim.substr(0,tim.length-14);
        var tim2 = tim.substring(11,tim.length-5);
        var tim = tim1+" "+tim2;
        return tim;
    };
    
  });
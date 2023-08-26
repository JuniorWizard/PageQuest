<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name='viewpoint' content='width=device-width,initial-scale=1.0'>
        <title>PageQuest</title>
        <link rel='stylesheet' type='text/css' href='style/Main.css'>
        <script type='text/javascript' src='JQuery/JQuery.js'></script>
    </head>
    <body>
        <h2>PageQuest</h2>
        <div class="SearchContainer">
            <br>
            <input type=text id='Search' placeholder="Search Here">
            <br><br>
            <button class='button' type=button id='ActionSearch'>Search</button>
            <br><br>
        </div>
        <div class='LoadContainer'>
            <div class='loader'></div>
            <h3 id='loading_text'>Loading..</h3>
        </div>
        <div id='Results' class='container flex' style='display:none'>
            <div id='Results_Container' class='subcontainer'>
                <div class='overlay'></div>
                <div class='modal'></div>
            </div>
        </div>
        <script>
            $(document).on('keypress',function(e){
                if(e.which ==13){  
                    $("#ActionSearch").click();
                  
                }
            })
       
            $("#ActionSearch").on('click',function(){
                $("#ActionSearch").addClass('Transition');
                $("#Search").addClass('Transition');
                $(".loader").addClass('load');
                $("#loading_text").addClass('load');
                    setTimeout(function(){
                        $(".SearchContainer").addClass('Searched');
                    },900)
                var searchAction=$("#Search").val();
                if(searchAction.length!==0){
                    $.ajax({
                        type:"POST",
                        url:"API/Search.php",
                        data:{
                            searchValue:searchAction,
                        },
                        success:function(data){
                          
                           var e=JSON.parse(data);
                           console.log(e);
                           var count_of_entries=e.length;
                           for(var i=0;i<count_of_entries;i++){
                            var book_cover=e[i]['image'];
                            var title=e[i]['title'];
                            var link=e[i]['link'];
                            var auth=e[i]['auth'][0]; // Authors is an array need to check for amount of authors maybe ?
                            BookDisplay_Template(book_cover,title,auth,link);
                            setTimeout(function(){
                                $("#loading_text").removeClass('load');
                                $('.loader').removeClass('load');
                            },2000)
                            setTimeout(function(){
                                $("#Results").show();
                            },2000)
                           }
                        },
                        error:function(data){

                        }
                    })
                }else{
                    alert("Please Search Value");
                }
            })
            function BookDisplay_Template(img,book_title,auth,move_link){
                const mainContainer = $("#Results_Container");
                var book_container = $('<div>', {
                    class: 'book_container'
                });
                var imageLink = $('<a>', {
                    href: move_link,
                    class: 'image-link'
                });
                var image = $('<img>', {
                    src: img || 'images/missing.png', // Default image if img is null
                    alt: 'Book Cover',
                    class: 'book-img'
                });
                var details = $('<div>', {
                    class: 'book-details'
                });
                var title = $('<h4>', {
                    class: 'title',
                    text: book_title
                });

                details.append(title);
                imageLink.append(image);
                book_container.append(imageLink);
                book_container.append(details);
                mainContainer.append(book_container);
            }
        </script>
    </body>
</html>
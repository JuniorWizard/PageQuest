<!DOCTYPE html>
<html lang="en">
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
            $(document).on('keypress',function(e){ // Handling if the user presses the enter key rather than directly clicking the button
                if(e.which ==13){  
                    $("#ActionSearch").click(); 
                  
                }
            })
       
            $("#ActionSearch").on('click',function(){ // When the search button is clicked .. do the following
                $("#ActionSearch").addClass('Transition'); // Transition class to allow for CSS transition
                $("#Search").addClass('Transition'); 
                $(".loader").addClass('load'); // Adding Loading Animation class
                $("#loading_text").addClass('load'); // Shows Loading Animation text
                    setTimeout(function(){ // After 900 miliseconds do the following..
                        $(".SearchContainer").addClass('Searched'); // CSS hides the main search div
                    },900)
                var searchAction=$("#Search").val(); // Get searched for value
                if(searchAction.length!==0){ // If the length of that value does not equal zero then ..
                    $.ajax({ // AJAX Call .. POSTING Data to the URL
                        type:"POST",
                        url:"API/Search.php", 
                        data:{
                            searchValue:searchAction, 
                        },
                        success:function(data){ // If the post is successful : 
                           var e=JSON.parse(data); // Parse Response into an array
                           var count_of_entries=e.length; // Getting Length of array
                           for(var i=0;i<count_of_entries;i++){ // For Loop to handle each record within the array
                            var book_cover=e[i]['image']; // Book Cover Image
                            var title=e[i]['title']; // Book Title
                            var link=e[i]['link']; // Book Link
                            var auth=e[i]['auth'][0]; // Authors is an array need to check for amount of authors maybe ?
                            BookDisplay_Template(book_cover,title,auth,link); // Using Template Formula .. See below 
                            setTimeout(function(){ // After 2000 miliseconds do the following ..
                                $("#loading_text").removeClass('load'); // Hiding Load Text
                                $('.loader').removeClass('load'); // Hiding Load Animation
                            },2000)
                            setTimeout(function(){  // This is intentionally, seperated from the above as this adds a slightly delay.
                                $("#Results").show(); // Shows Results
                            },2000)
                           }
                        },
                        error:function(data){ // If the post was not successful : 
                            alert("Oh No ! An Error Occured, please refresh and try again.");
                        }
                    })
                }else{ // If search value is empty:
                    alert("Please Search Value");
                }
            })
            
            //Book display Template that creates a single book elment. 
            function BookDisplay_Template(img,book_title,auth,move_link){ 
                const mainContainer = $("#Results_Container"); // Main Results Container
                var book_container = $('<div>', { // Describe the html element that it's creating
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

                // Appending those elements into their appropriate containers
                details.append(title);
                imageLink.append(image);
                book_container.append(imageLink);
                book_container.append(details);
                mainContainer.append(book_container);
            }
        </script>
    </body>
</html>
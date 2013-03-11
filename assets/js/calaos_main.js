
$(document).ready(function()
{
        //When page loads...

        $(".tab_content").hide(); //Hide all content
        $("ul.tabs li:first").addClass("active").show(); //Activate first tab
        $(".tab_content:first").show(); //Show first tab content

        //On Click Event
        $("ul.tabs li").click(function() {

                $("ul.tabs li").removeClass("active"); //Remove any "active" class
                $(this).addClass("active"); //Add "active" class to selected tab
                $(".tab_content").hide(); //Hide all tab content

                var activeTab = $(this).find("a").attr("href"); //Find the href attribute value to identify the active tab + content
                $(activeTab).fadeIn(); //Fade in the active ID content
                return false;
        });


        $('.column').equalHeight();

        /*
        var hash = MD5("raoul@calaos.fr");
        $.ajax({
                url: 'http://www.gravatar.com/' + hash + '.json',
                cache: true,
                type: 'GET',
                dataType: 'jsonp',
                success: function (data) {
                        var url = data.entry[0].thumbnailUrl + '?size=22';
                        $('.user p').css("background-image", "url(" + url + ")");
                }
        });*/
        
        $(".slider").slider({
                orientation: "horizontal",
                range: "min",
                max: 255 });

        $("area[rel^='prettyPhoto']").prettyPhoto({
                theme: 'facebook', /*pp_default / light_rounded / dark_rounded / light_square / dark_square / facebook */
                social_tools: ''
        });
        
        $("#camera_1").click(function () {
//                 $.prettyPhoto.open('../camera_test_06.jpg','Title','');
                api_images = ['../camera_test_06.jpg','../camera_test_05.jpg','../camera_test_03.jpg','../camera_test_04.jpg'];
                api_titles = ['Title 1','Title 2','Title 3', 'Title 4'];
                api_descriptions = ['', '', '', ''];
                $.prettyPhoto.open(api_images,api_titles,api_descriptions);
        })

});

function loadingOverlay()
{
        $("#loading_overlay .loading_message").delay(200).fadeOut(function(){});
        $("#loading_overlay").delay(500).fadeOut();
}

$(window).load(function ()
{
        loadingOverlay();
});

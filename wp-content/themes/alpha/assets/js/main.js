;(function ($){
    $(document).ready(function (){
       $(".popup").each(function (){
           var image = $(this).find("img").attr("src");
           $(this).attr("href",image);
       }) ;

        var slider = tns({
            container: '.slider',
            speed:3000,
            autoplayTimeout:3000,
            items: 1,
            autoplay: true,
            autoHeight:true,
            controls:false,
            nav:false,
            autoplayButtonOutput:false

        });

    });
})(jQuery);
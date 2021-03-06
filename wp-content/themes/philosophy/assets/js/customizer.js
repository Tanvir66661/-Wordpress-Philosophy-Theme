;(function ($){
    wp.customize('philosophy_about_heading',function (value){
        value.bind(function (newvalue){
            $("#about_heading").html(newvalue);
        })
    });

    wp.customize('philosophy_about_desc',function (value){
       value.bind(function (newvalue){

           $('#about_description').html(newvalue);
       })
    });

    wp.customize('philosophy_icon_color',function (value){
        value.bind(function (newvalue){
            $('.about_us i').css("color",newvalue);
        })
    });


})(jQuery);



jQuery(document).ready(function($) {
    $('.testimonials').masonry({
        // options
        itemSelector: '.testimonial',
        columnWidth: 400,
        gutter: 15
    });


    var owl;


    $(document).ready(function(){
        owl = $('.owl-carousel').owlCarousel({
            loop:true,
            items:1
        });
      });


      $(document).ready(function(){


        if($(".owl-item").length == 0) return 0; 

        $($(".owl-item.active").prev()).addClass("prev");
        $($(".owl-item.active").next()).addClass("next");


        owl.on("changed.owl.carousel",function(event){
            $(".owl-item").removeClass("prev");
            $(".owl-item").removeClass("next");

            setTimeout(function(){
                $($(".owl-item.active").prev()).addClass("prev");
                $($(".owl-item.active").next()).addClass("next");
            });
            
        });


        

      })



    $(".news-tabs ul li a").click(function(){
        $(".news-tabs ul li a").removeClass("active");
        $(".news-tabs .tabs-one-tab").removeClass("active");
        $(".news-tabs .news-all-link").removeClass("active");

        $(this).addClass("active");
        $($(".news-tabs .tabs-one-tab")[$(".news-tabs ul li a").index($(this))]).addClass("active");
        $($(".news-tabs .news-all-link")[$(".news-tabs ul li a").index($(this))]).addClass("active");


    });


    $(".right-side-links ul li a").click(function(){
        $(".right-side-links ul li a").removeClass("active");
        $(".main-header-tabs").removeClass("active");
        //$(".news-tabs .news-all-link").removeClass("active");

        $(this).addClass("active");
        $($(".main-header-tabs")[$(".right-side-links ul li a").index($(this))]).addClass("active");
        //$($(".news-tabs .news-all-link")[$(".news-tabs ul li a").index($(this))]).addClass("active");


    });



    $(document).scroll(function(){

        if($(".sidebar").length == 0) return 0; 


        //отрываем сайдбар
        if($(document).scrollTop() > $(".page-sidebar").offset().top - 40){
            $(".sidebar").css("position","fixed");
            $(".sidebar").css("top","40px");
        }
        else{
            $(".sidebar").css("position","relative");
            $(".sidebar").css("top","auto");
        }
        //обрабатываем вызод за пределы контейнера
        if(($(".sidebar").offset().top + $(".sidebar").height()) > ($(".page-sidebar").offset().top + $(".page-sidebar").height())){
            $(".sidebar").css("top", ($(".page-sidebar").offset().top + $(".page-sidebar").height()) - ($(".sidebar").offset().top + $(".sidebar").height()));
        }
            

    });


    $(document).scroll(function(){
        
        if($(".third-fixed").length == 0) return 0; 


        //отрываем сайдбар
        if($(document).scrollTop() > $(".content__left").offset().top - 40){
            $(".third-fixed").css("position","fixed");
            $(".third-fixed").css("top","40px");
        }
        else{
            $(".third-fixed").css("position","absolute");
            $(".third-fixed").css("top","auto");
        }
        //обрабатываем вызод за пределы контейнера
        // if(($(".third-fixed").offset().top) > ($(".content__left").offset().top)){
        //     $(".third-fixed").css("top", ($(".page-sidebar").offset().top + $(".page-sidebar").height()) - ($(".sidebar").offset().top + $(".sidebar").height()));
        // }


        if(($(".third-fixed").offset().top)>($(".page-sidebar").offset().top + $(".page-sidebar").height() - ($(".third-fixed").height()))){
            $(".third-fixed").css("top", ($(".page-sidebar").offset().top + $(".page-sidebar").height()) - ($(".third-fixed").offset().top + $(".third-fixed").height() - 50));
        }
            

    });

    $(document).ready(thirdRes);
    $(window).resize(thirdRes);


    //third-col responsive position
    function thirdRes(){
        if($(".third-col").length == 0) return 0; 

        if($(document).width()>1400){

            $(".third-col").css("left", ($(".content__left").offset().left + $(".content__left").width()+30));

        }

    }

    $(document).ready(function(){
        var a = $('.testimonial__head');
        for(var i=0; i< a.length; i++){

            if(($($(a).find("div p:last-child")[i]).height()>42) & ($($(a).find("div p:last-child")[i]).height()<65) ){
                $(a[i]).height($(a[i]).height() + $($(a).find("div p:last-child")[i]).height() - 42);
                
                $($(a[i]).prev()).css("position", "relative");
                $($(a[i]).prev()).css("top", $(a[i]).height()/-3.5);
                
                $('.testimonials').masonry({
                    // options
                    itemSelector: '.testimonial',
                    columnWidth: 400,
                    gutter: 15
                });
            }else if($($(a).find("div p:last-child")[i]).height()>42){
                $(a[i]).height($(a[i]).height() + $($(a).find("div p:last-child")[i]).height() - 42);
                
                $($(a[i]).prev()).css("position", "relative");
                $($(a[i]).prev()).css("top", $(a[i]).height()/-2.5);
                
                $('.testimonials').masonry({
                    // options
                    itemSelector: '.testimonial',
                    columnWidth: 400,
                    gutter: 15
                });
            }
        }
    });


    $(document).ready(function(){
        var colmn = $("img.third-col.third-fixed");

        if($(colmn).length == 1){
            colmn = $(colmn).detach();
            $(".content").prepend($(colmn));
            if($(document).scrollTop() > $(".content__left").offset().top - 40){
                $(".third-fixed").css("position","fixed");
                $(".third-fixed").css("top","40px");
            }
            else{
                $(".third-fixed").css("position","absolute");
                $(".third-fixed").css("top","auto");
            }
    
    
            if(($(".third-fixed").offset().top)>($(".page-sidebar").offset().top + $(".page-sidebar").height() - ($(".third-fixed").height()))){
                $(".third-fixed").css("top", ($(".page-sidebar").offset().top + $(".page-sidebar").height()) - ($(".third-fixed").offset().top + $(".third-fixed").height() - 50));
            }
        }


    });




    $('.nav-link-img').on('click', function(){
        if($('.search').is(':visible')){
            $('.search').fadeOut();
        } else{
            $('.search').fadeIn();
        }
    });
    
    $('.search__close').on('click', function(){
        $('.search').fadeOut();
    });
    
    $(document).keyup(function(e) {
        if (e.keyCode === 27){
            $('.search').fadeOut();
        }
    });

});

$('.wu-accordion__head').click(function(){
    var a = $(this).next();
    //var listAcc = 
    if(!$(this).data("open")){

        //open only one accordion
        $('.wu-accordion__body').slideUp();
        $('.wu-accordion__head').removeClass('wu-accordion__head-open');
        $('.wu-accordion__head').data("open",false);


        a.slideDown();
        $(this).addClass('wu-accordion__head-open');

    }
    else{
        a.slideUp();
        $(this).removeClass('wu-accordion__head-open');
    }

    //$(".wu-accordion__head [data-open='true']").slideUp();
    //$(this).data("open", "false");
    //a.slideDown();


    $(this).data("open", !($(this).data("open"))); //= true;//;
});


$(document).ready(function() {

        // $(".submenu-container").css("left", $(".main-header-menu").offset().left);

    
        $.fancybox.defaults.hash = false;

        $(".video-block").click(function(){
            
            $("body").append('<div class="video-hidder"></div>');
            $("body .video-hidder").append('<iframe width="780" height="440" src="https://www.youtube-nocookie.com/embed/'+$(this).data("videolink").substr($(this).data("videolink").indexOf('=')+1,11)+'?rel=0&amp;controls=0&amp;showinfo=0" frameborder="0" allowfullscreen></iframe>');
            $("body .video-hidder").append('<a href="javascript:void(0)">X</a>');
            $("body").css('overflow','hidden');

            $(".video-hidder a").click(function(){
                $("body").css('overflow','auto');
                $("body .video-hidder").remove();
                
            });

        });

        function initMapArgs(elem, lat_a, lng_a) {

            var map = new google.maps.Map($(elem)[0], {
              zoom: 17,
              center: {lat: lat_a, lng: lng_a},
              disableDefaultUI: true,
              draggable: false
            });


        }

        var mapsEl = $("body .map-container");

        setTimeout(function(){
            for(var i=0; i < mapsEl.length;i++){
                var a = $(".map-container")[i];
                $($(".map-container")[i]).height($($($($(".map-container")[i]).parent()).parent()).height());
                var a1 = a[0]
                initMapArgs($(a), $(a).data("lat"), $(a).data("lng"));
            }
        },500);
        



        
    });

    $(window).resize(function(){
        // $(".submenu-container").css("left", $(".main-header-menu").offset().left);

        
        
    });



    for(var i=0; i< $(".submenu-container .submenu__list ul").length; i++){
        var el = $(".submenu-container .submenu__list ul")[i];
        if($(el).children().length % 2 == 0){
            // $($($(el).children()[$(el).children().length / 2 - 1])).css("border","none");
            $($($(el).children()[$(el).children().length / 2 - 1])).css("padding-bottom","0");
            
        }else{
            // $($($(el).children()[($(el).children().length - 1) / 2])).css("border-bottom-color","#fff");
            $($($(el).children()[($(el).children().length - 1) / 2]).children()[0]).css("padding-bottom","0");
            
        }

        
    }




    $(".gallery-item a").click(function(){
        openGallery(this);
    });



    $(".video-gallery-item a").click(function(){
        //openGallery(this);
    });


    $(".video-gallery-item a").click(function(){
        
        $("body").append('<div class="video-hidder"></div>');
        $("body .video-hidder").append('<iframe width="780" height="440" src="https://www.youtube-nocookie.com/embed/'+$(this).data("videolink").substr($(this).data("videolink").indexOf('=')+1,11)+'?rel=0&amp;controls=0&amp;showinfo=0" frameborder="0" allowfullscreen></iframe>');
        $("body .video-hidder").append('<a href="javascript:void(0)">X</a>');
        $("body").css('overflow','hidden');

        $(".video-hidder a").click(function(){
            $("body").css('overflow','auto');
            $("body .video-hidder").remove();
            
        });

    });

    //реализация галлереи
    function openGallery(el){
        el = $(el).next();
        el = $(el).find("a")[0];
        $(el).trigger("click");
    }



    //табы в селекты
$(document).ready(function(){
    if($(window).width() < 530){
        $(".page-news .news-tabs").hide();
        var opts = $(".page-news .news-tabs a");

        var str = "<select class='cs-select cs-skin-rotate'>";
        for(var i=0; i<opts.length; i++){
            var selected = "";

            if(($(opts[i]).attr("class"))=="active"){
                selected = "selected";
            }

            str += "<option value='"+ $(opts[i]).attr("href")+"' "+ selected +">"+ $(opts[i]).html()+"</option>";
        }

        str += "</select>";

        $(".page-news .pagetitle").append(str);

        (function() {
            [].slice.call( document.querySelectorAll( 'select.cs-select' ) ).forEach( function(el) {	
                new SelectFx(el);
            } );
        })();


        $(".cs-select").prepend("<p>Category</p>");

        $(".cs-options ul li").click(function(){
            location.href = $(this).data('value');
        });

    }
});

//формирование мобильных галерей
$(document).ready(function(){
    if(($(window).width() < 530) && ($('.content__left>div>#single_image').length > 1)){
        
        var elms = $(".content__left>div>#single_image");
        var tmp_gal = "";
        for(var i=0; i<elms.length;i++){
            if(tmp_gal != $(elms[i]).data('fancybox')){
                console.log(tmp_gal+"\n"+$(elms[i]).data('fancybox'));
                tmp_gal = $(elms[i]).data('fancybox');

                $(elms[i]).addClass('gal-hidder');


                i++;
                
            }
            $(elms[i]).hide();
        }
    }
});


//мобильное меню
$(document).ready(function(){
    if($(window).width() < 1190){

        var menuFlag = false;

        // $(".main-header-menu>ul").css('transform','translate3d(100%, 0, 0)');
        $(".main-header-menu>ul").prepend('<li class="mobile-search"></li>');
        $(".mobile-search").html($("form.search"));
        $(".mobile-search .search-wrap").append('<input class="search__btn" type="submit" value="">');


        $(".main-header-menu>ul").append('<li class="mobile-from-tops"></li>');
        $(".mobile-from-tops").html($(".lang-container"));

        $(".mobile-from-tops").prepend($(".nav-container:nth-child(3)"));
        $(".mobile-from-tops").prepend($(".nav-container:last-child"));


        $(".main-header-menu>ul").after('<div class="mobile-close-nav" id="mobile-close-nav"></div>');

        $(".mobile-close-nav").append('<input class="search__close" type="button">');

        $("body").append('<div class="hidder-layer"></div>');
        //$(".hidder-layer").hide();
        
        //$(".main-header-menu").addClass('animated fadeOutRight');


        $(".nav-burger").click(function(){
            $(".mobile-close-nav").trigger("click");

        })


        $('.mobile-close-nav').click(function(){
            if(!menuFlag){
                //$(".main-header-menu>ul").show();
                // $(".hidder-layer").show();
                $(".main-header-menu>ul").css('transform','none');
                
                $(".main-header-menu>ul").removeClass('fadeOutRight');
                
                $(".main-header-menu>ul").addClass('animated fadeInRight');
                $(".main-header-menu>ul").css("z-index","999");
                $(".hidder-layer").show();
                $("body").css("overflow-y","hidden");
                

                setTimeout(function(){
                    // $(".main-header-menu>ul").removeClass('animated fadeInRight');
                    $(".hidder-layer").show();
                    $(".mobile-close-nav").removeClass('fadeOutUp');
                    $(".mobile-close-nav").css('transform','none');
                    $('.mobile-close-nav').addClass('animated fadeInDown');

                },500);
                

            }else{
                $(".main-header-menu>ul").removeClass('fadeInRight');
                $(".mobile-close-nav").removeClass('fadeInDown');
                $(".main-header-menu>ul").addClass('animated fadeOutRight');
                $(".main-header-menu>ul").css("z-index","999");
                $("body").css("overflow-y","auto");
                $('.mobile-close-nav').addClass('animated fadeOutUp');

                //$(".hidder-layer").hide();

                setTimeout(function(){
                    //(".main-header-menu>ul").removeClass('animated fadeOutRight');
                    $(".hidder-layer").hide();
                    
                    // $(".main-header-menu>ul").hide();

                },500);
                



            }

            menuFlag = !menuFlag;

            console.log(menuFlag);



        });



    }
});



// audio player

$(document).ready(function(){
    var audioplayer = document.getElementById("audio-player");

    if(audioplayer == null) return 0;
    

    setInterval(function(){
        var time = "0:00";
        //timestemp
        if(Math.floor(audioplayer.currentTime)<59){
            time = "0:"+Math.floor(audioplayer.currentTime);
            if(Math.floor(audioplayer.currentTime)<10)time = "0:0"+Math.floor(audioplayer.currentTime);
        }else{
            var min = Math.floor(audioplayer.currentTime / 60);
            time = min + ":"+Math.floor(audioplayer.currentTime - min * 60);
            if(Math.floor(audioplayer.currentTime - min * 60)<10) time = min + ":0"+Math.floor(audioplayer.currentTime - min * 60);


        }


        $(".player-time").html(time);

        //timeline
        var currentTimePlayer = (audioplayer.currentTime / audioplayer.duration) * 100;
        $(".player-progress div").css('width', currentTimePlayer+"%");

        if((currentTimePlayer == 100) && ($("#play-bt.pause").length!=0)) {
            $("#play-bt").toggleClass('pause');
        };




    },300);
    


    

    $(".player-volume").click(function(e){
        var parentOffset = $(this).offset(); 
        
        var relX = e.pageX - parentOffset.left;
        console.log((relX / $(this).width())*100);


        $('.player-volume div:last-child').css('width', ((relX / $(this).width())*100)+'%');

        audioplayer.volume = (relX / $(this).width());


    });
    


    $("#play-bt").click(function(){
        if (audioplayer.paused) {
           audioplayer.play();
           
        }   
        else {
           audioplayer.pause();
        }
        $(this).toggleClass('pause');  /* style your toggle button according to 
                                          the current state */
    })
});
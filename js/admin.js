jQuery(document).ready(function(){
    jQuery('.draft').hide();
    jQuery("#cf_type").val(jQuery(".btn.active").data("value"));
    jQuery(".cf_header .btn").click(function(){
        jQuery(".cf_header .btn").removeClass().addClass('btn');
        jQuery(this).addClass("active");
        jQuery("#cf_type").val(jQuery(this).data("value"));
        switch(jQuery(this).data("value")){
            case "p":
                jQuery(this).addClass("btn-selected");
                jQuery(".cf_url").show();
                jQuery(".helpinfo").hide();
                jQuery(".p_text").show();
            break;
            case "hp":
                jQuery(this).addClass("btn-selected");
                jQuery(".cf_url").hide();
                jQuery(".helpinfo").hide();
                jQuery(".hp_text").show();
            break;
            case "np":
                jQuery(this).addClass("btn-selected");
                jQuery(".cf_url").hide();
                jQuery(".helpinfo").hide();
                jQuery(".np_text").show();
            break;
        }
    })
    jQuery("#publish").click(function(e){
        if(jQuery("#cf_slug").val()=="" && jQuery(".btn.active").data("value")=="p")
        {
            e.preventDefault();
            jQuery("#cf_slug").css("border", "1px solid red");
            jQuery("#cf_invalid_slug").show();
        }
        else
        {
            jQuery("#cf_slug").css("border", "1px solid #cccccc");
            jQuery("#cf_invalid_slug").hide();
        }
    })
})
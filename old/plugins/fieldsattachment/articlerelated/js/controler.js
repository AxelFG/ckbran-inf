// Article related  ========================================================================


var one_articlerelated=true;
var TT_articlerelated;

function controler_percha_articlerelated()
{ 
    var extras = $('jform_extras');  
    //contenr = $('jform_params_field_tags_size').value;
    
    //extras.value = contenr;
    hide_articlerelated();
    
    //New 
    if(one_articlerelated){
        TT_articlerelated = new Objarticlerelated("wrapperextrafield_articlerelated"); 
         
        one_articlerelated=false;
    } 
    TT_articlerelated.init(extras.value); 
}

function hide_articlerelated()
{
    var nom =  'image-params';

    if($(nom)!=null)
    {
        
        $($(nom).getParent( )).setStyle('display','none');
    }
 

}
  
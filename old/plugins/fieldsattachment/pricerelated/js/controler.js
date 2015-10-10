// Article related  ========================================================================


var one_pricerelated=true;
var TT_pricerelated;

function controler_percha_pricerelated()
{ 
    var extras = $('jform_extras');  
    //contenr = $('jform_params_field_tags_size').value;
    
    //extras.value = contenr;
    hide_pricerelated();
    
    //New 
    if(one_pricerelated){
        TT_pricerelated = new Objpricerelated("wrapperextrafield_pricerelated"); 
         
        one_pricerelated=false;
    } 
    TT_pricerelated.init(extras.value); 
}

function hide_pricerelated()
{
    var nom =  'image-params';

    if($(nom)!=null)
    {
        
        $($(nom).getParent( )).setStyle('display','none');
    }
 

}
  
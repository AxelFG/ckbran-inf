// Article related  ========================================================================


var one_contactrelated=true;
var TT_contactrelated;

function controler_percha_contactrelated()
{ 
    var extras = $('jform_extras');  
    //contenr = $('jform_params_field_tags_size').value;
    
    //extras.value = contenr;
    hide_contactrelated();
    
    //New 
    if(one_contactrelated){
        TT_contactrelated = new Objcontactrelated("wrapperextrafield_contactrelated"); 
         
        one_contactrelated=false;
    } 
    TT_contactrelated.init(extras.value); 
}

function hide_contactrelated()
{
    var nom =  'image-params';

    if($(nom)!=null)
    {
        
        $($(nom).getParent( )).setStyle('display','none');
    }
 

}
  
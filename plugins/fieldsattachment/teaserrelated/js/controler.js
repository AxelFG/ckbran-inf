// Article related  ========================================================================


var one_teaserrelated=true;
var TT_teaserrelated;

function controler_percha_teaserrelated()
{ 
    var extras = $('jform_extras');  
    //contenr = $('jform_params_field_tags_size').value;
    
    //extras.value = contenr;
    hide_teaserrelated();
    
    //New 
    if(one_teaserrelated){
        TT_teaserrelated = new Objteaserrelated("wrapperextrafield_teaserrelated"); 
         
        one_teaserrelated=false;
    } 
    TT_teaserrelated.init(extras.value); 
}

function hide_teaserrelated()
{
    var nom =  'image-params';

    if($(nom)!=null)
    {
        
        $($(nom).getParent( )).setStyle('display','none');
    }
 

}
  
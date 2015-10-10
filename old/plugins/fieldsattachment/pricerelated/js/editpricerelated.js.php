<?php header("Content-type: application/x-javascript");?>

<?php

$size = "Size";

$max_size = "Max size"; 

//JSON *************** 

if(isset($_GET["dictionary"])) 

{

  

   $langfile = $_GET["dictionary"]; 

   if(file_exists($langfile))

   { 

       $strlang = file_get_contents($langfile); 

       $strlang = str_replace('=', '":', $strlang);

       $tmp = array();

       

       $strlang = explode("\n", $strlang);

       foreach ($strlang as $line)

       {

            

           $pos = strpos($line, ";");



           if(!empty($line) && ($pos === false)) $tmp[count($tmp)] = '"'.$line;

       }

       $strlang = implode( $tmp ,",");

       

       $strlang = "{".$strlang."}";

       $obj = json_decode($strlang);

       if(isset($obj->{'COM_FIELDSATTACH_SIZE'})) $size =  $obj->{'COM_FIELDSATTACH_SIZE'}; // 

       if(isset($obj->{'COM_FIELDSATTACH_MAXWIDTH'})) $max_size =  $obj->{'COM_FIELDSATTACH_MAXWIDTH'}; // 

        

       

   }  

}



//if(isset($_GET["size"])) { $size = $_GET["size"]; }

//if(isset($_GET["max_size"])) { $max_size = $_GET["max_size"]; } 

?>



 

/*

Copyright (c) 2007 John Dyer (http://percha.com)

MIT style license

*/

/*

if (!window.Refresh) Refresh = {};

if (!Refresh.Web) Refresh.Web = {};

 */









Objeditpricerelated = new Class({

	id: null,

        _bar: null,

        array_of_articles:null,

	 

        



    Implements: [Options],



    options: { 

        arrowImage: 'refresh_web/colorpicker/images/rangearrows.gif'

    },



	initialize: function(id, options) {

	 

                 

	},

        init: function(id){

             

            this.array_of_articles = new Array();

            this.id  = id;

            this._bar = $("field_"+this.id);

            this._bar.Objeditpricerelated = this;

              

            

            //this.initpricerelated();

            //this.eventinput();

             

              

             

        },

        addId:function(id, title){

            

            var obj_article = new article;

            obj_article.id  = id;

            obj_article.title  = title;



            //FIND IF EXIST ---------------------------------------

            var find = false;

            for(var cont=0;cont<this.array_of_articles.length;cont++ )

            {

                if(this.array_of_articles[cont].id == id) {find = true;break}

            }



            //IF NOT EXIST ADD ---------------------------------------

            if(!find) this.array_of_articles[this.array_of_articles.length] = obj_article;



            //RENDER --------------------------------------------------

            this.render_articlesid();

        },

        render_articlesid: function()

        {

            document.id("field_"+this.id).value ="";

            document.getElementById("field_"+this.id+"_text").innerHTML ="" ; 

             

            for(var cont=0;cont<this.array_of_articles.length;cont++ )

            {

                var str = this.array_of_articles[cont].id ;

                if (this.array_of_articles.length-1>cont) str += ",";

                document.id("field_"+this.id).value += str;

                this.addLI("field_"+this.id+"_text", this.array_of_articles[cont].id, this.array_of_articles[cont].title);

            }

        },

        addLI:function(divid, id, text)

        {

            var Parent = document.getElementById(divid);

            var NewLI = document.createElement("LI");



            text = '<div style="position:relative;width:100%; padding:5px 0 5px 0;border-bottom:#ddd solid 1px;"><div style="  padding:5px 0 5px 0;">'+text+'</div>  <div style="position:absolute; top:10px; right:5px;"><a href="javascript:objpricerelated_'+this.id+'.RemoveId('+id+')" class="btn"><i title="Удалить" class="icon-remove"></i>Удалить</a></a></div></div>';



            NewLI.innerHTML = text; 

             

            Parent.appendChild(NewLI);

        },

        RemoveId: function(id)

        {

            for(var cont=0;cont<this.array_of_articles.length;cont++ )

            {

                var elid = this.array_of_articles[cont].id ;

                if(elid == id) this.array_of_articles.splice(cont,1);    

            }

            //RENDER --------------------------------------------------

            this.render_articlesid();

        } 

	

	 



});



//OBJECT ARTICLE =========================================

function article(id, title){

        this.id = id;

        this.title = title;

}


$(document).ready(function(){
    $('#selectAllBoxes').click(function(event){
        if(this.checked){
            $('.checkBoxes').each(function(){
                this.checked = true;
            });
        } else{
            $('.checkBoxes').each(function(){
                this.checked = false;
            });
        }
    });
  
   var div_box = "<div id='load-screen'><div id='loading'></div></div>" 
   $("body").prepend(div_box);

   $('#load-screen').delay(500).fadeOut(600,function(){
    $(this).remove();
   });
});


tinymce.init({selector:'textarea'});
$(document).ready(function (){
    ClassicEditor
    .create( document.querySelector( '#editor' ) )
    .catch( error => {
        console.error( error );
    } );
});

    ClassicEditor
        .create( document.querySelector( '#body' ) )
        .catch( error => {
            console.error( error );
        } );



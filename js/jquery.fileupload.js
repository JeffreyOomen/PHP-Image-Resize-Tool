$(document).ready(function() {

    // Because the cancel buttons are added dynamically, you have to
    // add the click event like this with reference to document
    $(document).on('click', ".cancel", function(e) {
        e.preventDefault();
        $(this).closest('tr').remove();      
    });

    $('.cancel-all').click(function(e) {
        e.preventDefault();
        $('#imageRows').empty(); 
    });

    // Add event listener for when new images are uploaded
    document.getElementById('files').addEventListener('change', handleFileSelect, false);
});


function handleFileSelect(evt) {
    $('#imageRows').empty(); // Empty table
    var files = evt.target.files; // FileList object

    // Loop through the FileList and render image files as thumbnails.
    for (var i = 0, f; f = files[i]; i++) {
        // Only process image files.
        if (!f.type.match('image.*')) {
            continue;
        }

        var reader = new FileReader();

        // Closure to capture the file information.
        reader.onload = (function (theFile) {
            return function (e) {
                var div = document.createElement("tr");
                div.innerHTML = "<td><img class='thumbnail' src='" + e.target.result + "'" +
                                "title='" + escape(theFile.name) + "' width='200px'/></td>" +
                                "<td><p class='name'>" + escape(theFile.name) + 
                                "</p><strong class='error text-danger'></strong></td>" +
                                "<td>" + humanFileSize(theFile.size) + "</td>" +
                                "<td><button type='submit' class='btn btn-primary start' name='submit'><i class='glyphicon glyphicon-upload'></i><span> Start</span></button> " +
                                "<button type='button' class='btn btn-warning cancel'><i class='glyphicon glyphicon-ban-circle'></i><span> Cancel</span></button></td>";                   
                //console.log("filename: " + picFile.name);
                document.getElementById('imageRows').insertBefore(div,null); 

                /*// Render thumbnail.
                var span = document.createElement('span');
                span.innerHTML = ['<img class="thumb" src="', e.target.result,
                    '" title="', escape(theFile.name), '"/>'].join('');
                document.getElementById('imageRows').insertBefore(span, null);*/
            };
        })(f);

        // Read in the image file as a data URL.
        reader.readAsDataURL(f);
    }
}

function humanFileSize(size) {
    var i = Math.floor( Math.log(size) / Math.log(1024) );
    return ( size / Math.pow(1024, i) ).toFixed(2) * 1 + ' ' + ['B', 'kB', 'MB', 'GB', 'TB'][i];
};

// Read in the image file as a data URL.

    /*window.onload = function() {
        //Check File API support
        if(window.File && window.FileList && window.FileReader) {
            var filesInput = document.getElementById("files");
            
            filesInput.addEventListener("change", function(event){
                
                var files = event.target.files; //FileList object
                var output = document.getElementById("imageRows");

                $('#imageRows').empty(); // Empty previous pictures

                for(var i = 0; i< files.length; i++) {
                    var file = files[i];
                    console.log("file is: " + file.name);
                    
                    //Only pics
                    if(!file.type.match('image'))
                      continue;
                    
                    var picReader = new FileReader();
                    picReader.addEventListener("load", function(event) {
                        var picFile = event.target;
                        console.log("file isssss: " + event.name);
                        
                        var div = document.createElement("tr");
                        
                        div.innerHTML = "<td><img class='thumbnail' src='" + picFile.result + "'" +
                                "title='" + picFile.name + "' width='200px'/></td>" +
                                "<td><p class='name'>" + picFile.name + "</p><strong class='error text-danger'></strong></td>" +
                                "<td></td>";
                                
                        console.log("filename: " + picFile.name);
                        
                        output.insertBefore(div,null);            
                    
                    });
                    
                     //Read the image
                    picReader.readAsDataURL(file);
                }                               
               
            });
        } else {
            console.log("Your browser does not support File API");
        }
}*/


/*function readURL(input) {

    if (input.files) {
        
        for (i = 0; i < input.files.length; i++) {
            var reader = new FileReader();
            $('#imagediv').prepend('<img id="theImg' + i + '" src="#" />');

            reader.onload = function (e) {
                $('#theImg' + i).attr('src', e.target.result).width(400);
            };
            reader.readAsDataURL(input.files[i]);
        }
    }
}*/
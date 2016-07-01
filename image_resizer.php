<?php
    if(isset($_POST["submit"]) && isset($_FILES['my_file'])) {

        if (isset($_FILES['my_file']) && !empty($_FILES['my_file']) && 
            isset($_POST['requestedWidth']) && !empty($_POST['requestedWidth']) && 
            isset($_POST['requestedHeight']) && !empty($_POST['requestedHeight'])) {

            $posted_images = $_FILES['my_file']; // Get posted images
            $image_count = count($posted_images['name']); // Get image count

            $requestedWidth = $_POST['requestedWidth']; // Get width
            $requestedHeight = $_POST['requestedHeight']; // Get height

            $array_image_names = array();
            for($i = 0; $i < $image_count; $i++) { // Save all names
                $array_image_names[] = $posted_images["name"][$i];
            }

            foreach ($array_image_names as $image_key => $image_name ) {
                $src_img = "images/$image_name"; // Source image that will be resized.
                $time = time();

               /* if (!file_exists($posted_images['tmp_name'][$image_key])) {
                    echo "File upload failed. ";
                    if (isset($posted_images['error'])) {
                         echo "Error code: ".$posted_images['error'];
                         print_r($posted_images['error']);
                    }
                    exit;
                }

                $image_info = getimagesize($posted_images["tmp_name"][$image_key]);
                $image_width = $image_info[0];
                echo "width: " + $image_width;
                $image_height = $image_info[1];
                echo "height: " + $image_height;
                $image_type = $image_info[2];
                echo "sssss" + $image_type;*/

                //information needed for the function
                $img_ext = strtolower(end(explode('.', $src_img)));
                $dst_img = 'resizedimages/'.$image_name.'.'.$img_ext; // This name will be given to the resized image.
                $dst_w= $requestedWidth; // The width of the resized image
                $dst_h = $requestedHeight; // The height of the resized image
                $dst_quality = '100'; // Quality of the resized image (best quality = 100)

                resizeImage($src_img, $dst_img, $dst_w, $dst_h, $dst_quality); // Call function to resize
            }
        }
    }
?>

<!doctype html>
<html>
    <head>
        <title>Test</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
        <script src="http://malsup.github.com/jquery.form.js"></script>
        <!-- Bootstrap styles -->
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
        <!-- File input styles -->
        <link rel="stylesheet" href="fileupload.css">
    </head>
    <body>
        <div class="container">
            <h1>Image Resizing Tool</h1>
            <br>
            <blockquote>
                <p>With this widget it is possible to upload several images which will be resized to a certain size. <br />
                    This size can be chosen by the end user himself. Works only with server installed. Drag and drop support will follow.</p>
            </blockquote>

            <div class="progress" style="display:none;" id="progressbar">
                <div class="progress-bar progress-bar-striped active bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%">
                </div>
                <div class="percent">0%</div>
            </div>

            
            <!-- <div class="progress">
                          <div class="bar"></div>
                          <div class="percent">0%</div>
                      </div>
                      <div id="status"></div>   --> 

            <form action="" method="post" enctype="multipart/form-data" id="myForm">

                <div class="row">
                    <div class="col col-lg-6">
                        <div class="input-group"><div class="input-group-btn">
                            <button type="button" class="btn btn-default">Width</button>
                        </div><input type="text" class="form-control" value="640" name="requestedWidth"/></div>
                    </div>
                    <div class="col col-lg-6">
                        <div class="input-group"><div class="input-group-btn">
                            <button type="button" class="btn btn-default">Height</button>
                        </div><input type="text" class="form-control" value="480" name="requestedHeight"/></div>
                    </div>
                </div>

                <br>

                <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
                <div class="row fileupload-buttonbar">
                    <div class="col-lg-12">
                        <!-- The fileinput-button span is used to style the file input field as button -->
                        <span class="btn btn-success fileinput-button">
                            <i class="glyphicon glyphicon-plus"></i>
                            <span>Add files...</span>
                            <input type="file" id="files" name="my_file[]" multiple >
                        </span>
                        <button type="submit" class="btn btn-primary start" name="submit">
                            <i class="glyphicon glyphicon-upload"></i>
                            <span>Start upload</span>
                        </button>
                        <button type="button" class="btn btn-warning cancel-all">
                            <i class="glyphicon glyphicon-ban-circle"></i>
                            <span>Cancel upload</span>
                        </button>
                        <button type="button" class="btn btn-danger delete">
                            <i class="glyphicon glyphicon-trash"></i>
                            <span>Delete</span>
                        </button>
                        <input type="checkbox" class="toggle">
                        <!-- The global file processing state -->
                        <span class="fileupload-process"></span>
                        
                    </div>


                    <!-- The global progress state -->
                    <div class="col-lg-5 fileupload-progress fade">
                        <!-- The global progress bar -->
                        <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                            <div class="progress-bar progress-bar-success" style="width:0%;"></div>
                        </div>
                        <!-- The extended global progress state -->
                        <div class="progress-extended">&nbsp;</div>
                    </div>
                </div>
                <!-- The table listing the files available for upload/download -->
                <table role="presentation" class="table table-striped"><tbody id="imageRows"></tbody></table>
            </form>
        </div>

        <!-- The File Upload processing plugin -->
        <script src="js/jquery.fileupload.js"></script>
        <script src="js/jquery.progressbar.js"></script>
  
    </body>
</html>

<?php 

function resizeImage($src_img, $dst_img, $dst_w, $dst_h, $dst_quality){
    //Stop and giving an error if the file does not exists.
    if(file_exists($src_img) == false){
        die('<p>The file does not exists. Check if the image "' . $src_img . '" is in the right path.</p>');
    }
        
    //Get variables for the function.
    $src_cpl = $src_img; //complete path of the source image.
    $dst_cpl = $dst_img; //complete path of the destination image.

    //end() advances array's internal pointer to the last element, and returns its value.
    //explode() returns an array of strings, each of which is a substring of string formed by splitting it on boundaries formed by the string delimiter.
    $src_ext = strtolower(end(explode('.', $src_img))); //extension excl "." of the source image.
    list($src_w, $src_h) = getimagesize($src_cpl); //width and height sizes of the source image.
    $src_type = exif_imagetype($src_cpl); //get type of image.

    //Checking extension and imagetype of the source image and path.
    if( ($src_ext =="jpg") && ($src_type =="2") ){
        $src_img = imagecreatefromjpeg($src_cpl);
    }else if( ($src_ext =="jpeg") && ($src_type =="2") ){
        $src_img = imagecreatefromjpeg($src_cpl);
    }else if( ($src_ext =="gif") && ($src_type =="1") ){
        $src_img = imagecreatefromgif($src_cpl);
    }else if( ($src_ext =="png") && ($src_type =="3") ){
        $src_img = imagecreatefrompng($src_cpl);
    }else{
        die('<p>The file "'. $src_img . '" with the extension "' . $src_ext . '" and the imagetype "' . $src_type . '" is not a valid image. Please upload an image with the extension JPG, JPEG, PNG or GIF and has a valid image filetype.</p>');
    }

    //Get heights and width so the image keeps its ratio.
    $x_ratio = $dst_w / $src_w;
    $y_ratio = $dst_h / $src_h;

    if( ($x_ratio > 1) || ($y_ratio > 1) && ($x_ratio >= $y_ratio) ){
            //If one of the sizes of the image is smaller than the destination (normal: more height than width).
        $dst_w = ceil($y_ratio * $src_w);
        $dst_h = $dst_h;
    }elseif( ($x_ratio > 1) || ($y_ratio > 1) && ($y_ratio > $x_ratio) ){
            //If one of the sizes of the image is smaller than the destination (landscape: more width than height).
        $dst_w = $dst_w;
        $dst_h = ceil($x_ratio * $src_h);
    }elseif (($x_ratio * $src_h) < $dst_h){
            //if the image is landscape (more width than height).
        $dst_h = ceil($x_ratio * $src_h);
        $dst_w = $dst_w;
    }elseif (($x_ratio * $src_h) > $dst_h){
            //if the image is normal (more height than width).
        $dst_w = ceil($y_ratio * $src_w);
        $dst_h = $dst_h;
    }else{
            //if the image is normal (more height than width).
        $dst_w = ceil($y_ratio * $src_w);
        $dst_h = $dst_h;
    }

    // Creating the resized image.
    $dst_img=imagecreatetruecolor($dst_w, $dst_h); // return the information about the image
    imagecopyresampled($dst_img,$src_img,0,0,0,0,$dst_w, $dst_h,$src_w,$src_h);

    // Saving the resized image.
    imagejpeg($dst_img, $dst_cpl, $dst_quality);
    // Cleaning the memory.
    imagedestroy($src_img);
    imagedestroy($dst_img);   
}
?>

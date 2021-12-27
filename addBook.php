<?php 
	$uploadDir = 'uploads/'; // Folder with uploaded bookcovers 
	$response = array( 
		'status' => 0, 
		'message' => 'Form submission failed, please try again.' 
	); 

	require 'details.php';
	if (!($con = mysqli_connect($server,$username,$password,$db))) {  
		die("Cannot connect to database!"); 
	}
	if(isset($_POST['inputFirstName']) || isset($_POST['inputSurname']) || isset($_POST['inputTitle']) || isset($_POST['inputDescription']) || isset($_POST['inputYear']) || isset($_POST['ISBN'])){ 
		$firstName = mysqli_real_escape_string($con,$_POST['inputFirstName']);
		$surName =  mysqli_real_escape_string($con,$_POST['inputSurname']);
		$midName = mysqli_real_escape_string($con,$_POST['inputMidName']);
		$title = mysqli_real_escape_string($con,$_POST['inputTitle']);
		$description = mysqli_real_escape_string($con,$_POST['inputDescription']);
		$genre = mysqli_real_escape_string($con,$_POST['inputGenre']);
		$year = mysqli_real_escape_string($con,$_POST['inputYear']);
		$ISBN = mysqli_real_escape_string($con,$_POST['ISBN']);
    

		// Check whether submitted data is not empty 
		if(!empty($firstName) && !empty($surName) && !empty($title) && !empty($description) && !empty($year) && !empty($ISBN)){ 
        // Validate Year 
			if(!is_numeric($year) || !ctype_digit($year)){ 
				$response['status'] = 2;
				$response['message'] = '❌ Year must be a number.'; 
			}elseif(strlen($ISBN) < 10 || strlen($ISBN) > 13) {
				$response['status'] = 3;
				$response['message'] = '❌ Incorrect ISBN format'; 
			}else{ 
            	$uploadStatus = 1;

				// Upload file 
				$uploadedFile = ''; 
				if(!empty($_FILES["bookCover"]["name"])){ 
                 
					// File path config 
					$fileName = basename($_FILES["bookCover"]["name"]); 
					$fileSize = $_FILES["bookCover"]["size"];
					$fileNameNew = uniqid('',true).'.'.$fileName;
					$targetFilePath = $uploadDir . $fileNameNew; 
					$fileType =  strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));  
                 
					// Allow certain file formats 
					$allowedTypes = array('jpg', 'png', 'jpeg'); 
					if(in_array($fileType, $allowedTypes)){ 
						// Upload file to the server 
						if ($fileSize < 2000000) {
							if(move_uploaded_file($_FILES["bookCover"]["tmp_name"], $targetFilePath)){ 
								$uploadedFile = $fileNameNew; 
							}else{ 
								$uploadStatus = 0; 
								$response['message'] = '❌ Sorry, there was an error uploading your file.'; 
							}  
						} else {
							$uploadStatus = 0; 
							$response['message'] = '❌ Image is too large (max size 2MB)';
						}
					}else{ 
						$uploadStatus = 0; 
						$response['message'] = '❌ Only JPG, JPEG, & PNG files are allowed to upload.'; 
				} 
				} 
             
				if($uploadStatus == 1){   
					// Insert form data in the database 
					$insertQuery = "INSERT INTO books (author_name,author_surname,middle_name,book_title, narration, genre, publication_year, ISBN,book_cover) VALUES (
						'" . $firstName . "' , '" . $surName . "' , '" . $midName . "' , '" . $title . "' , '" . $description . "' , '" . $genre . "' , '" . $year . "' , '" . $ISBN  . "','" . $uploadedFile . "')";
				
					if (mysqli_query($con, $insertQuery)){
						$response['status'] = 1; 
						$response['message'] = '✅ Succesfully inserted'; 
					} 
				}  
        	} 
    	}else{ 
			$response['message'] = '❌ Please fill all the required fields.'; 
    	} 
	} 
 
	// Return response 
	echo json_encode($response);

?>


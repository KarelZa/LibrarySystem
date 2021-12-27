<?php
    require 'details.php';
    $dir = "./uploads"; // Directory of uploaded images

    function time_elapsed_string($datetime, $full = false) {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);
    
        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;
    
        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }
    
        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }
    // Connection to the DB
    if (!($con = mysqli_connect($server,$username,$password,$db))) { 
		die("Cannot connect to database server!"); 
	}
    
    // Select Box options
    $options = [
        'id' => 'Sort by id',
        'yAsc' => 'publication (Oldest)',
        'yDesc' => 'publication (Latest)',
        'aAsc' => 'Author (A-Z)',
        'aDesc' => 'Author (Z-A)',
        'tAsc' => 'Title (A-Z)',
        'tDesc' => 'Title (Z-A)',
        //'multiply' => 'x'
    ];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advanced Search</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="css/records.css">
</head>
<body class="advancedSearch">
    <div class="container text-light ">
        <div class="wrapper px-4 py-4 ">
            <div  class="container d-flex  flex-column   justify-content-center align-items-center ">
                <form  id="searchBook" method="POST" autocomplete="off" enctype="multipart/form-data" class="position-relative needs-validation">  
                    <div id='inner' class="form-inner px-4 py-4 fs-5">
                        <div class="row">
                            <div class="col-sm-12 ">
                                <h1 class='text-center display-4 mt-3 mb-4 myBoldHeading text-dark'>Advanced Search</h1>
                            </div>
                        </div>

                        <!-- First row ==> AUTHOR(name,surname) & Title -->
                        <div class="row ">
                            <div class="col-md-4">
                                <label for="inputFirstName" class="form-label">Author</label>
                                <input type="text" class="form-control form-control-lg bg-light bg-opacity-75" id="inputFirstName" name="inputAuthor"  placeholder="firstname or surname" value="<?php  if(isset($_POST['inputAuthor'])) {echo htmlspecialchars($_POST['inputAuthor']); } ?>">
                            </div>
                            <div class="col-md-4 ">
                                <label for="inputTitle" class="form-label">Title</label>
                                <input type="text" class="form-control form-control-lg bg-light bg-opacity-75" id="inputTitle" name="inputTitle" placeholder="Book title" value="<?php  if(isset($_POST['inputTitle'])) {echo htmlspecialchars($_POST['inputTitle']); } ?>">
                            </div> 
                            <div class="col-md-4 ">
                                <label for="inputGenre" class="form-label">Genre</label>
                                <input type="text" class="form-control form-control-lg bg-light bg-opacity-75" id="inputGenre" name="inputGenre" placeholder="Book genre" value="<?php  if(isset($_POST['inputGenre'])) {echo htmlspecialchars($_POST['inputGenre']); } ?>">
                            </div> 
                        </div>

                        <!-- Second row ==> ISBN and SELECT -->
                        <div class="row mt-2">
                            <div class="col-md-4">
                                <label for="ISBN" class="form-label">ISBN number</label>
                                <input type="text" class="form-control form-control-lg bg-light bg-opacity-75" id="ISBN" name="ISBN" placeholder="10 or 13 format" value='<?php  if(isset($_POST['ISBN'])) {echo htmlspecialchars($_POST['ISBN']); } ?>'>
                            </div>
                            <div class="col-md-3 mt-2">
                                <label for="sort" class="form-label"></label>
                                <select id="sort" name="sort" class="form-select form-select-lg bg-light bg-opacity-75">
                                    <!-- Select box managed by PHP -->
                                    <?php foreach ($options as $key => $label) { ?>
                                        <option value="<?= $key ?>" <?= (isset($_POST['sort']) && $_POST['sort'] == $key) ? 'selected' : '' ?>><?= $label ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <!-- Third row ==> Buttons  -->
                        <div class="row mt-3"> 
                            <div class="col-md-6 d-grid gap-2 d-md-block mt-2">
                                <a href="libraryRecords.php" class="btn btn-dark py-2 fs-5">Display all books</a>
                                <a href="form.php" class="btn btn-dark py-2 fs-5 ">Add book</a>   
                            </div> 
                            <div class="col-12 col-md-2 offset-md-4 d-md-flex justify-content-md-end align-items-md-center mt-2  ">
                                <button id='searchSubmit' class="btn btn-dark py-2  fs-5 w-100" type="submit" name="searchSubmit">Search</button>
                            </div>
                        </div>

                    </div>   
                </form>  
            </div>  
           
            <!-- PHP to query DB -->
            <?php
                if(isset($_POST['inputAuthor']) || isset($_POST['inputTitle']) || isset($_POST['inputGenre']) || isset($_POST['ISBN'])) {
                    $author = mysqli_real_escape_string($con ,$_POST["inputAuthor"]);
                    $title = mysqli_real_escape_string($con ,$_POST["inputTitle"]);
                    $genre = mysqli_real_escape_string($con ,$_POST["inputGenre"]);
                    $ISBN = mysqli_real_escape_string($con ,$_POST["ISBN"]);
                    if(isset($_POST['sort'])) {
                        $sort = mysqli_real_escape_string($con ,$_POST["sort"]);
                    }                        
                    // Sql query 
                    $sql = "SELECT * FROM books WHERE 
                    ((author_name = '$author' OR author_surname = '$author' OR middle_name = '$author') AND genre = '$genre' AND book_title = '$title') OR
                    ((author_name = '$author' OR author_surname = '$author' OR middle_name = '$author') AND book_title = '$title') OR
                    (author_name = '$author' OR author_surname = '$author') OR (book_title = '$title' AND genre = '$genre') OR ISBN = '$ISBN' OR  genre = '$genre'
                    ORDER BY ";
                    // Order By switch statement 
                    switch ($sort)
                    {
                        case 'yAsc': { $sql .= "publication_year"; break; }
                        case 'yDesc': { $sql .= "publication_year DESC"; break; }
                        case 'aDesc': { $sql .= "author_surname DESC"; break; }
                        case 'aAsc': { $sql .= "author_surname"; break; }
                        case 'tAsc': { $sql .= "book_title"; break; }
                        case 'tDesc': { $sql .= "book_title DESC"; break; }
                        default: { $sql .= "book_id"; break; } // By default, let's sort by ID
                    }

                    $result = mysqli_query($con, $sql);
                    if (!($result)) {
                        die("Request cannot be completed.</body></html>");
                    }
                    // Check whether query return any rows 
                    if ($result->num_rows != 0) {   
                        ?> 
                    
                        <div class="row col-12 col-md-10 offset-md-1 mx-auto mt-5 bg-dark">
                            <div class="col-3 p-2">
                                <p class="my-auto p-2">Search results</p>
                            </div>
                        </div>
                    
                        <?php
                        while ($row = mysqli_fetch_array($result)) : 
            ?>
                <!-- HTML TO DISPLAY RESULTS FROM DB based on Query -->
                <div class="card my-4 col-12 col-md-10 mx-auto text-dark bookArticle ">
                    <div class="row g-0">
                        <div class="col-3 col-md-3 my-auto p-2 ">
                            <div class="row">
                                <img src="<?php echo $dir . "/" . $row['book_cover'] ?>" class="img-fluid w-50 mx-auto rounded-start" alt="...">
                            </div>
                            <div class="row">
                            <span class="card-text text-center mt-2 fw-light">ISBN: <?php echo $row['ISBN'] ?></span>
                            </div>
                        </div>	
                        <div class="col-9 col-md-9 ">
                            <div class="card-body p-2 px-3 py-md-4">
                                <h4 class="card-title ">
                                    <?php echo $row['book_title'] ?><small class="text-muted">&nbsp;by&nbsp;</small><?php echo $row['author_name'] . " " . $row['middle_name'] . " " . $row['author_surname']  ?>
                                </h4>
                                <span class="card-text">
                                    Published: <?php echo $row['publication_year'] ?>
                                </span> <br>
                                <span class="card-text">
                                    Genre: <?php echo $row['genre'] ?>
                                </span><br>
                                <p class="card-text my-3 bookDesc">
                                    <?php echo $row['narration'] ?>
                                </p>
                                <p class="card-text d-flex justify-content-end mt-4">
                                    <small class="text-muted">Book was added <?php echo time_elapsed_string($row['entry_timestamp'])
                                    ?> </small>
                                    </p>
                            </div>
                        </div>
                    </div>
                </div>

            <?php 
                        endwhile; 
                        // NO RESULTS FOUND!!!
                    } else {
            ?>
                <div class="row mt-3 ">
                    <div class="col-12">
                        <p class="text-center fs-4">Sorry, No results have been found!</p>
                    </div>
                </div>
            <?php  
                } 
                // Free results , when they are not needed anymore
                mysqli_free_result($result);
            }
                // Closes DB
                mysqli_close($con); 
            ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="app.js"></script>
</body>
</html>
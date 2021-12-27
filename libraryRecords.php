<?php
    require 'details.php';
    $con = mysqli_connect($server,$username,$password,$db);
    // Pokud nelze navazat spojeni s mysql serverem
    if (!$con) {
        die("Cannot connect to database!</body></html>");
    }

    $dir = "./uploads";
    $results_per_page = 5;
    $page = isset($_GET['page']) ? $_GET['page'] : 1; // Decides which page is currently visited
    $previousPage = $page - 1; // Previous page
    $nextPage = $page + 1; // Next page

    // Decides the sql LIMIT starting number for the results on the current page
    $starting_limit = ($page - 1) * $results_per_page; // so for page 1 == (1-1)*3 = 0, for page 2 == (2-1)*3 = 3 etc.

    // retrieve selected results from database and display them on page
    $sql = "SELECT author_name,author_surname,middle_name,book_title,narration,genre,publication_year,ISBN,book_cover,entry_timestamp FROM books LIMIT " . $starting_limit . "," . $results_per_page;
    $result = mysqli_query($con,$sql); // query db and store it in $var
    
    // PAGINATION
    $get_all_records = mysqli_query($con,"SELECT * FROM books");
    $number_of_rows = mysqli_num_rows($get_all_records); // store number of records in db
    $num_of_pages  = ceil($number_of_rows/$results_per_page); // Decides how many pages do we need
    // Function to convert Timestamp from db to ... d/m/y ago
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
?>


<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Book Records</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="css/records.css">
</head>
<body class="allRecords">
    <div class="container text-light ">
        <div class="wrapper px-4 py-4 ">
            <h1 class='text-center myBoldHeading display-3 text-dark'>Book Records</h1>  
            <div class="row col-10 mx-auto p-0 m-0">
                    <div class="col 12 col-md-6 my-auto text-sm-center text-md-start d-grid d-sm-block gap-2  p-0">
                        <a href="form.php" class="btn btn-dark py-2 fs-5 mt-lg-0">Add Book</a>
                        <a href="searchForm.php" class="btn btn-dark py-2 fs-5 mt-md-2 mt-lg-0  ">Search</a>
                    </div> 
                <div class="col-12 col-md-6 d-md-flex justify-content-end p-0">
                    <nav aria-label="Top Pagination " >
                        <ul class="pagination  justify-content-center my-auto py-3 fs-4">

                            <li class="page-item  <?php echo $page == 1 ? 'disabled' : ''; ?> ">
                                <a class="page-link " href="libraryRecords.php?page=<?php echo $previousPage; ?>" aria-label="Previous" >
                                    <span class="fw-bold" aria-hidden="true">&laquo;</span>
                                </a>
                            </li>

                            <?php for ($i=1; $i <= $num_of_pages; $i++) : ?>
                            <li class="page-item  <?php echo $page == $i ? 'isActive' : ''; ?>">
                                <a class="page-link" href="libraryRecords.php?page=<?php echo $i; ?>"><span class="sr-only"><?php echo $i; ?></span></a>
                            </li>
                            <?php endfor; ?>

                            <li class="page-item <?php echo $page == $num_of_pages ? 'disabled' : ''; ?>">
                                <a class="page-link" href="libraryRecords.php?page=<?php echo $nextPage; ?>" aria-label="Next">
                                    <span class="fw-bold" aria-hidden="true">&raquo;</span>
                                </a>
                            </li>

                        </ul>
                    </nav>
                </div>
            </div> 
            <?php
                // V cyklu vypise vsechny data ktere jsou v promenne associativniho pole $vysledek, ziskane skrze mysqli_fetch_array();
                while ($row = mysqli_fetch_array($result)) : 
            ?>
        
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
            mysqli_free_result($result); // Uvolnuje misto se ziskanymi daty jelikoz jiz nejsou potreba
            mysqli_close($con); // Uzavira spojeni se serverem mysql
            ?>
            <div class="row">
                <div class="col-12">
                    <nav aria-label="Top Pagination " >
                        <ul class="pagination  justify-content-center my-auto py-3 fs-4">

                            <li class="page-item  <?php echo $page == 1 ? 'disabled' : ''; ?> ">
                                <a class="page-link " href="libraryRecords.php?page=<?php echo $previousPage; ?>" aria-label="Previous" >
                                    <span class="fw-bold" aria-hidden="true">&laquo;</span>
                                </a>
                            </li>

                            <?php for ($i=1; $i <= $num_of_pages; $i++) : ?>
                            <li class="page-item  <?php echo $page == $i ? 'isActive' : ''; ?>">
                                <a class="page-link" href="libraryRecords.php?page=<?php echo $i; ?>"><span class="sr-only"><?php echo $i; ?></span></a>
                            </li>
                            <?php endfor; ?>

                            <li class="page-item <?php echo $page == $num_of_pages ? 'disabled' : ''; ?>">
                                <a class="page-link" href="libraryRecords.php?page=<?php echo $nextPage; ?>" aria-label="Next">
                                    <span class="fw-bold" aria-hidden="true">&raquo;</span>
                                </a>
                            </li>

                        </ul>
                    </nav>
                </div>
            </div> 
        </div>
    </div>
    

    <!-- <br>
	<a href="form.php">Add a new book</a><br>
	<a href="authorSearchForm.php">Search for a book</a> <br> -->
</body>
</html>
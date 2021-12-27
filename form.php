<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Library in PHP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div  class="container d-flex  flex-column   justify-content-center align-items-center ">
        <form  id="bookForm" method="POST" action="addBook.php" autocomplete="off" enctype="multipart/form-data" class="position-relative needs-validation">  
            <div id='inner' class="form-inner px-4 py-4 fs-5">
                <div class="row">
                    <div class="col-sm-12 ">
                        <h1 class='text-center display-3 mt-3 mb-4 myBoldHeading'>Add New Book</h1>
                    </div>
                </div>
                <div class="row ">
                    <div class="col-sm-4">
                        <label for="inputFirstName" class="form-label">Author's name<span class='text-danger'>*</span></label>
                        <input type="text" class="form-control form-control-lg bg-light bg-opacity-75" id="inputFirstName" name="inputFirstName">
                    </div>
                    <div class="col-sm-4">
                        <label for="inputSurname" class="form-label">Last name<span class='text-danger'>*</span></label>
                        <input type="text" class="form-control form-control-lg bg-light bg-opacity-75" id="inputSurname" name="inputSurname">
                    </div>
                    <div class="col-sm-4">
                        <label for="inputFirstName" class="form-label">Middle name</label>
                        <input type="text" class="form-control form-control-lg bg-light bg-opacity-75" id="inputMidName" name="inputMidName">
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-sm-12 col-md-7">
                        <label for="inputTitle" class="form-label">Title<span class='text-danger'>*</span></label>
                        <input type="text" class="form-control form-control-lg bg-light bg-opacity-75 myInput" id="inputTitle" name="inputTitle" placeholder="Book title">
                    </div> 
                    <div class="col-sm-12 col-md-5">
                        <label for="bookCover" class="form-label">Book Cover</label>
                        <input class="form-control form-control-lg bg-light bg-opacity-75" type="file" id="bookCover" name="bookCover" accept="image/x-png,image/gif,image/jpeg">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 mt-2">
                        <label for="inputDescription" class="form-label">Description<span class='text-danger'>*</span></label>
                        <input type="text" class="form-control form-control-lg bg-light bg-opacity-75" id="inputDescription" name="inputDescription" placeholder="Quick summary of the story">
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-5">
                        <label for="inputGenre" class="form-label">Genre</label>
                        <select id="inputGenre" name="inputGenre" class="form-select form-select-lg bg-light bg-opacity-75">
                        <option value="undefined" selected>Undefined</option>
                        <option value="fantasy">Fantasy</option>
                        <option value="romance">Romance</option>
                        <option value="thriller">Thriller</option>
                        <option value="mystery">Mystery</option>
                        <option value="science-fiction">Science-fiction</option>
                        <option value="non-fiction">Non-fiction</option>
                        <option value="horror">Horror</option>
                        <option value="biography">Biography</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="inputYear" class="form-label">Year<span class='text-danger'>*</span></label>
                        <input type="text" class="form-control form-control-lg bg-light bg-opacity-75" id="inputYear" name="inputYear">
                    </div>
                    <div class="col-md-5">
                        <label for="ISBN" class="form-label">ISBN number<span class='text-danger'>*</span></label>
                        <input type="text" class="form-control form-control-lg bg-light bg-opacity-75" id="ISBN" name="ISBN">
                    </div>
                </div>
                <div class="row">
                    <p class='pt-4 ms-1 mt-2'><span class='text-danger'>*</span>Required</p>
                </div>
                <div class="row mt-1"> 
                    <div class="col-md-6 d-grid gap-2 d-md-block mt-2">
                        <a href="libraryRecords.php" class="btn btn-dark py-2 fs-5">Display all books</a>
                        <a href="searchForm.php" class="btn btn-dark py-2 fs-5 ">Search</a>
                        
                    </div> 
                    <div class="col-12 col-md-2 offset-md-4 d-md-flex justify-content-md-end align-items-md-center mt-2  ">
                        <!-- <a href="Form.php" class="btn btn-dark py-2  fs-5 w-100">Add</a> -->
                        <button id='submit' class="btn btn-dark py-2  fs-5 w-100" type="submit" name="submit">Add</button>
                    </div>
                </div>
            </div>   
        </form>  
    </div>  

    <script src="js/app.js"></script>
</body>
</html>
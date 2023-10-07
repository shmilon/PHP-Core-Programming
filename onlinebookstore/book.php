<?php
session_start();
$book_isbn = $_GET['bookisbn'];
// connecto database
require_once "./functions/database_functions.php";
$conn = db_connect();

$query = "SELECT * FROM books WHERE book_isbn = '$book_isbn'";
$result = mysqli_query($conn, $query);
if (!$result) {
  echo "Can't retrieve data " . mysqli_error($conn);
  exit;
}

$row = mysqli_fetch_assoc($result);
if (!$row) {
  echo "Empty book";
  exit;
}

$title = $row['book_title'];
require "./template/header.php";
?>
<!-- Example row of columns -->
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="books.php" class="text-decoration-none text-muted fw-light">PublBooksishers</a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">
      <?php echo $row['book_title']; ?>
    </li>
  </ol>
</nav>
<div class="row">
  <div class="col-md-3 text-center book-item">
    <div class="img-holder overflow-hidden">
      <img class="img-top" src="./bootstrap/img/<?php echo $row['book_image']; ?>">
    </div>
  </div>
  <div class="col-md-9">
    <div class="card rounded-0 shadow">
      <div class="card-body">
        <div class="container-fluid">
          <h4>
            <?= $row['book_title'] ?>
          </h4>
          <hr>
          <p>
            <?php echo $row['book_descr']; ?>
          </p>
          <h4>Details</h4>
          <table class="table">
            <?php foreach ($row as $key => $value) {
              if ($key == "book_descr" || $key == "book_image" || $key == "publisherid" || $key == "book_title") {
                continue;
              }
              switch ($key) {
                case "book_isbn":
                  $key = "ISBN";
                  break;
                case "book_title":
                  $key = "Title";
                  break;
                case "book_author":
                  $key = "Author";
                  break;
                case "book_price":
                  $key = "Price";
                  break;
              }
              ?>
              <tr>
                <td>
                  <?php echo $key; ?>
                </td>
                <td>
                  <?php echo $value; ?>
                </td>
              </tr>
              <?php
            }
            if (isset($conn)) {
              mysqli_close($conn);
            }
            ?>
          </table>
          <form method="post" action="cart.php" style="display:inline-block">
            <input type="hidden" name="bookisbn" value="<?php echo $book_isbn; ?>">
            <div class="text-center">
              <input type="submit" value="Purchase / Add to cart" name="cart" class="btn btn-primary rounded-0">
            </div>
          </form>


          <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] == true): ?>

            <div class="btn-group btn-group-sm " style="height:38px">
              <a href="admin_edit.php?bookisbn=<?php echo $row['book_isbn']; ?>" class="btn btn-sm rounded-0 btn-primary"
                title="Edit">Edit <svg class="svg-inline--fa fa-pen-to-square" aria-hidden="true" focusable="false"
                  data-prefix="fas" data-icon="pen-to-square" role="img" xmlns="http://www.w3.org/2000/svg"
                  viewBox="0 0 512 512" data-fa-i2svg="">
                  <path fill="currentColor"
                    d="M490.3 40.4C512.2 62.27 512.2 97.73 490.3 119.6L460.3 149.7L362.3 51.72L392.4 21.66C414.3-.2135 449.7-.2135 471.6 21.66L490.3 40.4zM172.4 241.7L339.7 74.34L437.7 172.3L270.3 339.6C264.2 345.8 256.7 350.4 248.4 353.2L159.6 382.8C150.1 385.6 141.5 383.4 135 376.1C128.6 370.5 126.4 361 129.2 352.4L158.8 263.6C161.6 255.3 166.2 247.8 172.4 241.7V241.7zM192 63.1C209.7 63.1 224 78.33 224 95.1C224 113.7 209.7 127.1 192 127.1H96C78.33 127.1 64 142.3 64 159.1V416C64 433.7 78.33 448 96 448H352C369.7 448 384 433.7 384 416V319.1C384 302.3 398.3 287.1 416 287.1C433.7 287.1 448 302.3 448 319.1V416C448 469 405 512 352 512H96C42.98 512 0 469 0 416V159.1C0 106.1 42.98 63.1 96 63.1H192z">
                  </path>
                </svg><!-- <i class="fa fa-edit"></i> Font Awesome fontawesome.com --></a>
              <a href="admin_delete.php?bookisbn=<?php echo $row['book_isbn']; ?>" class="btn btn-sm rounded-0 btn-danger"
                title="Delete"
                onclick="if(confirm('Are you sure to delete this book?') === false) event.preventDefault()">Delete <svg
                  class="svg-inline--fa fa-trash" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="trash"
                  role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg="">
                  <path fill="currentColor"
                    d="M135.2 17.69C140.6 6.848 151.7 0 163.8 0H284.2C296.3 0 307.4 6.848 312.8 17.69L320 32H416C433.7 32 448 46.33 448 64C448 81.67 433.7 96 416 96H32C14.33 96 0 81.67 0 64C0 46.33 14.33 32 32 32H128L135.2 17.69zM394.8 466.1C393.2 492.3 372.3 512 346.9 512H101.1C75.75 512 54.77 492.3 53.19 466.1L31.1 128H416L394.8 466.1z">
                  </path>
                </svg><!-- <i class="fa fa-trash"></i> Font Awesome fontawesome.com --></a>
            </div>
          <?php endif; ?>

        </div>
      </div>
    </div>
  </div>
</div>
<?php
require "./template/footer.php";
?>